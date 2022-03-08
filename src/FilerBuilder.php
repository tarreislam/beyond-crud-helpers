<?php

namespace Tarre\BeyondCrudHelpers;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Tarre\BeyondCrudHelpers\Resources\Tenant;

class FilerBuilder
{
    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function build(ClassType $class)
    {
        $filename = $this->tenant->getFilename();
        $fileContent = $this->getFileContent($class);
        /*
         * Create file
         */
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0700, true);
        }
        $fh = fopen($filename, 'w+');
        fwrite($fh, $fileContent);
        fclose($fh);
    }

    protected function getFileContent(ClassType $class): string
    {
        return '<?php' . PHP_EOL . PHP_EOL . $this->finalizedNamespace($class);
    }

    protected function finalizedNamespace(ClassType $class): PhpNamespace
    {
        /*
         * Create namespace
         */
        $namespace = new PhpNamespace($this->tenant->getNamespace());
        /*
         * Add uses
         */
        foreach ($this->tenant->getUsesClasses() as $usesClass) {
            $namespace->addUse($usesClass);
        }
        /*
         * Finalize classes
         */
        if (!$class->getName()) {
            $class->setName($this->tenant->getClassName());
        }
        if ($extends = $this->tenant->getExtends()) {
            $class->setExtends($extends);
            $namespace->addUse($extends);
        }
        /*
         * Add class
         */
        $namespace->add($class);
        /*
         * Last runthru to use instead of fqdn
         */
        foreach ($namespace->getClasses() as $nsClass) {
            foreach ($nsClass->getImplements() as $nsImplement) {
                $namespace->addUse($nsImplement);
            }
            foreach ($nsClass->getTraits() as $nsTrait) {
                $namespace->addUse($nsTrait);
            }
            foreach ($nsClass->getProperties() as $nsProperty) {
                $nsPropertyType = $nsProperty->getType();
                if (class_exists($nsPropertyType)) {
                    $namespace->addUse($nsPropertyType);
                }
            }
            foreach ($nsClass->getMethods() as $nsMethod) {
                $nsMethodReturnType = $nsMethod->getReturnType();
                if (class_exists($nsMethodReturnType)) {
                    $namespace->addUse($nsMethodReturnType);
                }
                foreach ($nsMethod->getParameters() as $nsParameter) {
                    $nsMethodParameterType = $nsParameter->getType();
                    if (class_exists($nsMethodParameterType)) {
                        $namespace->addUse($nsMethodParameterType);
                    }
                }
            }
        }
        /*
         * Return instance
         */
        return $namespace;
    }

}
