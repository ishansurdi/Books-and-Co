<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin_login.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../images/logofile/png/logo-no-background.png" alt="Logo" class="logo">
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <form action="admin_login_process.php" method="POST">
                <div class="input-group">
                    <label for="aid">Admin ID (AID):</label>
                    <input type="text" id="aid" name="aid" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <p class="note">Note: Not for Members</p>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
