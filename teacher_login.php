<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login | SGS Academy</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        /* Font Awesome for Icons */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
            margin: 0;
        }

        /* Back Arrow Style */
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .back-btn:hover {
            background: #fff;
            color: #667eea;
            transform: translateX(-5px);
        }


        /* Login Card Style */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 400px;
            max-width: 90%;
            text-align: center;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h1 {
            font-size: 2rem;
            color: #2d3436;
            margin-bottom: 0.5rem;
        }

        .login-card p {
            color: #636e72;
            margin-bottom: 2rem;
        }

        .error-msg {
            background: #ffeaa7;
            color: #d63031;
            padding: 0.8rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #fab1a0;
        }

        /* Form Groups */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #eee;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .form-group input:focus {
            border-color: #667eea;
        }

        /* Password Toggle Styles */
        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #636e72;
            font-size: 1.1rem;
            transition: all 0.3s;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #667eea;
        }


        /* Login Button */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .forgot-pass {
            display: block;
            margin-top: 1.5rem;
            text-decoration: none;
            color: #667eea;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .forgot-pass:hover {
            text-decoration: underline;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #b2bec3;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem; }
        }
    </style>
</head>
<body>
    <a href="javascript:void(0)" onclick="goBack()" class="back-btn" title="Go Back">
        <span>&#8592; Back</span>
    </a>


    <div class="login-card">
        <h1>Teacher Login</h1>
        <p>Access your teacher dashboard</p>

        <?php
            error_reporting(0);
            session_start();
            $message = $_SESSION['loginMessage'];
            if($message) {
                echo "<div class='error-msg'>$message</div>";
            }
            session_destroy();
        ?>

        <form method="POST" action="teacher_login_check.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <i class="fa-solid fa-eye toggle-password" id="toggleIcon" onclick="togglePassword('password', 'toggleIcon')"></i>
                </div>
            </div>

            
            <button type="submit" class="submit-btn">Login to Account</button>
            
            <a href="forget.php" class="forgot-pass">Forgot Password?</a>
        </form>

        <div class="footer-text">Student Grading System</div>
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }

    function goBack() {
        if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
            window.history.back();
        } else {
            window.location.href = 'index.php';
        }
    }
    </script>

</body>

</html>