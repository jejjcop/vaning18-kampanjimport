<?php

class AddDetailsToImportsTable {
    public function run_migration(PDO $pdo): void 
    {
        $pdo->exec("ALTER TABLE imports ADD COLUMN new_count INTEGER DEFAULT 0");
        $pdo->exec("ALTER TABLE imports ADD COLUMN updated_count INTEGER DEFAULT 0");
    }
}

