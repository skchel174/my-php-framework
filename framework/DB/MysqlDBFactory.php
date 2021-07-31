<?php

namespace Framework\DB;

use Framework\DB\Connection\MysqlConnection;
use Psr\Container\ContainerInterface;

class MysqlDBFactory
{
    public function __invoke(ContainerInterface $container): DB
    {
        $connection = new MysqlConnection($container->get('config.db.mysql'), [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
        return new DB($connection);
    }
}
