<?php

namespace Framework\Services\CSRFProtection;

use Framework\Http\Sessions\Exceptions\SessionNotStartedException;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Services\CSRFProtection\Exceptions\MissingCSRFTokenException;

class CSRFToken
{
    const KEY = 'csrf_token';
    const HEADER = 'X-CSRF-Token';

    private string $tokenSalt = 'secret';
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setToken(): void
    {
        $this->sessionExistGuard();
        $token = md5($this->session->getId() . $this->tokenSalt);
        $this->session->set(static::KEY, $token);
    }

    public function getToken(): string
    {
        $this->sessionExistGuard();
        if (!$token = $this->session->get(static::KEY)) {
            throw new MissingCSRFTokenException();
        }
        return $token;
    }

    protected function sessionExistGuard(): void
    {
        if (empty($this->session->getId())) {
            throw new SessionNotStartedException();
        }
    }
}
