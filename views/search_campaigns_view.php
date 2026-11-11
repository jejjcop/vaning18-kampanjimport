<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Search campaigns</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f9f9f9; color: #333; }
        h1 { text-align: center; margin-bottom: 30px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: space-between; }
        .card img { max-width: 100%; height: 160px; object-fit: contain; margin-bottom: 15px; background: #eee; border-radius: 4px; }
        .card h3 { margin: 0 0 10px 0; font-size: 1.1rem; }
        .card p { font-size: 0.9rem; color: #666; flex-grow: 1; }
        .price-tag { font-weight: bold; color: #27ae60; margin-top: 10px; }
        .discount { background: #e74c3c; color: white; padding: 2px 6px; font-size: 0.8rem; border-radius: 4px; display: inline-block; margin-bottom: 5px; }
        .btn-back { display: inline-block; margin-bottom: 20px; background: #7f8c8d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
        .btn-back:hover { background: #707b7c; }
        .error { background: #ffdddd; color: #a00; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { color: #27ae60; background: #e8f8f5; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-weight:600; }

        .search-container 
        {
            width: 100%;
            max-width: 450px;
            margin-left:auto;
            margin-right:auto;
        }

        .search-form 
        {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-family: sans-serif;
        }

        /* Box shadow and border when you click into the search box */
        .search-form:focus-within 
        {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .search-input 
        {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px 14px;
            font-size: 14px;
            color: #1f2937;
            background: transparent;
            font-family: sans-serif;
        }

        .search-input::placeholder 
        {
            color: #9ca3af;
        }

        .search-btn 
        {
            display: flex;
            align-items: center;
            gap: 6px;
            background-color: #4e4064;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-family: sans-serif !important;
        }

        .search-btn:hover 
        {
            background: rgba(78, 64, 100, 0.9);
        }

        .search-query
        {
            text-align:center;
            margin-top:20px;
            margin-bottom:20px;
        }

    </style>
</head>
<body>

    <a href="index.php" class="btn-back">&larr; Back to start page</a>
    <h1>Search campaigns (imported from API)</h1>

    <div class="search-container">
        <form action="" method="GET" class="search-form">
            <input type="hidden" name="action" value="search">
            <input type="search" name="q" placeholder="Search campaigns..." class="search-input" required>
            <button type="submit" class="search-btn">
            <span>Search</span>
            </button>
        </form>
    </div>

    <?php if (isset($_GET['q'])): ?>
        <div class="search-query">Search results for: <?= htmlspecialchars($_GET['q']) ?></div>
    <?php endif; ?>
    
    <div class="grid">
        <?php foreach ($campaigns as $camp): ?>
            <div class="card">
                <?php if (!empty($camp['thumbnail'])): ?>
                    <img src="<?= htmlspecialchars($camp['thumbnail']) ?>" alt="<?= htmlspecialchars($camp['title']) ?>">
                <?php endif; ?>
                
                <div>
                    <h3><?= htmlspecialchars($camp['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($camp['description'], 0, 90)) ?>...</p>
                </div>
                <div>
                    <a href="index.php?action=delete&id=<?= $camp['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to remove this campaign?');">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
