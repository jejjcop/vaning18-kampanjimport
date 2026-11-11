<?php

try {
    $dbPath = __DIR__ . '/../database.sqlite';

    $pdo = new PDO("sqlite:" . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Activate WAL-mode
    $pdo->exec('PRAGMA journal_mode = WAL;');

} 
catch (PDOException $e) 
{
    die("DB Connection Failed: " . htmlspecialchars($e->getMessage()));
}
