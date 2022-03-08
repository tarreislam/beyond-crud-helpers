<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class QueryTenant implements TenantClassBuilder
{
    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;

        if ($tenant->getRelatedModel()) {

            $modelName = $tenant->getRelatedModelClassName();
            $class
                ->addMethod('base')
                ->setBody("return $modelName::class;");
        }

        return $class;
    }
}
