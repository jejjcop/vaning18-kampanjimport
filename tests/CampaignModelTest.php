<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/CampaignModel.php';
require_once __DIR__ . '/../models/HttpClient.php';
require_once __DIR__ . '/../models/ImportModel.php';

class CampaignModelTest extends TestCase {
    private PDO $pdo;

    protected function setUp(): void 
    {
        parent::setUp();

        session_start();

        // Create a temporary SQLite-DB in memory for testing purposes
        $this->pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        // Create tables 
        $this->pdo->exec("
            CREATE TABLE campaigns (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                external_id INTEGER NOT NULL,
                title TEXT NOT NULL,
                description TEXT,
                thumbnail TEXT,
                user_id INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
            
            CREATE UNIQUE INDEX idx_campaigns_user ON campaigns(external_id, user_id);

            CREATE TABLE imports (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                imported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                user_id INTEGER NOT NULL,
                items_count INTEGER DEFAULT 0,
                new_count INTEGER DEFAULT 0,
                updated_count INTEGER DEFAULT 0
            );

            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Pretend we are a logged in user
        $_SESSION['user_id'] = 1;
    }

    protected function tearDown(): void 
    {
        unset($_SESSION['user_id']);
        session_destroy();
        parent::tearDown();
    }

    /**
     * Tests that the import function saves campaigns for the logged in user
     */
    public function testImportCampaignsSavesDataCorrectly(): void {
        // Create a test HttpClient so that we dont actually call the actual API with CURL
        $mockClient = $this->createMock(HttpClient::class);
        $dummyApiResponse = json_encode([
            'products' => [
                ['id' => 101, 'title' => 'Campaign A', 'description' => 'Description A', 'thumbnail' => 'img1.jpg', 'user_id' => $_SESSION['user_id']],
                ['id' => 102, 'title' => 'Campaign B', 'description' => 'Description B', 'thumbnail' => 'img2.jpg', 'user_id' => $_SESSION['user_id']]
            ]
        ]);
        $mockClient->method('get')->willReturn($dummyApiResponse);

        // Inject the test HttpClient in the model
        $model = new CampaignModel($this->pdo, $mockClient);
        $result = $model->importCampaigns();

        // Verify what is returned
        $this->assertEquals(2, $result['total']);
        $this->assertEquals(2, $result['new']);
        $this->assertEquals(0, $result['updated']);

        // Verify that the data actually exists in the DB
        $this->assertEquals(2, $model->getCampaignCount());
    }

    /**
     * Tests that repeated imports for the same user updates campaigns instead of creating duplicates
     */
    public function testRepeatedImportDoesNotCreateDuplicates(): void 
    {
        $mockClient = $this->createMock(HttpClient::class);
        $dummyApiResponse = json_encode([
            'products' => [
                ['id' => 101, 'title' => 'Campaign A', 'description' => 'Other text here', 'thumbnail' => 'img1.jpg']
            ]
        ]);
        $mockClient->method('get')->willReturn($dummyApiResponse);

        $model = new CampaignModel($this->pdo, $mockClient);
        
        // First import (creates new)
        $model->importCampaigns();
        $this->assertEquals(1, $model->getCampaignCount());

        // Second import (updates)
        $result2 = $model->importCampaigns();
        $this->assertEquals(1, $result2['total']);
        $this->assertEquals(0, $result2['new']);
        $this->assertEquals(1, $result2['updated']);
        
        // Number campaigns in the DB should still be 1
        $this->assertEquals(1, $model->getCampaignCount());
    }

    /**
     * Tests that the user only sees their own campaigns
     */
    public function testUserIsolation(): void 
    {
        $mockClient = $this->createMock(HttpClient::class);
        $dummyApiResponse = json_encode([
            'products' => [
                ['id' => 101, 'title' => 'Campaign A', 'description' => 'Test', 'thumbnail' => '']
            ]
        ]);
        $mockClient->method('get')->willReturn($dummyApiResponse);

        // User 1 imports
        $_SESSION['user_id'] = 1;
        $modelUser1 = new CampaignModel($this->pdo, $mockClient);
        $modelUser1->importCampaigns();

        // Switch to user 2 and import the same campaign
        $_SESSION['user_id'] = 2;
        $modelUser2 = new CampaignModel($this->pdo, $mockClient);
        $modelUser2->importCampaigns();

        // User 2 should only see their campaign
        $this->assertEquals(1, $modelUser2->getCampaignCount());

        // Check there are a total of two campaigns
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM campaigns");
        $this->assertEquals(2, (int)$stmt->fetchColumn());
    }

    /**
     * Tests that the transaction is rolled back if an error occurs during import
     */
    public function testTransactionRollbackOnError(): void 
    {
        // We simluate an error by throwing an exception
        $mockClient = $this->createMock(HttpClient::class);
        
        // Create invalid response
        $mockClient->method('get')->willThrowException(new RuntimeException("API down!"));

        $model = new CampaignModel($this->pdo, $mockClient);

        $this->expectException(RuntimeException::class);

        try 
        {
            $model->importCampaigns();
        } 
        finally 
        {
            // Confirm nothing was added to the DB if it crashed
            $this->assertEquals(0, $model->getCampaignCount());
        }
    }
}
