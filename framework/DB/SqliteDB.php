<?php

namespace Framework\DB;

use Framework\DB\Connection\SqliteConnection;

class SqliteDB extends DB
{
    public function __construct(SqliteConnection $connection)
    {
        parent::__construct($connection);
    }
}
