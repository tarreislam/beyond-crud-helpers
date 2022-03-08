<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class EventTenant implements TenantClassBuilder
{

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;

        $method = $class->addMethod('__construct');

        if ($tenant->getRelatedModel()) {
            Helper::injectParamToClassMethod($class, $method, $tenant);
        }

        return $class;
    }
}
