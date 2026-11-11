<?php

require_once __DIR__ . '/../config/db.php';

// 1. Skapa en tabell för att hålla reda på körda migreringar om den inte finns
$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// 2. Hämta alla redan körda migreringar
$stmt = $pdo->query("SELECT migration FROM migrations");
$executedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 3. Läs av migreringsmappen
$migrationsDir = __DIR__ . '/migrations';
$files = glob($migrationsDir . '/*.php');

sort($files); // Säkerställ kronologisk ordning

$newMigrationsCount = 0;

foreach ($files as $file) {
    $migrationName = basename($file, '.php');

    // Om migreringen inte har körts än
    if (!in_array($migrationName, $executedMigrations)) {
        require_once $file;
        
        // Extrahera klassnamnet (eller anta standardnamn baserat på fil)
        // För enkelhetens skull i detta exempel skapar vi instansen direkt:
        $className = '';
        // Konvertera filnamn till klassnamn (t.ex. 2026_11_10_001_create_campaigns_table -> CreateCampaignsTable)
        $parts = explode('_', $migrationName);
        array_splice($parts, 0, 4); // Ta bort datum och versionsnummer (2026, 11, 10, 001)
        $className = implode('', array_map('ucfirst', $parts));

        if (class_exists($className)) {
            $migration = new $className();
            $migration->run_migration($pdo);

            // Registrera att migreringen är körd
            $insert = $pdo->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
            $insert->execute(['migration' => $migrationName]);

            echo "Ran migration: {$migrationName}\n";
            $newMigrationsCount++;
        }
    }
}

if ($newMigrationsCount === 0) 
{
    echo "No new migrations found.\n";
} else {
    echo "Migration complete! {$newMigrationsCount} new migrations were run.\n";
}
