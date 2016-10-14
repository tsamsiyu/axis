<?php namespace axis\specification\di;

interface DependencyDefinition
{
    const SCOPE_SINGLETON = 'singleton';
    const SCOPE_PROTOTYPE = 'prototype';

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