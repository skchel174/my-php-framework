<?php

namespace Framework\Http\Client\Uri;

use Framework\Http\Client\Uri\Exceptions\InvalidUriSchemeException;
use Framework\Http\Client\Uri\Exceptions\UriComponentTypeException;
use Framework\Http\Client\Uri\Exceptions\UriPortRangeException;
use Framework\Http\Client\Uri\Exceptions\UriPortTypeException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    // зарезервированные символы
    const URI_GEN_DELIMS = ':\/\/\?#\[\]@';

    // базовые разграничители
    const URI_SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

    // незарезервированные символы
    const URI_UNRESERVED = 'a-zA-Z0-9_\-\.~';

    protected array $defaultPorts = [
        'http' => 80,
        'https' => 443
    ];

    protected array $portsRange = [
        'min' => 0,
        'max' => 65535
    ];

    protected string $scheme;
    protected string $user;
    protected ?string $pass;
    protected string $host;
    protected ?int $port;
    protected string $path;
    protected string $query;
    protected string $fragment;

    public function __construct(
        string $scheme = '',
        string $user = '',
        ?string $pass = null,
        string $host = '',
        ?int $port = null,
        string $path = '',
        string $query = '',
        string $fragment = '',
    ) {
        $this->scheme = $scheme ? $this->filterScheme($scheme) : '';
        $this->user = $user ? $this->filterUserName($user) : '';
        $this->pass = $pass ? $this->filterUserPass($pass) : null;
        $this->host = $host ? $this->filterHost($host) : '';
        $this->port = $port ? $this->filterPort($port) : null;
        $this->path = $path ? $this->filterPath($path) : '';
        $this->query = $query ? $this->filterQueryString($query) : '';
        $this->fragment = $fragment ? $this->filterFragment($fragment) : '';
    }

    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme) {
            $uri .= $this->scheme . ':';
        }

        if ($this->getAuthority()) {
            $uri .= '//' . $this->getAuthority();
        }

        if ($this->getPath()) {
            $uri .= $this->getPath();
        }

        if ($this->query) {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment) {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * При наличии информации о пользователе
     * компонент authority имеет вид user[:password]@host
     * при отсутствии, host:port
     *
     * @return string
     */
    public function getAuthority(): string
    {
        if (!$this->host) {
            return '';
        }

        $authority = $this->getHost();

        if ($this->user) {
            $authority = $this->getUserInfo() . '@' . $authority;
        }

        if ($this->getPort()) {
            $authority .= ':' . $this->getPort();
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        $userInfo = $this->user;
        if ($this->pass) {
            $userInfo .= ':' . $this->pass;
        }
        return $userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * если порт нестандартный, возвращает целое число
     * если порт стандартный, возвращает null
     * если остутствуют порт и схема, возвращает null
     * если порт отсутствует, но известна схема, возвращает null
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        if ($this->scheme && $this->defaultPorts[$this->scheme] == $this->port) {
            return null;
        }
        return $this->port;
    }

    public function getPath(): string
    {
        if ($this->path) {
            if ($this->path === '' || substr($this->path, 0, 1) != '/') {
                return '/' . $this->path;
            }
            return $this->path;
        }
        return '';
    }

    public function getQuery(): string
    {
        return $this->query ?? '';
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): static
    {
        $copy = clone $this;
        $copy->scheme = $this->filterScheme($scheme);
        return $copy;
    }

    public function withUserInfo($user, $password = null): static
    {
        $copy = clone $this;
        $copy->user = $this->filterUserName($user);
        $copy->pass = $this->filterUserPass($password);
        return $copy;
    }

    public function withHost($host): static
    {
        $copy = clone $this;
        $copy->host = $this->filterHost($host);
        return $copy;
    }

    public function withPort($port): static
    {
        $copy = clone $this;
        $copy->port = $this->filterPort($port);
        return $copy;
    }

    public function withPath($path): static
    {
        $copy = clone $this;
        $copy->path = $this->filterPath($path);
        return $copy;
    }

    public function withQuery($query): static
    {
        $clone = clone $this;
        $clone->query = $this->filterQueryString($query);
        return $clone;
    }

    public function withFragment($fragment): static
    {
        $copy = clone $this;
        $copy->fragment = $this->filterFragment($fragment);
        return $copy;
    }

    protected function filterScheme(string $scheme): string
    {
        if (strlen($scheme) === 0) {
            return '';
        }

        $scheme = strtolower($scheme);
        $scheme = preg_replace('#:(//)?.*#', '', $scheme);

        if (!array_key_exists($scheme, $this->defaultPorts)) {
            throw new InvalidUriSchemeException($scheme);
        }

        return $scheme;
    }

    protected function filterUserName(string $user): string
    {
        $pattern = '#(?:[^' . static::URI_UNRESERVED . static::URI_SUB_DELIMS . '%:]+)#u';
        return preg_replace_callback($pattern, [$this, 'encode'], $user);
    }

    protected function filterUserPass(?string $pass = null): ?string
    {
        if ($pass === null) {
            return $pass;
        }

        $pattern = '#(?:[^' . static::URI_UNRESERVED . static::URI_SUB_DELIMS . '%:]+)#u';
        return preg_replace_callback($pattern, [$this, 'encode'], $pass);
    }

    protected function filterHost(string $host): string
    {
        $pattern = '#(?:[^' . static::URI_UNRESERVED . '%]+)#u';
        return preg_replace_callback($pattern, [$this, 'encode'], $host);
    }

    protected function filterPort(int $port): int
    {
        if ($port < $this->portsRange['min'] || $port > $this->portsRange['max']) {
            throw new UriPortRangeException($port);
        }
        return $port;
    }

    protected function filterPath(string $path): string
    {
        $pattern = '#(?:[^' . static::URI_UNRESERVED . static::URI_SUB_DELIMS . '%:@/]+)#u';
        $path = preg_replace_callback($pattern, [$this, 'encode'], $path);

        if (strlen($path) === 0 || $path[0] !== '/') {
            return $path;
        }
        return '/' . ltrim($path, '/');
    }

    protected function filterQueryString(string $query): string
    {
        if (strlen($query) !== 0 && $query[0] == '?') {
            $query = substr($query, 1);
        }

        $pattern = '#(?:[^' . static::URI_UNRESERVED . static::URI_SUB_DELIMS . '%:@/\?]+)#u';
        return preg_replace_callback($pattern, [$this, 'encode'], $query);
    }

    protected function filterFragment(string $fragment): string
    {
        if (strlen($fragment) !== 0 && $fragment[0] == '#') {
            $fragment = substr($fragment, 1);
        }

        $pattern = '#(?:[^' . static::URI_UNRESERVED . static::URI_SUB_DELIMS . '%:@/\?]+)#u';
        return preg_replace_callback($pattern, [$this, 'encode'], $fragment);
    }

    protected function encode(array $value): string
    {
        return rawurlencode($value[0]);
    }
}
