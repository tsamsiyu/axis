<?php namespace axis\specification\store;

interface StoreMapperInterface
{
    public function insert(StoreEntityInterface $entity);

    public function update(StoreEntityInterface $entity);

    public function delete(StoreEntityInterface $entity);
}