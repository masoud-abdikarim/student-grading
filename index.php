<?php
	error_reporting(0);
	session_start();
	session_destroy();

	if($_SESSION['message'])
	{
		$message=$_SESSION['message'];
		echo "<script type='text/javascript'>
		alert('$message')
		</script>";
	}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Grading System | Welcome</title>
	<link rel="stylesheet" type="text/css" href="css/index-style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">SGS Academy</div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Welcome to Student Grading System</h1>
            <p>A comprehensive platform for managing academic excellence. Streamline student data, exams, assignments, and results with ease.</p>
            <div class="hero-btns">
                <a href="login.php" class="btn btn-admin">Admin Login</a>
                <a href="teacher_login.php" class="btn btn-teacher">Teacher Login</a>
                <a href="student_login.php" class="btn btn-student">Student Login</a>
                <a href="feedback_form.php" class="btn btn-feedback">Feedback</a>
                <a href="#" class="btn btn-credit">Student Credit System</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1523050853063-bd8012fec21b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="School Campus">
        </div>
    </header>

    <section class="features">
        <h2 class="section-title">Core Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">👨‍🎓</div>
                <h3>Manage Students</h3>
                <p>Efficiently organize student profiles, enrollments, and academic progress.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h3>Manage Exams</h3>
                <p>Schedule exams, track performance, and maintain academic standards.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Track Results</h3>
                <p>Generate detailed reports and monitor student achievements in real-time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📂</div>
                <h3>Assign Assignments</h3>
                <p>Seamlessly distribute and collect assignments across all classes.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <h3>Student Grading System</h3>
            <p>Empowering educators and students through digital excellence.</p>
            <div class="contact-info">
                <span>Email: contact@sgs-academy.com</span> | 
                <span>Phone: +1 234 567 890</span>
            </div>
            <p class="copyright">&copy; <?php echo date("Y"); ?> SGS Academy. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>