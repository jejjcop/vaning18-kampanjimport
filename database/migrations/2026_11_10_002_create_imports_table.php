<?php

class CreateImportsTable {
    public function run_migration(PDO $pdo): void 
    {
        $sql = "CREATE TABLE IF NOT EXISTS imports (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            imported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status TEXT NOT NULL,
            user_id INTEGER NOT NULL,
            items_count INTEGER DEFAULT 0
        )";
        $pdo->exec($sql);
    }
}
