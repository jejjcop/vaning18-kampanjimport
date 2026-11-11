<?php

class CreateUsersTable {
    public function run_migration(PDO $pdo): void 
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);

        // Create two sample users
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) 
        {
            $hash = password_hash('password', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES ('user1', :password)");
            $stmt->execute(['password' => $hash]);
            
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES ('user2', :password)");
            $stmt->execute(['password' => $hash]);
        }
    }
}
