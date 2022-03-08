<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;
use Tarre\BeyondCrudHelpers\Resources\Tenant;

class ActionTenant implements TenantClassBuilder
{
    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;

        if ($command->option('construct')) {
            $class
                ->addMethod('__construct')
                ->setBody('// TODO: implement')
                ->setPublic();
        }

        $class
            ->addMethod('__invoke')
            ->setReturnType('void')
            ->setBody('// TODO: implement ')
            ->setPublic();

        return $class;
    }

}
