<?php

class CreateCampaignsTable {
    public function run_migration(PDO $pdo): void 
    {
        $sql = "CREATE TABLE IF NOT EXISTS campaigns (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            external_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            description TEXT,
            thumbnail TEXT,
            user_id INTEGER NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);

        // Create combined index on external_id and user_id
        $indexSql = "CREATE UNIQUE INDEX IF NOT EXISTS idx_campaigns_user 
                     ON campaigns(external_id, user_id)";
        $pdo->exec($indexSql);
    }
}
