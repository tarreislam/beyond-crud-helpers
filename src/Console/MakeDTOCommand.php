<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\DTOCollectionTenant;
use Tarre\BeyondCrudHelpers\Tenants\DTOTenant;

class MakeDTOCommand extends BaseConsole
{
    public $signature = 'mmake:dto {name} {location?} {--table=} {--collection} {--force}';

    public function tenant(): string
    {
        if ($this->option('collection')) {
            return 'dtoCollection';
        }
        return 'dto';
    }

    public function tenantClassName(): string
    {
        if ($this->option('collection')) {
            return DTOCollectionTenant::class;
        }
        return DTOTenant::class;
    }
}
