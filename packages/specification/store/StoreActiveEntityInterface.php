<?php namespace axis\specification\store;

interface StoreActiveEntityInterface extends StoreEntityInterface
{
    public function save();
}