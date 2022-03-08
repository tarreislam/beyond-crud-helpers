<?php

namespace Tarre\BeyondCrudHelpers\Resources;

use Illuminate\Support\Str;
use Tarre\BeyondCrudHelpers\Resources\Home;
use function config;

class Tenant
{
    protected Home $home;
    protected string $relativePath;
    protected string $prefix;
    protected string $suffix;
    protected string $tenant;
    protected ?string $extends = null;
    protected ?string $resourceName = null;
    protected ?string $location = null;
    protected ?string $relatedModel = null;
    protected array $useClasses = [];

    public function __construct(string $tenant)
    {
        $this->tenant = $tenant;
        $tenant = config('beyond-crud-helper.tenants')[$tenant];
        $this->home = new Home($tenant['home']);
        $this->relativePath = $tenant['relativePath'];
        $this->prefix = $tenant['prefix'];
        $this->suffix = $tenant['suffix'];
        $this->extends = $tenant['extends'];
    }

    /**
     * @return Home
     */
    public function getHome(): Home
    {
        return $this->home;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @return string|null
     */
    public function getExtends(): ?string
    {
        return $this->extends;
    }

    /**
     * @param string|null $resourceName
     * @return Tenant
     */
    public function setResourceName(?string $resourceName): Tenant
    {
        $this->resourceName = $resourceName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRelatedModel(): ?string
    {
        return $this->relatedModel;
    }

    /**
     * @return string|null
     */
    public function getRelatedModelClassName()
    {
        if (!$relatedModel = $this->getRelatedModel()) {
            return null;
        }
        if (Str::startsWith($relatedModel, '\\') || Str::startsWith($relatedModel, '/')) {
            $relatedModel = str_replace('/', '\\', $relatedModel);
            return $relatedModel;
        }
        $modelTenant = (new Tenant('model'))->setLocation($relatedModel);

        $base = $modelTenant->getNamespace();

        return "\\$base\\$relatedModel";
    }

    /**
     * @return string
     */
    public function getSuggestedVariableName(): string
    {
        if ($this->relatedModel) {
            $base = $this->getRelatedModelClassName();
        } else {
            $base = $this->location;
        }
        $base = class_basename($base);
        $base = Str::singular($base);
        return Str::camel($base);
    }

    /**
     * @return string
     */
    public function getTenant(): string
    {
        return $this->tenant;
    }

    /**
     * @param string|null $location
     * @return Tenant
     */
    public function setLocation(?string $location): Tenant
    {
        $this->location = $location;
        return $this;
    }


    /**
     * @param string|null $relatedModel
     * @return Tenant
     */
    public function setRelatedModel(?string $relatedModel): Tenant
    {
        $this->relatedModel = $relatedModel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getFilename(): string
    {
        $base = $this->getLocationWithRelativePath($this->getHome()->getDir(), "/");
        $name = $this->getClassName();
        return "$base/$name.php";
    }

    public function getNamespace(): string
    {
        $base = $this->getLocationWithRelativePath($this->getHome()->getNamespace(), '\\');
        return "$base";
    }

    public function getClassName(): string
    {
        $name = $this->resourceName;
        if (!Str::startsWith($name, $prefix = $this->getPrefix())) {
            $name = $prefix . $name;
        }
        if (!Str::endsWith($name, $suffix = $this->getSuffix())) {
            $name = $name . $suffix;
        }
        return $name;
    }

    /**
     * @param $class
     * @return $this
     */
    public function addUseClass($class)
    {
        $this->useClasses[] = $class;
        return $this;
    }

    /**
     * @return array
     */
    public function getUsesClasses()
    {
        return $this->useClasses;
    }

    protected function getLocationWithRelativePath(string $base, string $separator): string
    {
        if (!empty($relativePath = $this->getRelativePath())) {
            $base .= $separator . "$relativePath";
        }
        $parsed =  str_replace('{name}', $this->location, $base);
        // adjust for separator
        if($separator == '/'){
            $parsed = str_replace('\\', '/', $parsed);
        }else{
            $parsed = str_replace('/', '\\', $parsed);
        }
        return $parsed;
    }

}
