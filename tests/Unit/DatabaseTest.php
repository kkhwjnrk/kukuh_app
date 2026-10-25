<?php

namespace Tests\Unit;

use App\Core\Database;
use Tests\TestRunner;

class DatabaseTest
{
    public function testDatabaseConnection(TestRunner $runner): void
    {
        $db = Database::getInstance();
        $runner->assertNotNull($db, "Instance Database tidak boleh null");
        
        $pdo = $db->getConnection();
        $runner->assertTrue($pdo instanceof \PDO, "Koneksi harus bertipe PDO");
    }

    public function testDatabaseQueryExecution(TestRunner $runner): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT 1 as val");
        $result = $stmt->fetch();
        
        $runner->assertEquals(1, (int)$result['val'], "Hasil query SELECT 1 harus 1");
    }
}
