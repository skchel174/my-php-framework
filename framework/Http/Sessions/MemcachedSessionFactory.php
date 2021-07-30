<?php

namespace Framework\Http\Sessions;

class MemcachedSessionFactory extends SessionFactory
{
    protected function setSessionSaveHandler(array $config): void
    {
        ini_set('session.save_handler', 'memcached');
    }
}
