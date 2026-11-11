<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Log in - Campaign Portal</title>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f6f9; color: #333; }
        .box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); width: 100%; max-width: 350px; }
        h1 { margin-top: 0; font-size: 1.5rem; color: #2c3e50; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { display: block; width: 100%; background: #27ae60; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 1rem; }
        .btn:hover { background: #219653; }
        .error { color: #c0392b; background: #fadbd8; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; text-align: center; }
        .info { text-align: center; font-size: 0.8rem; color: #7f8c8d; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="box">
        <h1>Login</h1>

        <?php if (isset($errorMessage)): ?>
            <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form action="index.php?action=login_submit" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>

</body>
</html>
