<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ModelTenant;

class MakeModelCommand extends BaseConsole
{
    public $signature = 'mmake:model {name} {location?} {--table=} {--force}';

    public function tenant(): string
    {
        return 'model';
    }

    public function tenantClassName(): string
    {
        return ModelTenant::class;
    }
}
