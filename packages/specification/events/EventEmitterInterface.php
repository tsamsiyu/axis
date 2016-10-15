<?php namespace axis\specification\events;

interface EventEmitterInterface
{
    /**
     * @param string $eventName
     * @param \Closure|array
     * @return mixed
     */
    public function onEvent(string $eventName, $callback);

    /**
     * @param EventInterface|string $data Name of event if it's string or Event object itself
     * @return $this
     */
    public function emitEvent($data);
}