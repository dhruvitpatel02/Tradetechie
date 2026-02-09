<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tradetechie_db');

define('SITE_URL', 'http://localhost/Personal%20Projects/Tradetechie/');
define('SITE_NAME', 'TradeTechie');

define('SESSION_TIMEOUT', 3600);
define('ITEMS_PER_PAGE', 20);
define('CACHE_DURATION', 300);

date_default_timezone_set('Asia/Kolkata');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Router.php';
