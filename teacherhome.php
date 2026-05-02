<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("location:index.php");
} elseif($_SESSION['usertype']=='admin') {
    header("location:index.php");
}

$host="localhost";
$user="root";
$password="";
$db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$username = $_SESSION['username'];
$sql = "SELECT * FROM teacherlist WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Teacher Dashboard | SGS Academy</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <h1>Welcome, <?php echo "{$info['firstname']} {$info['lastname']}"; ?></h1>
        <p style="color: #636e72; margin-bottom: 30px;">Manage your classes, exams, and student assignments efficiently.</p>
        
        <div class="card-grid">
            <a href="user_view_student.php" style="text-decoration: none;">
                <div class="card blue">
                    <div class="feature-icon" style="font-size: 2rem; margin-bottom: 10px;">👨‍🎓</div>
                    <h3>View Students</h3>
                    <p>Check your class rosters</p>
                </div>
            </a>
            <a href="add_exam.php" style="text-decoration: none;">
                <div class="card green">
                    <div class="feature-icon" style="font-size: 2rem; margin-bottom: 10px;">📝</div>
                    <h3>Add Exam</h3>
                    <p>Create new assessments</p>
                </div>
            </a>
            <a href="add_result.php" style="text-decoration: none;">
                <div class="card orange">
                    <div class="feature-icon" style="font-size: 2rem; margin-bottom: 10px;">📊</div>
                    <h3>Add Result</h3>
                    <p>Input student marks</p>
                </div>
            </a>
            <a href="add_assignment.php" style="text-decoration: none;">
                <div class="card red">
                    <div class="feature-icon" style="font-size: 2rem; margin-bottom: 10px;">📂</div>
                    <h3>Add Assignment</h3>
                    <p>Distribute tasks</p>
                </div>
            </a>
        </div>

        <div style="background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-top: 20px;">
            <h2>Quick Overview</h2>
            <p>Use the sidebar to navigate through more detailed management options like viewing assignment responses and managing your profile.</p>
        </div>
    </div>
</body>
</html>