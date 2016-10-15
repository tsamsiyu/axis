<?php namespace axis\specification\events;

interface EventInterface
{
    public function getName();

    public function getData();

    public function getContext();
}