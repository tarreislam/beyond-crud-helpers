<?php

namespace Tarre\BeyondCrudHelpers\Resources;

use function config;

class Home
{
    protected string $dir;
    protected ?string $namespace;

    public function __construct(string $name)
    {
        $home = config('beyond-crud-helper.homes')[$name];
        $this->dir = $home['dir'];
        $this->namespace = $home['namespace'];
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @return ?string
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }


}
