<?php

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controllers/CampaignController.php';
require_once __DIR__ . '/controllers/ImportController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$authController = new AuthController($pdo);
$action = $_GET['action'] ?? 'home';

if ($action === 'login') 
{
    $authController->showLogin();
    exit;
}
if ($action === 'login_submit') 
{
    $authController->login();
    exit;
}
if ($action === 'logout') 
{
    $authController->logout();
    exit;
}

// Protect all other pages - if not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) 
{
    header("Location: index.php?action=login");
    exit;
}

///

$campaignController = new CampaignController($pdo);
$importController = new ImportController($pdo);

switch($action)
{
    case 'list':
        $campaignController->viewCampaigns();
        break;
    
    case 'import':
        $campaignController->doImport();
        break;

    case 'delete':
        $campaignController->delete();
        break;

    case 'history':
        $importController->viewImportHistory();
        break;

    case 'search':
        $campaignController->search();
        break;

    default:
        $campaignController->index();
        break;

}
