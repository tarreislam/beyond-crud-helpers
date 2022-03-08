<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Illuminate\Console\Command;
use Tarre\BeyondCrudHelpers\FilerBuilder;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;
use Tarre\BeyondCrudHelpers\Resources\Tenant;

abstract class BaseConsole extends Command
{

    public abstract function tenant(): string;

    public abstract function tenantClassName(): string;

    public function handle()
    {
        $tenant = new Tenant($this->tenant());

        $resourceName = $this->argument('name');
        $location = $this->ask('Location(' . $tenant->getHome()->getDir() . '/' . $tenant->getRelativePath() . ')?', $this->argument('location'));

        $tenant
            ->setLocation($location)
            ->setResourceName($resourceName);

        if ($this->hasOption('model')) {
            $tenant->setRelatedModel($this->option('model'));
        }

        if (file_exists($finalFilename = $tenant->getFilename()) && !$this->option('force', false)) {
            $this->warn("File \"$finalFilename\" already exists");
            return 1;
        }

        $class = $this->getTenantClassBuilder()($tenant, $this);

        $this->buildClass($tenant, $class);

        $this->info("File \"$finalFilename\" created successfully!");
    }


    protected function buildClass(Tenant $tenant, $class)
    {
        $builder = new FilerBuilder($tenant);
        $builder->build($class);
    }

    protected function getTenantClassBuilder(): TenantClassBuilder
    {
        $tenantClass = $this->tenantClassName();
        return new $tenantClass;
    }
}
