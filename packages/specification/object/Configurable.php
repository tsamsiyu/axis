<?php namespace axis\specification\object;

interface Configurable
{
    public function configureInstance(array $configuration);
}