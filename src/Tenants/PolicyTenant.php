<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class PolicyTenant implements TenantClassBuilder
{
    protected $resources = [
        'viewAny',
        'view',
        'create',
        'update',
        'delete'
    ];

    protected $withoutModel = [
        'viewAny',
        'create'
    ];

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;

        if ($tenant->getRelatedModel()) {

            $authUserModel = $this->resolveAuthUserClass();
            $targetModelName = $tenant->getRelatedModelClassName();
            $parameterName = $tenant->getSuggestedVariableName();

            foreach ($this->resources as $resource) {
                $method = $class->addMethod($resource)->setReturnType('bool')->setBody('return false;');
                $method->addParameter('authedUser')->setType($authUserModel);

                if (!in_array($resource, $this->withoutModel)) {
                    $method->addParameter($parameterName)->setType($targetModelName);
                }
            }
        }

        return $class;
    }

    protected function resolveAuthUserClass()
    {
        $configModel = config('auth.providers.users.model');

        return "\\$configModel";
    }

}
