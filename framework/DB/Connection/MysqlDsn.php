<?php

namespace Framework\DB\Connection;

class MysqlDsn implements DSNInterface
{
    private string $driver;
    private string $host;
    private string $port;
    private string $dbname;
    private string $charset;

    public function __construct(
        string $driver,
        string $host,
        string $port,
        string $dbname,
        string $charset,
    )
    {
        $this->driver = $driver;
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->charset = $charset;
    }

    public function getParams(): array
    {
        return [
            'driver' => $this->driver,
            'host' => $this->host,
            'port' => $this->port,
            'dbname' => $this->dbname,
            'charset' => $this->charset,
        ];
    }

    public function __toString(): string
    {
        $host = 'host=' . $this->host;
        $port = 'port=' . $this->port;
        $dbname = 'dbname='  . $this->dbname;
        $charset = 'charset=' . $this->charset;
        return $this->driver . ':' . $host . ';' . $port . ';' . $dbname . ';' . $charset;
    }
}
