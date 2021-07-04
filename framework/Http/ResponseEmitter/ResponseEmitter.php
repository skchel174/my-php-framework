<?php

namespace Framework\Http\ResponseEmitter;

use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseEmitter implements ResponseEmitterInterface
{
    public function emit(ResponseInterface $response): void
    {
        $this->emitHeaders($response);
        $this->emitBody($response);
    }

    protected function emitHeaders(ResponseInterface $response): void
    {
        header(sprintf('HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }

    protected function emitBody(ResponseInterface $response): void
    {
        echo $response->getBody();
    }
}
