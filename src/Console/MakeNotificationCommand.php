<?php

namespace Tarre\BeyondCrudHelpers\Console;

use Tarre\BeyondCrudHelpers\Tenants\NotificationTenant;

class MakeNotificationCommand extends BaseConsole
{
    public $signature = 'mmake:notification {name} {location?} {--model=} {--force}';

    public function tenant(): string
    {
        return 'notification';
    }

    public function tenantClassName(): string
    {
        return NotificationTenant::class;
    }
}
