<?php namespace axis\specification\di;

use axis\specification\events\EventEmitterInterface;

interface DependencyDefinitionInterface extends EventEmitterInterface
{
    const SCOPE_SINGLETON = 'scopeSingleton';
    const SCOPE_PROTOTYPE = 'scopePrototype';

    const EVENT_BEFORE_CREATE = 'eventBeforeCreate';
    const EVENT_AFTER_CREATE = 'eventAfterCreate';

    /**
     * @return string
     */
    public function getAgent();

    /**
     * @return bool
     */
    public function isSingleton();

    /**
     * @return bool
     */
    public function isPrototype();

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return bool
     */
    public function isArgumentsMap();
}