<?php namespace axis\specification\di;

interface DependencyInjectorInterface
{
    /**
     * @param string|object|callable $contract
     * @param string|object|null|callable $agent
     * @return DependencyDefinitionInterface
     */
    public function set($contract, $agent = null);

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function get(string $contract, $beforeInstantiate = null);

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function getSingleton(string $contract, $beforeInstantiate = null);

    /**
     * @param string $contract
     * @return bool
     */
    public function has(string $contract);

    /**
     * @param string|DependencyDefinitionInterface $agent
     * @param null $beforeInstantiate
     * @return object
     */
    public function resolve($agent, $beforeInstantiate = null);
}