<?php

namespace Tests;

/**
 * Micro Unit Test Runner (Standalone Pure PHP)
 * 
 * Memenuhi SKKNI J.620100.033.02 (Melaksanakan Pengujian Unit Program)
 * Dapat dijalankan langsung di terminal tanpa ketergantungan composer/phpunit:
 * php tests/run.php
 */
class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $failures = [];
    private float $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function runTestClass(string $className): void
    {
        echo "\n\033[1;34m=== Menjalankan Test Suite: " . $className . " ===\033[0m\n";

        $instance = new $className();
        $methods = get_class_methods($instance);

        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                try {
                    $instance->$method($this);
                    $this->passed++;
                    echo "  \033[32m[PASS]\033[0m {$method}\n";
                } catch (\Throwable $e) {
                    $this->failed++;
                    $this->failures[] = [
                        'class'   => $className,
                        'method'  => $method,
                        'message' => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                    ];
                    echo "  \033[31m[FAIL]\033[0m {$method}: {$e->getMessage()}\n";
                }
            }
        }
    }

    public function assertEquals($expected, $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            $msg = $message ?: "Ekspektasi: " . var_export($expected, true) . ", tetapi mendapatkan: " . var_export($actual, true);
            throw new \Exception($msg);
        }
    }

    public function assertTrue($condition, string $message = 'Kondisi bernilai false, diharapkan true'): void
    {
        if ($condition !== true) {
            throw new \Exception($message);
        }
    }

    public function assertFalse($condition, string $message = 'Kondisi bernilai true, diharapkan false'): void
    {
        if ($condition !== false) {
            throw new \Exception($message);
        }
    }

    public function assertNotNull($value, string $message = 'Nilai bernilai null'): void
    {
        if ($value === null) {
            throw new \Exception($message);
        }
    }

    public function printSummary(): int
    {
        $duration = round(microtime(true) - $this->startTime, 4);
        $total = $this->passed + $this->failed;

        echo "\n======================================================\n";
        echo "HASIL AKHIR PENGUJIAN UNIT (SKKNI J.620100.033.02)\n";
        echo "======================================================\n";
        echo "Total Test Dijalankan : {$total}\n";
        echo "Sukses (Passed)       : \033[32m{$this->passed}\033[0m\n";
        echo "Gagal  (Failed)       : " . ($this->failed > 0 ? "\033[31m{$this->failed}\033[0m" : "0") . "\n";
        echo "Waktu Eksekusi        : {$duration} detik\n";
        echo "======================================================\n";

        if ($this->failed > 0) {
            echo "\n\033[31mRincian Kegagalan:\033[0m\n";
            foreach ($this->failures as $f) {
                echo "- [{$f['class']}::{$f['method']}] {$f['message']}\n";
                echo "  File: {$f['file']} (baris {$f['line']})\n";
            }
            return 1;
        }

        echo "\n\033[32m[SEMUA UNIT TEST BERHASIL DIUJI DENGAN SEMPURNA!]\033[0m\n\n";
        return 0;
    }
}
