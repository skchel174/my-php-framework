<?php

namespace Framework\Http\Sessions;

use Framework\DB\DBInterface;

class SqliteSessionHandler implements \SessionHandlerInterface
{
    protected DBInterface $db;

    public function __construct(DBInterface $db)
    {
        $this->db = $db;
    }

    public function close(): bool
    {
        return true;
    }

    public function destroy($id): bool
    {
        return $this->delete($id);
    }

    public function gc($max_lifetime): bool
    {
        return $this->clean($max_lifetime);
    }

    public function open($path, $name): bool
    {
        $this->clean(ini_get('session.gc_maxlifetime'));
        return true;
    }

    public function read($id)
    {
        if ($session = $this->select($id)) {
            $this->updateSessionTime($id);
        }
        return $session['session_data'] ?? '';
    }

    public function write($id, $data): bool
    {
        if ($this->isSessionExists($id)) {
            $this->update($id, $data);
        } else {
            $this->insert($id, $data);
        }
        return true;
    }

    protected function insert(string $session_id, $session_data): bool
    {
        $query = '
            INSERT INTO  
                sessions(session_id, session_data, session_time) 
            VALUES 
                (:session_id, :session_data, :session_time)';

        return (bool)$this->db->insert($query, [
            ':session_id' => $session_id,
            ':session_data' => $session_data,
            ':session_time' => time()
        ]);
    }

    protected function update(string $session_id, $session_data): bool
    {
        $query = '
            UPDATE 
                sessions 
            SET 
                session_data = :session_data, 
                session_time = :session_time 
            WHERE 
                session_id = :session_id';

        return (bool)$this->db->update($query, [
            ':session_id' => $session_id,
            ':session_data' => $session_data,
            ':session_time' => time()
        ]);
    }

    protected function updateSessionTime($session_id): bool
    {
        $query = '
            UPDATE sessions
            SET
                session_time = :session_time
            WHERE
                session_id = :session_id';

        return (bool)$this->db->update($query, [
            ':session_id' => $session_id,
            ':session_time' => time(),
        ]);
    }

    protected function select(string $session_id): bool|array
    {
        $query = '
            SELECT 
                session_id, 
                session_data, 
                session_time 
            FROM 
                sessions 
            WHERE 
                session_id = :session_id';

        return $this->db->getRow($query, [':session_id' => $session_id]);
    }

    protected function isSessionExists($session_id): bool
    {
        $query = '
            SELECT
                COUNT(*) AS `count`
            FROM 
                sessions
            WHERE 
                session_id = :session_id';

        $res = $this->db->getRow($query, [':session_id' => $session_id]);
        return (bool)$res['count'];
    }

    protected function delete($session_id): bool
    {
        $query = '
            DELETE FROM 
                sessions 
            WHERE 
                session_id = :session_id';

        return (bool)$this->db->delete($query, [':session_id' => $session_id]);
    }

    protected function clean($maxlifetime): bool
    {
        $query = '
            DELETE FROM 
                sessions 
            WHERE 
                session_time <= session_time + :maxlifetime';

        return (bool)$this->db->delete($query, [':maxlifetime' => $maxlifetime]);
    }
}
