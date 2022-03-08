<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nette\PhpGenerator\ClassType;
use ReflectionClass;
use Str;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class ModelGetterMigrationTenant implements TenantClassBuilder
{

    protected Command $command;
    protected Model $model;
    protected Tenant $tenant;

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $this->tenant = $tenant;
        $this->command = $command;
        $this->model = $this->initModel();
        $tableName = $this->getTableName();

        $class = new ClassType($this->getMigrationName());

        $tenant->setResourceName($this->getResourceName());
        $tenant->addUseClass(Blueprint::class);
        $tenant->addUseClass(Schema::class);

        $this->buildUpMethod($class, $tableName);
        $this->buildDownMethod($class, $tableName);


        return $class;
    }

    /**
     *
     * This is really hacky, it will just try to extract source code so u can manually place them in your desired place
     * @return array
     */
    public function additionalFile(): array
    {
        $reflectionClass = $this->reflectionCLass();
        $gettersAsRealMethod = collect($this->getGetters())
            ->map(function (string $getter) {
                return 'get' . Str::studly($getter) . 'Attribute';
            })->toArray();
        $classBasename = Str::singular(class_basename($this->resolvedClassName()));
        $suggestedVariableName = strtolower($classBasename);

        $source = collect($reflectionClass->getMethods())
            ->filter(function (\ReflectionMethod $method) use ($gettersAsRealMethod) {
                return in_array($method->getName(), $gettersAsRealMethod);
            })->map(function (\ReflectionMethod $method) use ($suggestedVariableName, $reflectionClass, $classBasename) {
                $snakedGetter = Str::snake(preg_replace('/get([A-Z].*)Attribute/', '$1', $method->getName()));
                /*
                 * Extract source code
                 */
                $startLine = $method->getStartLine() + 1;
                $endLine = $method->getEndLine() - 1;
                $filePath = $method->getFileName();
                $content = file_get_contents($filePath);
                $contentParts = explode("\n", $content);
                $newContent = '';
                for ($i = $startLine; $i < $endLine; $i++) {
                    $newContent .= $contentParts[$i] . "\n";
                }
                /*
                 * Swap some things here and there
                 */
                $newContent = str_replace('$this', "\$$suggestedVariableName", $newContent);
                $newContent = str_replace('self::', "$classBasename::", $newContent);
                // $newContent = preg_replace("/return\h*(.*);/s", "\$$suggestedVariableName->$snakedGetter = $1;", $newContent);

                $className = preg_replace('/get([A-Z].*)Attribute/', 'get$1Action', $method->getName());
                $className = Str::studly($className);
                $class = new ClassType($className);

                $class
                    ->addMethod('__invoke')
                    ->setBody($newContent)
                    ->addParameter($suggestedVariableName)
                    ->setType($this->resolvedClassName());

                return $class;
            });


        return $source->toArray();
    }

    protected function buildUpMethod(ClassType $class, string $tableName)
    {
        $up = $class->addMethod('up');
        $up->addBody("Schema::table('$tableName', function (Blueprint \$table) {");
        foreach ($this->getGetters() as $getter) {
            $up->addBody("    \$table->string('$getter');");
        }
        $up->addBody('});');
    }

    protected function buildDownMethod(ClassType $class, string $tableName)
    {
        $down = $class->addMethod('down');
        $down->addBody("Schema::table('$tableName', function (Blueprint \$table) {");
        foreach ($this->getGetters() as $getter) {
            $down->addBody("    \$table->dropColumn('$getter');");
        }
        $down->addBody('});');
    }

    protected function getResourceName(): string
    {
        return now()->format('Y_m_d_His') . '_' . Str::snake($this->getMigrationName());
    }

    protected function getMigrationName(): string
    {
        $tableName = $this->getTableName();
        $date = now()->format('Ymd');
        return Str::studly("patch_{$tableName}_table_$date");
    }

    protected function getTableName(): string
    {
        return $this->model->getTable();
    }

    protected function getGetters(): array
    {
        return $this->model->getMutatedAttributes();
    }

    /*
    protected function getGetters(ReflectionClass $class): Collection
    {
        $modelTenantHome = (new Tenant('model'))->getHome();
        $methods = $class->getMethods();

        $allowedFiles = [
            $class->getFileName(),
        ];

        if ($modelTenantHome->getNamespace() !== Model::class) {
            $allowedFiles[] = $class->getParentClass()->getFileName();
        }

        return collect($methods)
            ->filter(function (ReflectionMethod $method) use ($allowedFiles) {
                return $method->isPublic() && in_array($method->getFileName(), $allowedFiles);
            })
            ->map(function (ReflectionMethod $method) {
                if (preg_match('/get([A-Z].*)Attribute/', $method->getName(), $match)) {
                    return Str::snake($match[1]);
                }
                return null;
            })->reject(null);
    }
*/

    protected function reflectionCLass(): ReflectionClass
    {
        return new ReflectionClass($this->resolvedClassName());
    }

    protected function initModel(): Model
    {
        $className = $this->resolvedClassName();
        return new $className;
    }

    protected function resolvedClassName()
    {
        return $this->tenant->getRelatedModelClassName();
    }
}
