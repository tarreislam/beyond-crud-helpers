<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ActionTenant;

class MakeActionCommand extends BaseConsole
{
    public $signature = 'mmake:action {name} {location?} {--construct} {--force}';

    public function tenant(): string
    {
        return 'action';
    }

    public function tenantClassName(): string
    {
        return ActionTenant::class;
    }
}
