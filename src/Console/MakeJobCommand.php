<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\JobTenant;

class MakeJobCommand extends BaseConsole
{
    public $signature = 'mmake:job {name} {location?} {--model=} {--force}';

    public function tenant(): string
    {
        return 'job';
    }

    public function tenantClassName(): string
    {
        return JobTenant::class;
    }
}
