<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\RuleTenant;

class MakeRuleCommand extends BaseConsole
{
    protected $signature = "mmake:rule {name} {location?} {--force}";

    public function tenant(): string
    {
        return 'rule';
    }

    public function tenantClassName(): string
    {
        return RuleTenant::class;
    }
}
