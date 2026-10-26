<?php

namespace App\Core;

/**
 * Class Logger
 * 
 * Menyediakan mekanisme pencatatan riwayat error dan aktivitas sistem
 * ke file log lokal (storage/logs/app.log).
 */
class Logger
{
    /** @var string Lokasi file log default */
    private static string $logFile = __DIR__ . '/../../storage/logs/app.log';

    /**
     * Mencatat pesan log umum (INFO).
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    /**
     * Mencatat peringatan (WARNING).
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    /**
     * Mencatat pesan kegagalan/error (ERROR).
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    /**
     * Menuliskan baris log berformat standar ISO ke dalam file.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    private static function write(string $level, string $message, array $context = []): void
    {
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logEntry = sprintf("[%s] [%s]: %s%s%s", $timestamp, $level, $message, $contextStr, PHP_EOL);

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
