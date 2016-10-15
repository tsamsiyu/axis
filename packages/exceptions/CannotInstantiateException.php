<?php namespace axis\exceptions;

use Exception;

class CannotInstantiateException extends Exception
{
    public function __construct($className, $causeMessage = '', $code = 0, Exception $previous = null)
    {
        if (!$causeMessage) {
            $msg = "Cannot instantiate `$className`";
        } else {
            $msg = "Cannot instantiate `$className`. Cause: $causeMessage";
        }
        parent::__construct($msg, $code, $previous);
    }
}