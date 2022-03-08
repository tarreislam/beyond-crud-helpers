<?php

namespace Tarre\BeyondCrudHelpers;

use DB;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Property;
use Tarre\BeyondCrudHelpers\Resources\Tenant;

class Helper
{

    public static function injectParamToClassMethod(ClassType $class, Method $method, Tenant $tenant)
    {
        $paramName = $tenant->getSuggestedVariableName();
        $modelName = $tenant->getRelatedModelClassName();
        $method->setBody("\$this->$paramName = \$$paramName;");
        $method->addParameter($paramName)->setType($modelName);
        $class->addProperty($paramName)->setType($modelName);
    }

    /**
     * @param Type $type
     * @return string
     */
    public static function typeTranslator(Type $type)
    {
        switch ($type->getName()) {
            case 'boolean':
                return 'bool';
            case 'date':
            case 'datetime':
            case 'date_immutable':
            case 'datetime_immutable':
            case 'datetimetz':
            case 'datetimetz_immutable':
            case 'time':
            case 'time_immutable':
            case 'dateinterval':
                return 'string';
            case 'decimal':
            case 'float':
                return 'float';
            case 'smallint':
            case 'bigint':
            case 'integer':
                return 'int';
            case 'ascii_string':
            case 'text':
            case 'guid':
            case 'binary':
            case 'blob':
                return 'string';
            case 'array':
            case 'json':
            case 'simple_array':
            case 'json_array':
                return 'array';
        }
        return $type->getName();
    }

    public static function getColumnsFromTable($tableName): Collection
    {
        $DB = DB::connection(config('database.default'));
        $SchemaManager = $DB->getDoctrineSchemaManager();
        /*
         * Polyfill mysql
         */
        $dbPlatform = $SchemaManager->getDatabasePlatform();
        $dbPlatform->registerDoctrineTypeMapping('enum', 'string');
        $dbPlatform->registerDoctrineTypeMapping('timestamp', 'datetime');

        return collect($SchemaManager->listTableColumns($tableName));
    }

    public static function getPropertiesFromTable($tableName): array
    {
        return self::getColumnsFromTable($tableName)
            ->map(function (Column $column) {

                $property = new Property($column->getName());
                $property->setType($realType = self::typeTranslator($column->getType()));
                $isNull = !$column->getNotnull();
                // $isNullAsString = $isNull ? '?' : '';
                // $property->addComment('@var ' . $isNullAsString . $realType);
                if (!is_null($comment = $column->getComment())) {
                    $property->addComment($comment);
                }
                $value = $column->getDefault();

                /*
                 * Convert bools int and floats
                 */
                switch ($realType) {
                    case 'float':
                        $value = (float)$value;
                        break;
                    case 'bool':
                        $value = (bool)$value;
                        break;
                    case 'int':
                        $value = (int)$value;
                        break;
                    case 'array': // Arrays arent really a thing in an mysql database, its just json. so its default value may be an empty array
                        $value = [];
                        break;
                }

                /*
                 * If nullable or if autoincrement just null it
                 */
                if ($isNull || $column->getAutoincrement()) {
                    $property->setNullable(true);
                } else {
                    /*
                     * Never set the auto increment value or if the value is null, because then it should be filed under "isnull"
                     */
                    if (!is_null($value)) {
                        $property->setValue($value);
                    }
                }

                return $property;
            })
            ->sortByDesc(function (Property $property) {
                if ($property->isNullable()) {
                    return 0;
                }
                $value = $property->getValue();
                if (is_string($value)) {
                    return 1;
                }
                if (is_numeric($value)) {
                    return 2;
                }
                if (is_bool($value)) {
                    return 3;
                }
                return 4;
            })
            ->values()
            ->toArray();
    }
}
