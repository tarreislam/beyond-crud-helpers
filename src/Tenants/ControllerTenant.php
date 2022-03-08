<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;
use Tarre\BeyondCrudHelpers\Resources\Tenant;

class ControllerTenant implements TenantClassBuilder
{
    protected $methods = [
        'index',
        'show',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ];

    protected $hasParams = [
        'show',
        'update',
        'edit',
        'destroy'
    ];

    protected $hasRequests = [
        'store',
        'update'
    ];

    protected $excludeWhenApi = [
        'create',
        'edit'
    ];

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {

        if ($command->option('invoke')) {
            return $this->returnAsInvoke($tenant);
        }

        $class = new ClassType;

        foreach ($this->methods as $methodName) {

            if (in_array($methodName, $this->excludeWhenApi) && $command->option('api')) {
                continue;
            }

            $method = $class->addMethod($methodName)->setBody("// TODO: implement $methodName");

            if (in_array($methodName, $this->hasRequests)) {
                $method->addParameter('request')->setType(Request::class);
            }

            if (in_array($methodName, $this->hasParams)) {
                $suggestedName = $tenant->getSuggestedVariableName();

                if (!$modelName = $tenant->getRelatedModelClassName()) {
                    $suggestedName = "{$suggestedName}Id";
                }

                $param = $method->addParameter($suggestedName);

                if ($modelName) {
                    $param->setType($modelName);
                }
            }
        }

        return $class;
    }

    protected function returnAsInvoke(Tenant $tenant): ClassType
    {
        $class = new ClassType;

        $method = $class->addMethod('__invoke')->setBody('// TODO: implement method');
        if ($tenant->getRelatedModel()) {
            $method->addParameter($tenant->getSuggestedVariableName())->setType($tenant->getRelatedModelClassName());
        }
        return $class;
    }

}
