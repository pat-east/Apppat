<?php

include_once('Event.php');
include_once('EventHandler.php');

class EventBus {

    /** @var array<string, EventHandler[]> */
    private static array $EventHandler = [];

    /** @var Event[] */
    private static array $RegisteredEvents = [];

    public static function On(EventHandler $eventHandler) : void {
        if(!isset(self::$EventHandler[$eventHandler->hookName])) {
            self::$EventHandler[$eventHandler->hookName] = [];
        }
        self::$EventHandler[$eventHandler->hookName][] = $eventHandler;
        usort(self::$EventHandler[$eventHandler->hookName], function ($a, $b) {
            return $b->priority <=> $a->priority;
        });
    }

    public static function Raise(Event $event): void {
        Logger::Debug(__FILE__,
            'Event raised [sender=%s; hookName=%s]',
            $event->getSenderString(),
            $event->hookName);

        $event = self::InitEventArgs($event);

        if(isset(self::$EventHandler[$event->hookName])) {
            foreach(self::$EventHandler[$event->hookName] as $eventHandler) {
                $handler = $eventHandler->handler;
                $handler($event->args);
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function RegisterEvent(Event $event): void {
//        Logger::Debug(__FILE__,
//            'Event registered [sender=%s; hookName=%s]',
//            $event->getSenderString(),
//            $event->hookName);
        if(isset(self::$RegisteredEvents[$event->hookName])) {
            throw new Exception("Event already registered [hookName={$event->hookName}].]");
        }
        self::$RegisteredEvents[$event->hookName] = $event;
    }

    private static function InitEventArgs(Event $event): Event {
        if(!isset(self::$RegisteredEvents[$event->hookName])) {
            throw new Exception("Event not registered [hookName={$event->hookName}].]");
        }
        $registeredEvent = self::$RegisteredEvents[$event->hookName];
//        Logger::Object(__FILE__, $registeredEvent->args);
        foreach($registeredEvent->args as $param => $arg) {
            if(!isset($event->args[$param])) {
                throw new Exception("Arguments of raised event does not match registered event [sender={$event->getSenderString()}; hookName={$event->hookName}].]");
            }
        }
//        Logger::Object(__FILE__, $event->args);
        return $event;
    }

    private function __construct() { }
}