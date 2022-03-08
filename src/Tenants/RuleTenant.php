<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Rule;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class RuleTenant implements TenantClassBuilder
{
    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
       $class = new ClassType;

       $class->addImplement(Rule::class);

       $passesMethod = $class->addMethod('passes')->setBody('return false;');
       $passesMethod->addParameter('attribute');
       $passesMethod->addParameter('value');

       $class->addMethod('message')->setBody('return [];');

       return $class;
    }
}
