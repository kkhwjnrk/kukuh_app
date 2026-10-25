<?php
/**
 * Root Entry Forwarder
 * 
 * Mengarahkan eksekusi langsung ke direktori public/index.php
 * sehingga aplikasi dapat dibuka langsung melalui http://localhost/kukuh_app
 * maupun melalui virtual host.
 */

require_once __DIR__ . '/public/index.php';
