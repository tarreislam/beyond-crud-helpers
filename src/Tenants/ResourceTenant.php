<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class ResourceTenant implements TenantClassBuilder
{

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;
        $method = $class->addMethod('toArray')->setBody('return [];');
        $method->addParameter('request');
        return $class;
    }
}
