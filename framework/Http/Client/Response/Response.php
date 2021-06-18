<?php

namespace Framework\Http\Client\Response;

use Framework\Http\Client\Message\HttpMessage;
use Framework\Http\Client\Response\Exceptions\InvalidCodeException;
use Framework\Http\Client\Response\Exceptions\InvalidPhraseTypeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends HttpMessage implements ResponseInterface
{
    const REASON_PHRASES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];
    protected ?StreamInterface $body;
    protected int $statusCode;
    protected string $reasonPhrase;

    public function __construct($body = null, int $statusCode = 200, string $reasonPhrase = '')
    {
        $this->body = $this->normalizeBody($body);
        $this->statusCode = $this->filterStatusCode($statusCode);
        $this->reasonPhrase = $this->normalizeReasonPhrase($statusCode, $reasonPhrase);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): static
    {
        $clone = clone $this;
        $clone->statusCode = $this->filterStatusCode($code);
        $clone->reasonPhrase = $this->normalizeReasonPhrase($code, $reasonPhrase);
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    protected function filterStatusCode($code): int
    {
        if (!array_key_exists($code, static::REASON_PHRASES)) {
            throw new InvalidCodeException($code);
        }
        return $code;
    }

    protected function normalizeReasonPhrase($code, $reasonPhrase = ''): string
    {
        if (!is_string($reasonPhrase)) {
            throw new InvalidPhraseTypeException($reasonPhrase);
        }
        return $reasonPhrase ?: static::REASON_PHRASES[$code];
    }
}
