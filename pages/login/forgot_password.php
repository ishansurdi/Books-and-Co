<!-- forgot_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/PBL_SourceCode/css/styles.css">
</head>
<body>
    <div class="container">
        <form action="reset_password.php" method="POST">
            <h1>Reset Password</h1>
            <input type="email" name="email" placeholder="Enter your email" required />
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
