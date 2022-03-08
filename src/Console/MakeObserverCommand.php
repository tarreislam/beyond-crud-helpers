<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ObserverTenant;

class MakeObserverCommand extends BaseConsole
{
    public $signature = 'mmake:observer {name} {location?} {--model=} {--force}';

    public function tenant(): string
    {
        return 'observer';
    }

    public function tenantClassName(): string
    {
        return ObserverTenant::class;
    }
}
