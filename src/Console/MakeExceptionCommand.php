<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ExceptionTenant;

class MakeExceptionCommand extends BaseConsole
{
    public $signature = 'mmake:exception {name} {location?} {--force}';

    public function tenant(): string
    {
        return 'exception';
    }

    public function tenantClassName(): string
    {
        return ExceptionTenant::class;
    }
}
