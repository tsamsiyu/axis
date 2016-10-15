<?php namespace axis\specification\IoC;

interface ServiceLocatorInterface
{
    public function get(string $name);

    public function set(string $name, $contract, array $configuration = []);

    public function make(string $name, array $configuration = []);

    public function has(string $name);

    public function configure(string $name, array $configuration);
}