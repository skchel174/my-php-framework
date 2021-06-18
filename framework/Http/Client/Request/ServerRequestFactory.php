<?php

namespace Framework\Http\Client\Request;

use Framework\Http\Client\Request\Exceptions\InvalidUriTypeException;
use Framework\Http\Client\Stream\StreamFactory;
use Framework\Http\Client\UploadedFile\UploadedFileFactory;
use Framework\Http\Client\Uri\UriFactory;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (!is_string($uri) && (is_object($uri) && !$uri instanceof UriInterface)) {
            throw new InvalidUriTypeException($uri);
        }
        $uri = is_string($uri) ? (new UriFactory)->createUri($uri) : $uri;;
        return new ServerRequest($method, $uri, $serverParams);
    }

    public function createFromSapi(
        array $server = [],
        array $cookie = [],
        array $get = [],
        array $post = [],
        array $files = [],
        string|StreamInterface $body = 'php://input',
    ): ServerRequestInterface
    {
        $server = $server ?: $_SERVER;
        $cookie = $cookie ?: $_COOKIE;
        $query = $get ?: $_GET;
        $post = $post ?: $_POST;
        $files = $files ?: $_FILES;

        $method = $server['REQUEST_METHOD'] ?? 'GET';
        $uri = (new UriFactory)->createUriFromSapi($server);
        $protocol = $this->normalizeProtocol($server);
        $headers = $this->normalizeHeaders($server);
        $files = $this->normalizeUploadedFiles($files);
        $body = is_string($body) ? (new StreamFactory)->createStreamFromFile($body) : $body;
        $parsedBody = $this->normalizeParsedBody($headers, $body, $post);

        return new ServerRequest($method, $uri, $server, $protocol, $headers, $body, $parsedBody, $cookie, $query, $files);
    }

    private function normalizeHeaders(array $serverParams): array
    {
        $headers = [];
        foreach ($serverParams as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $prepName = str_replace('_', ' ', substr($key, 5));
                $name = str_replace(' ', '-', ucwords(strtolower($prepName)));
                $headers[$name] = [$value];
            }
        }
        return $headers;
    }

    protected function normalizeProtocol(array $serverParams): string
    {
        if (isset($serverParams['SERVER_PROTOCOL']) && str_contains($serverParams['SERVER_PROTOCOL'], '/')) {
            return explode('/', $serverParams['SERVER_PROTOCOL'])[1];
        }
        return '1.1';
    }

    protected function normalizeUploadedFiles(array $uploadedFiles): array
    {
        if (!$uploadedFiles) {
            return [];
        }

        $files = [];
        $uploaded = array_values($uploadedFiles)[0];

        if (is_array($uploaded['name'])) {
            for ($i = 0; $i < count($uploaded['name']); $i++) {
                $files[] = (new UploadedFileFactory)->createUploadedFile(
                    (new StreamFactory)->createStreamFromFile($uploaded['tmp_name'][$i]),
                    $uploaded['error'][$i],
                    $uploaded['size'][$i],
                    $uploaded['name'][$i],
                    $uploaded['type'][$i],
                );
            }
        } else {
            $files[] = (new UploadedFileFactory)->createUploadedFile(
                (new StreamFactory)->createStreamFromFile($uploaded['tmp_name']),
                $uploaded['error'],
                $uploaded['size'],
                $uploaded['name'],
                $uploaded['type'],
            );
        }
        return $files;
    }

    protected function normalizeParsedBody(array $headers, StreamInterface $body, array $post): array
    {
        if (array_key_exists('Content-Type', $headers) &&
            str_contains($headers['Content-Type'][0], 'application/json')
        ) {
            return json_decode($body->getContents(), true) ?? [];
        }
        return $post;
    }
}
