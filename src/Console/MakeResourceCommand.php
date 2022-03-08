<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\ResourceCollectionTenant;
use Tarre\BeyondCrudHelpers\Tenants\ResourceTenant;

class MakeResourceCommand extends BaseConsole
{
    public $signature = 'mmake:resource {name} {location?} {--collection} {--force}';

    public function tenant(): string
    {
        if ($this->option('collection')) {
            return 'resourceCollection';
        }
        return 'resource';
    }

    public function tenantClassName(): string
    {
        if ($this->option('collection')) {
            return ResourceCollectionTenant::class;
        }
        return ResourceTenant::class;
    }
}
