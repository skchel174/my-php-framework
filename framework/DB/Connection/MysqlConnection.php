<?php

namespace Framework\DB\Connection;

class MysqlConnection extends Connection
{
    function makeDsn(array $config): string
    {
        return new MysqlDsn(
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['charset'] ?? 'utf8',
        );
    }
}