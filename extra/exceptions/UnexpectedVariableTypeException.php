<?php namespace axis\exceptions;

use Exception;

class UnexpectedVariableTypeException extends Exception
{
    public function __construct($variable, $code = 0, Exception $previous = null)
    {
        $type = gettype($variable);
        parent::__construct("Unexpected variable type: `{$type}`", $code, $previous);
    }
}