<?php
/**
 * Test Runner Executable Script
 * 
 * Jalankan di terminal:
 * php tests/run.php
 */

// 1. Autoloader
spl_autoload_register(function ($class) {
    if (strncmp('App\\', $class, 4) === 0) {
        $file = __DIR__ . '/../app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($file)) require_once $file;
    } elseif (strncmp('Tests\\', $class, 6) === 0) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, 6)) . '.php';
        if (file_exists($file)) require_once $file;
    }
});

require_once __DIR__ . '/../app/Helpers/utility.php';

use Tests\TestRunner;
use Tests\Unit\DatabaseTest;
use Tests\Unit\AuthTest;
use Tests\Unit\ValidationTest;
use Tests\Unit\ModelTest;

echo "======================================================\n";
echo "   APOTEK LSP - AUTOMATED UNIT TEST SUITE             \n";
echo "======================================================\n";

$runner = new TestRunner();

$runner->runTestClass(DatabaseTest::class);
$runner->runTestClass(AuthTest::class);
$runner->runTestClass(ValidationTest::class);
$runner->runTestClass(ModelTest::class);

$exitCode = $runner->printSummary();
exit($exitCode);
