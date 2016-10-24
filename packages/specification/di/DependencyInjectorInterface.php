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
    public function get(string $contract, callable $beforeInstantiate = null);

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function getSingleton(string $contract, callable $beforeInstantiate = null);

    /**
     * @param string $contract
     * @return bool
     */
    public function has(string $contract) : bool;

    /**
     * @param string|DependencyDefinitionInterface $agent
     * @param callable|null $beforeInstantiate
     * @return object
     */
    public function resolve($agent, callable $beforeInstantiate = null);

    /**
     * @param string $scope
     * @param callable $scopeClosure
     * @return void
     */
    public function scope(string $scope, callable $scopeClosure);
}