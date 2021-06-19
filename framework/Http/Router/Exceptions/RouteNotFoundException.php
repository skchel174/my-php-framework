<?php

namespace Framework\Http\Router\Exceptions;

class RouteNotFoundException extends \Exception
{
    public function __construct(string $name)
    {
        $message = 'Route with name "' . $name . '" not found';
        parent::__construct($message);
    }
}
