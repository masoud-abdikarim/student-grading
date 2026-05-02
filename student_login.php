<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | SGS Academy</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

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
            transform: translateX(-5px);
            opacity: 0.8;
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
    <a href="index.php" class="back-btn">
        <span>&#8592; Back to Home</span>
    </a>

    <div class="login-card">
        <h1>Student Login</h1>
        <p>Login to your student account</p>

        <?php
            error_reporting(0);
            session_start();
            $message = $_SESSION['loginMessage'];
            if($message) {
                echo "<div class='error-msg'>$message</div>";
            }
            session_destroy();
        ?>

        <form method="POST" action="student_login_check.php">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="submit-btn">Login to Account</button>
            
            <a href="forget_student.php" class="forgot-pass">Forgot Password?</a>
        </form>

        <div class="footer-text">Student Grading System</div>
    </div>
</body>
</html>