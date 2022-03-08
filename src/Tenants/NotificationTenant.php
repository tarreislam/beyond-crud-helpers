<?php

namespace Tarre\BeyondCrudHelpers\Tenants;

use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Nette\PhpGenerator\ClassType;
use Tarre\BeyondCrudHelpers\Helper;
use Tarre\BeyondCrudHelpers\Resources\Tenant;
use Tarre\BeyondCrudHelpers\Tenants\Concerns\TenantClassBuilder;

class NotificationTenant implements TenantClassBuilder
{

    public function __invoke(Tenant $tenant, Command $command): ClassType
    {
        $class = new ClassType;
        $class->addImplement(ShouldQueue::class);
        $method = $class->addMethod('__construct');
        $class->addMethod('via')->setBody("return ['mail'];")->setReturnType('array');
        $class->addTrait(Queueable::class);

        if ($tenant->getRelatedModel()) {
            Helper::injectParamToClassMethod($class, $method, $tenant);
        }

        $class
            ->addMethod('toMail')
            ->setReturnType(MailMessage::class)
            ->setBody('return (new \Illuminate\Notifications\Messages\MailMessage);')
            ->addParameter('notifiable');

        return $class;
    }
}
