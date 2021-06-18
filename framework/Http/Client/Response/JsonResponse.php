<?php

namespace Framework\Http\Client\Response;

use Framework\Http\Client\Stream\StreamFactory;
use Psr\Http\Message\StreamInterface;

class JsonResponse extends Response
{
    public function __construct(mixed $body, int $statusCode = 200, string $reasonPhrase = '')
    {
        if ($body == null || $body instanceof StreamInterface) {
            $body = (string)$body;
        }

        if (is_resource($body)) {
            $body = (new StreamFactory)->createStreamFromResource($body);
        }

        parent::__construct(json_encode($body, JSON_UNESCAPED_UNICODE), $statusCode, $reasonPhrase);
        $this->headers['Content-Type'] = ['application/json; charset=utf-8'];
    }
}
