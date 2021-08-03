<?php

namespace Framework\Http\Sessions;

use Framework\DB\Connection\SqliteConnection;
use Framework\DB\DB;
use Framework\DB\DBInterface;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;

class SqliteSessionFactory
{
    public function __invoke(ContainerInterface $container): SessionInterface
    {
        $config = $container->get('config.sessions');

        $db = new DB(new SqliteConnection($config['sqlite_handler']));
        $handler = new SqliteSessionHandler($db);
        session_set_save_handler($handler, true);

        $this->createDatabaseFile($config['sqlite_handler']);
        $this->createSessionsTable($db);

        return new Session([
            'gc_maxlifetime' =>  $config['options']['gc_maxlifetime'],
        ]);
    }

    protected function createDatabaseFile(array $config)
    {
        if (!file_exists($config['dbname'])) {
            touch($config['dbname']);
        }
    }

    protected function createSessionsTable(DBInterface $db): void
    {
        $db->getPdo()->exec("
            CREATE TABLE IF NOT EXISTS `sessions` (
                `session_id` TEXT NOT NULL PRIMARY KEY,
                `session_data` TEXT DEFAULT NULL,
                `session_time` NUMERIC    
            )
        ");

        $db->getPdo()->exec("
            CREATE INDEX IF NOT EXISTS `session_date_idx` ON `sessions` (`session_time`)
        ");
    }
}
