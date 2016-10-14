<?php namespace axis\specification\di;

interface DI
{
    /**
     * @param string $contract
     * @param string $agent
     * @return DependencyDefinition
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
     * @param string|DependencyDefinition $agent
     * @return mixed
     */
    public function resolve($agent);
}