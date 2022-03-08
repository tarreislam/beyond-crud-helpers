<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Doctrine\DBAL\Schema\Column;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class ModelTenant implements TenantClassBuilder
{

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;

        $tableName = $command->option('table');

        $columns = Helper::getColumnsFromTable($tableName);

        $columns->each(function (Column $column) use (&$class) {

            if (in_array($column->getName(), ['deleted_at'])) {
                $class->addTrait(SoftDeletes::class);
            }

            if (in_array($column->getName(), ['email', 'mail'])) {
                $class->addTrait(Notifiable::class);
            }

        });

        $class->addProperty('fillable', $columns->map(fn(Column $column) => $column->getName())->toArray());

        $class->addProperty('casts', $columns->map(function (Column $column) {
            $phpType = Helper::typeTranslator($column->getType());
            return $phpType;
        })->toArray());

        $class->addMethod('boot')->setStatic()->setProtected()->setBody('parent::boot();');

        return $class;
    }
}
