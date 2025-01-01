<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Books & Co</title>
    <link rel="icon" href="../images/logofile/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/slider.css">
    <link rel="stylesheet" href="../../css/loginpage.css"> 
</head>
<body>
    <header>
        <nav>
            <a href="../index.html">
            <div class="logo">
                <img src="../../images/logofile/png/logo-no-background.png" alt="Site Logo">
            </div></a>
            <div class="menu-icon" id="menu-icon">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </nav>
        <div class="overlay" id="overlay"></div>
        <div class="side-menu" id="side-menu">
            <div class="close-btn" id="close-btn">&times;</div>
            <div class="menu-content">
                <ul>
                    <li><a href="#">Login / Sign-Up</a></li>
                    <li><a href="#">Apply for Membership</a></li>
                </ul>
                <div class="social-media">
                    <a href="#"><img src="../../images/facebook.svg" alt="Facebook"></a>
                    <a href="#"><img src="../../images/x.svg" alt="Twitter"></a>
                    <a href="#"><img src="../../images/instagram.svg" alt="Instagram"></a>
                </div>
                <div class="footer">
                    <p>&copy; 2024 PBL Group - 13. All rights reserved.</p>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="form-container sign-in-container">
            <form action="auth.php" method="POST" id="loginForm">
                <h1>Sign in</h1>
                <input type="text" name="MID" id="MID" placeholder="Membership ID" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <br><br>
                <a href="forgot_password.php">Forget Password?</a>
            </form>
        </div>

        <div class="overlay-container-login">
            <div class="overlay-login">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us, please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <a href="../index.html">
            <div class="footer-logo">
                <img src="../../images/logofile/png/logo-no-background.png" alt="Logo">
            </div></a>
            <div class="footer-maps">
                <h4>Maps Location</h4>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3783.24647107663!2d73.81253627470771!3d18.517760969261065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2bfb9e53a05f9%3A0x2be5e8da02be693e!2sMIT%20World%20Peace%20University%20(MIT-WPU)!5e0!3m2!1sen!2sin!4v1723285471888!5m2!1sen!2sin" 
                        width="300" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <p>Address Line 1<br>Address Line 2</p>
                <p>Contact: +123 456 7890</p>
            </div>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Disclaimer</a>
                <a href="pages/credits.html" target="_blank">Credits</a>
            </div>
        </div>
        <div class="social-media">
            <a href="#"><img src="../../images/facebook.svg" alt="Facebook"></a>
            <a href="#"><img src="../../images/x.svg" alt="Twitter"></a>
            <a href="#"><img src="../../images/instagram.svg" alt="Instagram"></a>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 PBL Group - 13. All rights reserved.</p>
        </div>
    </footer>

    <script src="/PBL_SourceCode/js/slider.js"></script>
    <script>
        // Check session errors and alert user
        window.onload = function() {
            <?php if (!empty($_SESSION['account_error'])): ?>
                alert("<?php echo $_SESSION['account_error']; ?>");
                <?php unset($_SESSION['account_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['password_error'])): ?>
                alert("<?php echo $_SESSION['password_error']; ?>");
                <?php unset($_SESSION['password_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['mid_error'])): ?>
                alert("<?php echo $_SESSION['mid_error']; ?>");
                <?php unset($_SESSION['mid_error']); ?>
            <?php endif; ?>
        };

        // Prevent form submission if there are session errors
        document.getElementById('loginForm').onsubmit = function(e) {
            <?php if (!empty($_SESSION['account_error']) || !empty($_SESSION['password_error']) || !empty($_SESSION['mid_error'])): ?>
                e.preventDefault(); // Prevent form submission
            <?php endif; ?>
        };
    </script>
</body>
</html>
