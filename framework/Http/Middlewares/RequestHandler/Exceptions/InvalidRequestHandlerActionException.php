<?php

namespace Framework\Http\Middlewares\RequestHandler\Exceptions;

use Throwable;

class InvalidRequestHandlerActionException extends \BadMethodCallException
{
    public function __construct(string|object $controller, string $action)
    {
        $controller = is_string($controller) ? $controller : $controller::class;
        $message = 'Method "' . $action . '" not exist in controller "' . $controller . '"';
        parent::__construct($message, 404);
    }
}
