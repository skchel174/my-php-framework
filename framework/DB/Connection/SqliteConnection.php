<?php

namespace Framework\DB\Connection;

class SqliteConnection extends Connection
{
    function makeDsn(array $config): string
    {
        return new SqliteDsn($config['driver'], $config['dbname']);
    }
}
