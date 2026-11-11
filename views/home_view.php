<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Campaign portal</title>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f6f9; color: #333; }
        .box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; max-width: 450px; width: 100%; }
        h1 { margin-top: 0; font-size: 1.5rem; color: #2c3e50; }
        .counter { font-size: 2.5rem; font-weight: bold; color: #2980b9; margin: 15px 0; }
        .btn-group { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .btn { display: block; background: #27ae60; color: white; padding: 12px; text-decoration: none; border-radius: 4px; font-weight: bold; transition: background 0.2s; }
        .btn:hover { background: #219653; }
        .btn-secondary { background: #2980b9; }
        .btn-secondary:hover { background: #2471a3; }
        .btn-import { background: #d35400; }
        .btn-import:hover { background: #ba4a00; }
        .success { color: #27ae60; background: #e8f8f5; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; text-align: left; line-height: 1.5; }
        .error { color: #c0392b; background: #fadbd8; padding: 10px; border-radius: 4px; margin-bottom: 15px; }

    /* Animation when import-button is clicked */
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(0.96); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    .btn-import.loading {
        animation: pulse 0.6s infinite ease-in-out;
        background: #a04000;
        pointer-events: none;
    }
    </style>
</head>
<body>

    <div class="box">
        <h1>Campaign Overview</h1>

        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] == 1): ?>
                <div class="success">
                    <strong>Import completed!</strong><br>
                    Total: <?= htmlspecialchars($_GET['total']) ?><br>
                    New: <?= htmlspecialchars($_GET['new']) ?><br>
                    Updated: <?= htmlspecialchars($_GET['updated']) ?>
                </div>
        <?php endif; ?>
            <?php if ($_GET['success'] == 0): ?>
                <div class="error">
                    <strong>Import failed!</strong><br>
                    <?= htmlspecialchars($_GET['message']) ?><br>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        <?php if (isset($errorMessage)): ?>
            <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php else: ?>
            <p>Total saved campaigns:</p>
            <div class="counter"><?= $totalNrOfCampaigns ?></div>
            
            <div class="btn-group">
                <a href="index.php?action=import" class="btn btn-import" id="importBtn">Do import from API</a>
                
                <a href="index.php?action=list" class="btn btn-secondary">View all campaigns</a>
                
                <a href="index.php?action=history" class="btn">View import history</a>

                <a href="index.php?action=logout" style="color: #c0392b; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-top: 15px;">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
            </div>
        <?php endif; ?>
    </div>

<script>
// Catch the click on the import-button
document.getElementById('importBtn').addEventListener('click', function(e) {
    
    // Add animation and change button text
    this.classList.add('loading');
    this.textContent = 'Fetching campaigns...';
});
</script>

</body>
</html>
