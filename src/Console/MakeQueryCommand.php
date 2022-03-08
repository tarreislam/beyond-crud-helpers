<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\QueryTenant;

class MakeQueryCommand extends BaseConsole
{
    public $signature = 'mmake:query {name} {location?} {--model=} {--force}';

    public function tenant(): string
    {
        return 'query';
    }

    public function tenantClassName(): string
    {
        return QueryTenant::class;
    }
}
