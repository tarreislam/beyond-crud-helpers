<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class DTOCollectionTenant implements TenantClassBuilder
{

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;
        $class->addMethod('current')
            ->setBody('return parent::current();')
            ->setComment('TODO: set return type of method');
        $method = $class
            ->addMethod('create')
            ->setReturnType('self')
            ->setStatic()
            ->setComment('TODO: array_map rows with other DTO before returning self')
            ->setBody('return new self($rows);');
        $method->addParameter('rows')->setType('array');
        return $class;
    }
}
