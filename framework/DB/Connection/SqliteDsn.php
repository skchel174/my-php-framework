<?php

namespace Framework\DB\Connection;

class SqliteDsn implements DSNInterface
{
    private string $driver;
    private string $dbname;

    public function __construct(string $driver, string $dbname)
    {
        $this->driver = $driver;
        $this->dbname = $dbname;
    }

    public function getParams(): array
    {
        return [
            'driver' => $this->driver,
            'dbname' => $this->dbname,
        ];
    }

    public function __toString(): string
    {
        return $this->driver . ':' . $this->dbname;
    }
}
