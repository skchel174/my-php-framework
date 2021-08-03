<?php

namespace Tests\framework\DB;

use Framework\DB\Connection\SqliteConnection;
use Framework\DB\DB;
use PHPUnit\Framework\TestCase;

class DBTest extends TestCase
{
    public DB $db;
    public array $config = [
        'driver' => 'sqlite',
        'dbname' => ':memory:',
    ];

    public function setUp(): void
    {
        $connection = new SqliteConnection($this->config);
        $this->db = new DB($connection);
        $this->db->getPDO()->exec('
        CREATE TABLE IF NOT EXISTS test_db
            (
                id            INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name          TEXT                              NOT NULL,
                transcription TEXT                              NOT NULL,
                translation   TEXT                              NOT NULL
            )
        ');
    }

    public function tearDown(): void
    {
        $this->db->getPDO()->exec('DELETE FROM test_db');
    }

    public function testUpdate(): void
    {
        $id = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'house', ':transcription' => 'haʊs', ':translation' => 'дом']
        );

        $result = $this->db->update('UPDATE test_db SET name = :new WHERE id = :id', [
            ':new' => 'House',
            ':id' => $id,
        ]);

        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }

    public function testDelete(): void
    {
        $id = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'house', ':transcription' => 'haʊs', ':translation' => 'дом']
        );

        $result = $this->db->update('DELETE FROM test_db WHERE id = :id', [':id' => $id]);

        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }

    public function testGetRow(): void
    {
        $id = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'house', ':transcription' => 'haʊs', ':translation' => 'дом']
        );

        $row = $this->db->getRow('
            SELECT id, name, transcription, translation FROM test_db WHERE id = :id',
            [':id' => $id]
        );

        $this->assertIsArray($row);
        $this->assertCount(4, $row);

        $this->assertEquals($id, $row['id']);
        $this->assertEquals('house', $row['name']);
    }

    public function testGetRows(): void
    {
        $houseId = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'house', ':transcription' => 'haʊs', ':translation' => 'дом']
        );
        $treeId = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'tree', ':transcription' => 'tri', ':translation' => 'дерево']
        );

        $rows = $this->db->getRows('SELECT id, name, transcription, translation FROM test_db');

        $this->assertIsArray($rows);
        $this->assertCount(2, $rows);

        $this->assertEquals($houseId, $rows[0]['id']);
        $this->assertEquals($treeId, $rows[1]['id']);

        $this->assertEquals('house', $rows[0]['name']);
        $this->assertEquals('tree', $rows[1]['name']);
    }

    public function testGetObject(): void
    {
        $id = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'house', ':transcription' => 'haʊs', ':translation' => 'дом']
        );

        $row = $this->db->getObject(
            Word::class,
            'SELECT id, name, transcription, translation FROM test_db WHERE id = :id',
            [':id' => $id],
        );

        $this->assertIsObject($row);
        $this->assertInstanceOf(Word::class, $row);

        $this->assertEquals('house', $row->name);
        $this->assertEquals('haʊs', $row->transcription);
        $this->assertEquals('дом', $row->translation);
    }

    public function testGetObjects(): void
    {
        $houseId = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'house', ':transcription' => 'haʊs', ':translation' => 'дом']
        );
        $treeId = $this->db->insert(
            'INSERT INTO test_db (name, transcription, translation) VALUES (:name, :transcription, :translation)',
            [':name' => 'tree', ':transcription' => 'tri', ':translation' => 'дерево']
        );

        $rows = $this->db->getObjects(Word::class, 'SELECT id, name, transcription, translation FROM test_db');

        $this->assertIsArray($rows);
        $this->assertCount(2, $rows);

        $this->assertInstanceOf(Word::class, $rows[0]);
        $this->assertInstanceOf(Word::class, $rows[1]);

        $this->assertEquals($houseId, $rows[0]->id);
        $this->assertEquals($treeId, $rows[1]->id);

        $this->assertEquals('house', $rows[0]->name);
        $this->assertEquals('tree', $rows[1]->name);
    }
}

class Word {
    public ?int $id = null;
    public string $name;
    public string $transcription;
    public string $translation;
}
