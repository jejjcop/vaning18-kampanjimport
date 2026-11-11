<?php

require_once __DIR__ . '/../models/ImportModel.php';

class ImportController {
    private PDO $pdo;
    private ImportModel $importModel;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
        $this->importModel = new ImportModel($pdo);
    }

    public function viewImportHistory(): void 
    {
        try 
        {
            $imports = $this->importModel->getAll();
            require_once __DIR__ . '/../views/imports_view.php';
        } 
        catch (Exception $e) 
        {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../views/imports_view.php';
        }
    }
}
