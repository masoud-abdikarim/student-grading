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
$sql = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.enroll='$username'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

// Summary Stats for Student
$exam_count = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT examname FROM results WHERE enroll='$username'"));
$latest_result = mysqli_query($conn, "SELECT marks FROM results WHERE enroll='$username' ORDER BY id DESC LIMIT 1");
$latest_val = mysqli_fetch_assoc($latest_result)['marks'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Dashboard | SGS Academy</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>

    <div class="content">
        <h1>Welcome, <?php echo "{$info['firstname']} {$info['lastname']}"; ?></h1>
        
        <div style="background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 40px; display: flex; align-items: center; gap: 30px;">
            <div style="font-size: 4rem;">🎓</div>
            <div>
                <h2 style="margin-bottom: 5px;"><?php echo "{$info['firstname']} {$info['lastname']}"; ?></h2>
                <p style="color: #636e72;"><strong>Class:</strong> <?php echo $info['class_name'] ?? 'Not Assigned'; ?></p>
                <p style="color: #636e72;"><strong>Enrollment No:</strong> <?php echo $info['enroll']; ?></p>
            </div>
        </div>

        <div class="card-grid">
            <div class="card blue">
                <h3>Exams Taken</h3>
                <div class="value"><?php echo $exam_count; ?></div>
            </div>
            <div class="card green">
                <h3>Latest Result</h3>
                <div class="value"><?php echo $latest_val; ?></div>
            </div>
            <div class="card orange">
                <h3>Contact</h3>
                <p style="margin-top: 10px;"><strong>Email:</strong> <?php echo $info['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $info['phone']; ?></p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); padding: 30px; border-radius: 20px; color: #fff; box-shadow: 0 10px 30px rgba(108, 92, 231, 0.2);">
            <h3>Academic Tip</h3>
            <p>Regularly check "My Results" and "My Assignments" to stay updated with your academic progress and deadlines.</p>
        </div>
    </div>
</body>
</html>