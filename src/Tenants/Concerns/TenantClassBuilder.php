<?php

namespace Tarre\BeyondCrudHelpers\Tenants\Concerns;

use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Resources\Tenant;

interface TenantClassBuilder
{
    public function __invoke(Tenant $tenant, Command $command): ClassType;
}
