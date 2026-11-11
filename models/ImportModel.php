<?php

class ImportModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    // Logs completed import
    public function log(int $total, int $new, int $updated): void 
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO imports (items_count, new_count, updated_count, user_id) 
             VALUES (:total, :new, :updated, :user_id)"
        );
        $stmt->execute([
            'total' => $total,
            'new' => $new,
            'updated' => $updated, 
            'user_id' => $_SESSION['user_id']
        ]);
    }

    public function getAll(): array 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM imports WHERE user_id = :user_id ORDER BY imported_at DESC");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
