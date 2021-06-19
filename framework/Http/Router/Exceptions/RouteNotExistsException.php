<?php

namespace Framework\Http\Router\Exceptions;

class RouteNotExistsException extends \Exception
{
    public function __construct(string $path)
    {
        $message = 'Route with path "' . $path . '" not exist';
        parent::__construct($message, 404);
    }
}
