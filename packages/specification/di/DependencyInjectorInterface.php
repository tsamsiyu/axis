<?php namespace axis\specification\di;

interface DependencyInjectorInterface
{
    /**
     * @param string $contract
     * @param string $agent
     * @return DependencyDefinitionInterface
     */
    public function set(string $contract, string $agent);

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function get(string $contract, callable $beforeInstantiate = null);

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function getSingleton(string $contract, callable $beforeInstantiate = null);

    /**
     * @param string $contract
     * @return mixed
     */
    public function has(string $contract);

    /**
     * @param string|DependencyDefinitionInterface $agent
     * @return mixed
     */
    public function resolve($agent);
}