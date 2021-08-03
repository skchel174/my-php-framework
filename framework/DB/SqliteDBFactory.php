<?php

namespace Framework\DB;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\DB\Connection\SqliteConnection;

class SqliteDBFactory
{
    public function __invoke(ContainerInterface $container): DBInterface
    {
        $connection = new SqliteConnection($container->get('config.db.sqlite'), [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
        return new DB($connection);
    }
}
