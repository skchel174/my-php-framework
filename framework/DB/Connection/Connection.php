<?php

namespace Framework\DB\Connection;

abstract class Connection extends \PDO
{
    public function __construct(array $config, array $options)
    {
        $dsn = $this->makeDsn($config);
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        parent::__construct($dsn, $user, $password, $options);
    }

    abstract function makeDsn(array $config): string;
}
