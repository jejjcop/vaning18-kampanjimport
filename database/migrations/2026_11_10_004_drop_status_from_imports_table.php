<?php

class DropStatusFromImportsTable {
    public function run_migration(PDO $pdo): void 
    {
        $pdo->exec("ALTER TABLE imports DROP COLUMN status");
    }
}
