<?php namespace axis\specification\db;

interface DataMapperInterface
{
    public function insert(EntityInterface $entity);

    public function update(EntityInterface $entity);

    public function delete(EntityInterface $entity);
}