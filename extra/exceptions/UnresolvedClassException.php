<?php namespace axis\exceptions;

use Exception;

class UnresolvedClassException extends Exception
{
    public function __construct($className = '', $code = 0, Exception $previous = null)
    {
        if ($className) {
            $msg = "Cannot resolve unknown class `$className`";
        } else {
            $msg = "Cannot resolve class";
        }
        parent::__construct($msg, $code, $previous);
    }
}