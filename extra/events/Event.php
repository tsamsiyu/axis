<?php namespace axis\events;

class Event implements \axis\specification\events\EventInterface
{
    protected $data;
    protected $name;
    protected $context;

    public function __construct(string $name, $data = null, $context = null)
    {
        $this->setName($name);
        $this->setData($data);
        $this->setContext($context);
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }
}