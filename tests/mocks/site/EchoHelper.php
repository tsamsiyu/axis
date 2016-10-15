<?php namespace axis\tests\mocks\site;

class EchoHelper
{
    public $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}