<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Import history</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; color: #333; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        h1 { margin-top: 0; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; color: #2c3e50; }
        .btn-back { display: inline-block; margin-bottom: 20px; background: #7f8c8d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
        .btn-back:hover { background: #707b7c; }
    </style>
</head>
<body>

    <div class="container">
        <a href="index.php" class="btn-back">&larr; Back to start page</a>
        <h1>Earlier imports</h1>

        <?php if (empty($imports)): ?>
            <p>No imports have been made yet</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Total</th>
                        <th>New</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($imports as $imp): ?>
                        <tr>
                            <td><?= htmlspecialchars($imp['id']) ?></td>
                            <td><?= htmlspecialchars($imp['imported_at']) ?></td>
                            <td><?= htmlspecialchars($imp['items_count']) ?></td>
                            <td><?= htmlspecialchars($imp['new_count'] ?? 0) ?></td>
                            <td><?= htmlspecialchars($imp['updated_count'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
