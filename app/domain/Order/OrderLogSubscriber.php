<?php
declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Order\Event\OrderCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Tracy\Debugger;

class OrderLogSubscriber implements EventSubscriberInterface
{

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreated::class => 'log'
        ];
    }

    public function log(Event $event): void
    {
        Debugger::log($event, 'info');
        Debugger::barDump($event);
    }

}