<?php

namespace Tarre\BeyondCrudHelpers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Tarre\BeyondCrudHelpers\Console\MakeActionCommand;
use Tarre\BeyondCrudHelpers\Console\MakeControllerCommand;
use Tarre\BeyondCrudHelpers\Console\MakeDTOCommand;
use Tarre\BeyondCrudHelpers\Console\MakeEventCommand;
use Tarre\BeyondCrudHelpers\Console\MakeExceptionCommand;
use Tarre\BeyondCrudHelpers\Console\MakeJobCommand;
use Tarre\BeyondCrudHelpers\Console\MakeListenerCommand;
use Tarre\BeyondCrudHelpers\Console\MakeModelCommand;
use Tarre\BeyondCrudHelpers\Console\MakeNotificationCommand;
use Tarre\BeyondCrudHelpers\Console\MakeObserverCommand;
use Tarre\BeyondCrudHelpers\Console\MakePolicyCommand;
use Tarre\BeyondCrudHelpers\Console\MakeQueryCommand;
use Tarre\BeyondCrudHelpers\Console\MakeRequestCommand;
use Tarre\BeyondCrudHelpers\Console\MakeResourceCommand;
use Tarre\BeyondCrudHelpers\Console\MakeModelGetterMigrationCommand;
use Tarre\BeyondCrudHelpers\Console\MakeRuleCommand;

class ServiceProvider extends BaseServiceProvider
{
    protected $availableCommands = [
        MakeActionCommand::class,
        MakeControllerCommand::class,
        MakeDTOCommand::class,
        MakeEventCommand::class,
        MakeExceptionCommand::class,
        MakeJobCommand::class,
        MakeListenerCommand::class,
        MakeModelCommand::class,
        MakeNotificationCommand::class,
        MakeObserverCommand::class,
        MakePolicyCommand::class,
        MakeQueryCommand::class,
        MakeRequestCommand::class,
        MakeResourceCommand::class,
        MakeRuleCommand::class,
        //ka
        MakeModelGetterMigrationCommand::class
    ];

    public function register()
    {
        $this->publishConfig();

        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->commands($this->availableCommands);
    }

    protected function publishConfig()
    {
        $file = 'beyond-crud-helper.php';

        $this->publishes([
            __DIR__ . '/config/' . $file => config_path($file)
        ]);
    }

}
