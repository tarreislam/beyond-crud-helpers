<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\PolicyTenant;

class MakePolicyCommand extends BaseConsole
{
    public $signature = 'mmake:policy {name} {location?} {--model=} {--force}';

    public function tenant(): string
    {
        return 'policy';
    }

    public function tenantClassName(): string
    {
        return PolicyTenant::class;
    }
}
