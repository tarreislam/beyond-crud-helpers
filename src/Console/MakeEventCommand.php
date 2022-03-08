<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\EventTenant;

class MakeEventCommand extends BaseConsole
{
    public $signature = 'mmake:event {name} {location?} {--model=} {--force}';

    public function tenant(): string
    {
        return 'event';
    }

    public function tenantClassName(): string
    {
        return EventTenant::class;
    }
}
