<?php
function getDBConnection() {
    // If DB env variables are NOT set, skip DB connection
    if (
        empty($_ENV['DB_HOST']) ||
        empty($_ENV['DB_NAME']) ||
        empty($_ENV['DB_USER'])
    ) {
        return null;
    }

    try {
        $dsn = "mysql:host=" . $_ENV['DB_HOST'] .
               ";dbname=" . $_ENV['DB_NAME'] .
               ";charset=utf8mb4";

        $pdo = new PDO(
            $dsn,
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'] ?? '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

        return $pdo;

    } catch (Exception $e) {
        // DO NOT crash the site
        return null;
    }
}
