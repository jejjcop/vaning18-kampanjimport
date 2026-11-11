<?php

require_once __DIR__ . '/../models/CampaignModel.php';

class CampaignController {
    private PDO $pdo;
    private CampaignModel $campaignModel;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
        $this->campaignModel = new CampaignModel($pdo);
    }

    // Displays first/front page
    public function index(): void 
    {
        $totalNrOfCampaigns = $this->campaignModel->getCampaignCount();
        require_once __DIR__ . '/../views/home_view.php';
    }

    // Displays the list of all campaigns
    public function viewCampaigns(): void 
    {
        try 
        {
            $campaigns = $this->campaignModel->getAllCampaigns();
            require_once __DIR__ . '/../views/campaigns_view.php';
        } 
        catch (Exception $e) 
        {
            $errorMessage = $e->getMessage();
            require_once __DIR__ . '/../views/campaigns_view.php';
        }
    }

    // Deletes a campaign
    public function delete(): void 
    {
        $id = $_GET['id'] ?? null;
        if ($id) 
        {
            try 
            {
                $this->campaignModel->deleteCampaign((int)$id);
                header("Location: index.php?action=list&deleted=1");
                exit;
            } 
            catch (Exception $e) 
            {
                $errorMessage = $e->getMessage();
                $campaigns = $this->campaignModel->getAllCampaigns();
                require_once __DIR__ . '/../views/campaigns_view.php';
            }
        } 
        else 
        {
            header("Location: index.php?action=list");
            exit;
        }
    }    

    // Import campaigns from the API
    public function doImport(): void 
    {
        try 
        {
            $result = $this->campaignModel->importCampaigns();
            header("Location: index.php?success=" . $result['success'] . "&message=" . $result['message'] . "&total=" . $result['total'] . "&new=" . $result['new'] . "&updated=" . $result['updated']);
            exit;
        } 
        catch (Exception $e) 
        {
            $errorMessage = $e->getMessage();
            $totalCount = $this->campaignModel->getCampaignCount();
            require_once __DIR__ . '/../views/home_view.php';
        }
    }

    // Search campaigns
    public function search(): void 
    {
        $campaigns = array();
        $q = "";
        if (isset($_GET['q']))
        {
            $q = htmlspecialchars($_GET['q']);
            $campaigns = $this->campaignModel->search($q);
        }
        require_once __DIR__ . '/../views/search_campaigns_view.php';
    }

}
