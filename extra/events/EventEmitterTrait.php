<?php namespace axis\events;

use axis\exceptions\UnexpectedVariableTypeException;
use axis\specification\events\EventInterface as EventInterface;

trait EventEmitterTrait
{
    protected $eventListeners = [];

    /**
     * @param string|EventInterface $event
     * @return $this
     * @throws UnexpectedVariableTypeException
     */
    public function emitEvent($event)
    {
        if (is_string($event)) {
            $event = new Event($event);
        } else if (!$event instanceof EventInterface) {
            throw new UnexpectedVariableTypeException($event);
        }

        if (isset($this->eventListeners[$event->getName()])) {
            foreach ($this->eventListeners[$event->getName()] as $callback) {
                call_user_func($callback, $event);
            }
        }
        return $this;
    }

    /**
     * @param string $eventName
     * @param \Closure|array $callback
     * @return $this
     */
    public function onEvent(string $eventName, $callback)
    {
        if (!isset($this->eventListeners[$eventName])) {
            $this->eventListeners[$eventName] = [];
        }
        $this->eventListeners[$eventName][] = $callback;
        return $this;
    }
}