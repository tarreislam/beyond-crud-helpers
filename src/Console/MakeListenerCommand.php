<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ListenerTenant;

class MakeListenerCommand extends BaseConsole
{
    public $signature = 'mmake:listener {name} {location?} {--force}';

    public function tenant(): string
    {
        return 'listener';
    }

    public function tenantClassName(): string
    {
        return ListenerTenant::class;
    }
}
