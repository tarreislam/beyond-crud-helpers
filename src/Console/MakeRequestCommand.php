<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\RequestTenant;

class MakeRequestCommand extends BaseConsole
{
    public $signature = 'mmake:request {name} {location?} {--force}';

    public function tenant(): string
    {
        return 'request';
    }

    public function tenantClassName(): string
    {
        return RequestTenant::class;
    }
}
