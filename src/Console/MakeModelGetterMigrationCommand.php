<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Illuminate\Console\Command;
use Tarre\BeyondCrudHelpers\FilerBuilder;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\ModelGetterMigrationTenant;

class MakeModelGetterMigrationCommand extends Command
{
    public $signature = 'mmake:model-getter-migration {model} {location}';

    private ModelGetterMigrationTenant $modelGetterMigrationTenant;

    public function __construct(ModelGetterMigrationTenant $modelGetterMigrationTenant)
    {
        parent::__construct();
        $this->modelGetterMigrationTenant = $modelGetterMigrationTenant;
    }

    public function handle()
    {
        $tenant = new Tenant('migration');
        $resourceName = $this->argument('model');
        $location = $this->argument('location');

        $tenant
            ->setLocation($location)
            ->setResourceName($resourceName);

        $tenant->setRelatedModel($resourceName);

        $tenantClass = $this->modelGetterMigrationTenant;
        $class = $tenantClass($tenant, $this);
        $this->buildClass($tenant, $class);

        foreach ($tenantClass->additionalFile() as $item) {
            $actionTenant = new Tenant('action');
            $actionTenant
                ->setLocation($location)
                ->setResourceName($item->getName());
            $this->buildClass($actionTenant, $item);
        }
    }

    protected function buildClass(Tenant $tenant, $class)
    {
        $builder = new FilerBuilder($tenant);
        $builder->build($class);
    }
}
