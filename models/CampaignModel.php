<?php

require_once __DIR__ . '/HttpClient.php';
require_once __DIR__ . '/ImportModel.php'; 

class CampaignModel {
    private PDO $pdo;
    private HttpClient $client;
    private ImportModel $importModel;

    public function __construct(PDO $pdo, ?HttpClient $client = null) 
    {
        $this->pdo = $pdo;
        $this->client = $client ?? new HttpClient(10);
        $this->importModel = new ImportModel($pdo); // Koppla ihop dem
    }

    public function importCampaigns(): array 
    {
        $url = "https://dummyjson.com/products?limit=0";
        $response = $this->client->get($url);
        $data = json_decode($response, true);

        if (!isset($data['products'])) 
        {
            return [
                'success' => 0, 
                'message' => 'No products found!', 
                'total' => 0,
                'new' => 0,
                'updated' => 0
            ];
        }

        try
        {
            // Start transaction
            $this->pdo->beginTransaction();

            $checkStmt = $this->pdo->prepare("SELECT id FROM campaigns WHERE external_id = :external_id AND user_id = :user_id");

            $insertSql = "INSERT INTO campaigns (external_id, title, description, thumbnail, user_id) 
                        VALUES (:external_id, :title, :description, :thumbnail, :user_id)
                        ON CONFLICT(external_id, user_id) DO UPDATE SET 
                        title = excluded.title, 
                        description = excluded.description, 
                        thumbnail = excluded.thumbnail";

            $stmt = $this->pdo->prepare($insertSql);

            $total = 0;
            $newCount = 0;
            $updatedCount = 0;

            foreach ($data['products'] as $product) 
            {
                $extId = $product['id'];

                $checkStmt->execute(['external_id' => $extId, 'user_id' => $_SESSION['user_id']]);
                $exists = $checkStmt->fetch();

                if ($exists) 
                {
                    $updatedCount++;
                } 
                else 
                {
                    $newCount++;
                }

                $stmt->execute([
                    'external_id' => $extId,
                    'title' => $product['title'],
                    'description' => $product['description'],
                    'thumbnail' => $product['thumbnail'] ?? '', 
                    'user_id' => $_SESSION['user_id']
                ]);
                
                $total++;
            }

            $this->importModel->log($total, $newCount, $updatedCount);

            // Confirm transaction
            $this->pdo->commit();

            return [
                'success' => 1, 
                'message' => '', 
                'total' => $total,
                'new' => $newCount,
                'updated' => $updatedCount
            ];
        }
        catch (\Exception $e) 
        {
            // If anything goes wrong during transaction, undo all changes in the database
            if ($this->pdo->inTransaction()) 
            {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function deleteCampaign(int $id): bool 
    {
        $stmt = $this->pdo->prepare("DELETE FROM campaigns WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']]);
    }

    public function getAllCampaigns(): array 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campaigns WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCampaignCount(): int 
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM campaigns WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        return (int) $stmt->fetchColumn();
    }
}
