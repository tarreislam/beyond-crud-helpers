<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class ObserverTenant implements TenantClassBuilder
{
    protected $events = [
        'creating',
        'created',
        'saving',
        'saved',
        'updating',
        'updated',
        'destroying',
        'destroyed'
    ];

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;

        if (!$tenant->getRelatedModel()) {
            $tenant->setRelatedModel($tenant->getLocation());
        }

        foreach ($this->events as $eventName) {
            $method = $class->addMethod($eventName)->setBody('//');
            $method->addParameter($tenant->getSuggestedVariableName())->setType($tenant->getRelatedModelClassName());
        }

        return $class;
    }
}
