<?php

namespace Framework\DB;

use Framework\DB\Connection\MysqlConnection;

class MysqlDB extends DB
{
    public function __construct(MysqlConnection $connection)
    {
        parent::__construct($connection);
    }
}
