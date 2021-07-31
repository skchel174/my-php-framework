<?php

namespace Framework\Http\Sessions;

use Framework\Http\Sessions\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    protected array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function start(): bool
    {
        return session_start($this->options);
    }

    public function getId(): string
    {
        return session_id();
    }

    public function id(string $id): bool|string
    {
        return session_id($id);
    }

    public function getName(): string
    {
        return session_name();
    }

    public function name(string $name): bool|string
    {
        return session_name($name);
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key = ''): mixed
    {
        if (empty($key)) {
            return $_SESSION;
        }
        return $_SESSION[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function remove(string $key): bool
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    public function clear(): void
    {
        $_SESSION = [];
    }

    public function destroy(): bool
    {
        return session_destroy();
    }

    public function flush(): void
    {
        $this->destroy();
        setcookie($this->getName(), '', time() - 3600, '/');
    }
}
