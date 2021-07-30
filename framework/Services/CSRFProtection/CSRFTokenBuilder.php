<?php

namespace Framework\Services\CSRFProtection;

use Framework\Http\Sessions\Exceptions\SessionNotStartedException;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Services\CSRFProtection\Exceptions\MissingCSRFTokenException;

class CSRFTokenBuilder
{
    const KEY = 'csrf_token';
    const HEADER = 'X-CSRF-Token';

    private string $tokenSalt = 'secret';
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->sessionExistGuard();
        $this->session = $session;
    }

    public function set()
    {
        $token = md5($this->session->getId() . $this->tokenSalt);
        $this->session->set(static::KEY, $token);
    }

    public function get(): array
    {
        if (!$token = $this->session->get(static::KEY)) {
            throw new MissingCSRFTokenException();
        }
        return [ 'key' => static::KEY, 'token' => $token ];
    }

    protected function sessionExistGuard(): void
    {
        if (empty($this->session->getId())) {
            throw new SessionNotStartedException();
        }
    }
}
