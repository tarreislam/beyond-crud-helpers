<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use Nette\PhpGenerator\Property;

class DTOTenant implements TenantClassBuilder
{
    protected Tenant $tenant;

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $this->tenant = $tenant;

        /*
         * Create class and append method and properties
         */
        $class = new ClassType;
        $class->setFinal(true);

        $table = $command->option('table');

        if (!is_null($table)) {
            $properties = Helper::getPropertiesFromTable($table);
            $class->setProperties($properties);
            $class->setMethods([$this->createFromRequestMethod($properties), $this->createFromModelMethod($properties)]);
        } else {
            $class
                ->addMethod('fromRequest')
                ->setReturnType('self')
                ->setStatic()
                ->setBody('return new self([]);')
                ->addParameter('request')
                ->setType(\Illuminate\Http\Request::class);
            $class
                ->addMethod('fromModel')
                ->setReturnType('self')
                ->setStatic()
                ->setBody('return new self([]);')
                ->addParameter('model')
                ->setType(Model::class);

        }

        /*
         * add class to namespace
         */
        return $class;
    }

    protected function createFromRequestMethod(array $properties): Method
    {
        /*
         * Create method body for FromRequest
         */
        $propertiesAsStringBodyForFromRequest = array_map(function (Property $prop) {
            $propName = $prop->getName();
            $value = "\$request->get('$propName')";
            return "    '$propName' => $value";
        }, $properties);
        $propertiesAsStringBodyForFromRequest = implode(',' . PHP_EOL, $propertiesAsStringBodyForFromRequest);
        $propertiesAsStringBodyForFromRequest = '[' . PHP_EOL . $propertiesAsStringBodyForFromRequest . PHP_EOL . ']';
        /*
        * Create method and parameter for metod
        */
        $requestParameter = new Parameter('request');
        $requestParameter->setType(\Illuminate\Http\Request::class);
        $fromRequest = new Method('fromRequest');
        $fromRequest->setParameters([$requestParameter]);
        $fromRequest->setPublic();
        $fromRequest->setStatic();
        $fromRequest->setBody('return new self(' . $propertiesAsStringBodyForFromRequest . ');');
        $fromRequest->addComment('@param Request $request');
        $fromRequest->addComment('@return static');
        $fromRequest->setReturnType('self');

        return $fromRequest;
    }

    protected function createFromModelMethod(array $properties): Method
    {
        /*
       * Create method body for FromRequest
       */
        $propertiesAsStringBodyForFromModel = array_map(function (Property $prop) {
            $propName = $prop->getName();
            /*
             * Base
             */
            $value = "\$" . $this->tenant->getSuggestedVariableName() . "->$propName";
            return "    '$propName' => $value";
        }, $properties);
        $propertiesAsStringBodyForFromModel = implode(',' . PHP_EOL, $propertiesAsStringBodyForFromModel);
        $propertiesAsStringBodyForFromModel = '[' . PHP_EOL . $propertiesAsStringBodyForFromModel . PHP_EOL . ']';
        /*
         * FromMOdel
         */
        $modelParameter = new Parameter($this->tenant->getSuggestedVariableName());
        $modelParameter->setType(Model::class);
        $fromModel = new Method('fromModel');
        $fromModel->setParameters([$modelParameter]);
        $fromModel->setPublic();
        $fromModel->setStatic();
        $fromModel->setBody('return new self(' . $propertiesAsStringBodyForFromModel . ');');
        $fromModel->addComment('@param Model $model');
        $fromModel->addComment('@return static');
        $fromModel->setReturnType('self');
        return $fromModel;
    }

}
