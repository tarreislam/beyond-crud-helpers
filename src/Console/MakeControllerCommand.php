<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ControllerTenant;

class MakeControllerCommand extends BaseConsole
{
    public $signature = 'mmake:controller {name} {location?} {--invoke} {--api} {--model=} {--force}';

    public function tenant(): string
    {
        return 'controller';
    }

    public function tenantClassName(): string
    {
        return ControllerTenant::class;
    }
}
