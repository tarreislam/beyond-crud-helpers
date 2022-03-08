<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class JobTenant implements TenantClassBuilder
{
    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;
        $class->addImplement(ShouldQueue::class);
        $class->addTrait(Dispatchable::class);
        $class->addTrait(InteractsWithQueue::class);
        $class->addTrait(Queueable::class);
        $class->addTrait(SerializesModels::class);
        $method = $class->addMethod('__construct')->setBody('//');
        if ($tenant->getRelatedModel()) {
            Helper::injectParamToClassMethod($class, $method, $tenant);
        }
        $class->addMethod('handle')->setReturnType('void');
        return $class;
    }
}
