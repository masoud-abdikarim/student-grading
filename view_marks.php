<?php
session_start();
error_reporting(E_ALL);

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

$exam_id = $_GET['exam_id'];
$student_enroll = $_SESSION['username'];

$exam_query = "SELECT e.*, c.class_name FROM exam e JOIN classes c ON e.class_id = c.id WHERE e.id='$exam_id'";
$exam_info = mysqli_fetch_assoc(mysqli_query($conn, $exam_query));
$exam_subject = $exam_info['subject'];

$result_query = "SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$student_enroll' AND subject='$exam_subject'";
$result_res = mysqli_query($conn, $result_query);
$marks_data = mysqli_fetch_assoc($result_res);

$student_query = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.enroll='$student_enroll'";
$student_data = mysqli_fetch_assoc(mysqli_query($conn, $student_query));

function getGrade($marks) {
    if ($marks >= 90) return ["grade" => "A+", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 80) return ["grade" => "A", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 70) return ["grade" => "B+", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 60) return ["grade" => "B", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 50) return ["grade" => "C", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 40) return ["grade" => "P", "pass" => "Pass", "color" => "#fdcb6e"];
    return ["grade" => "F", "pass" => "Fail", "color" => "#d63031"];
}

$m = $marks_data['marks'] ?? 0;
$g = getGrade($m);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Exam Result | Student Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Subject Performance Report</h1>
                <p style="color: #636e72;">Exam: <strong><?php echo $exam_info['examname']; ?></strong> | Year: <strong><?php echo $exam_info['year']; ?></strong></p>
            </div>
            <a href="view_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to List</a>
        </div>

        <div style="background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 40px; border-left: 5px solid #6c5ce7;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <p><strong>Student Name:</strong> <?php echo $student_data['firstname'] . " " . $student_data['lastname']; ?></p>
                <p><strong>Enrollment No:</strong> <?php echo $student_data['enroll']; ?></p>
                <p><strong>Assigned Class:</strong> <?php echo $student_data['class_name']; ?></p>
                <p><strong>Subject:</strong> <span style="color: #6c5ce7; font-weight: 700;"><?php echo $exam_subject; ?></span></p>
            </div>
        </div>

        <div style="background: #fff; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <h2 style="margin-bottom: 20px;">Grade Breakdown</h2>
            <div style="display: flex; justify-content: center; gap: 50px; align-items: center;">
                <div>
                    <p style="color: #636e72; font-size: 0.9rem; margin-bottom: 5px;">Marks Obtained</p>
                    <h1 style="font-size: 3rem; margin: 0;"><?php echo $m; ?><span style="font-size: 1.2rem; color: #b2bec3;">/100</span></h1>
                </div>
                <div style="width: 2px; height: 60px; background: #eee;"></div>
                <div>
                    <p style="color: #636e72; font-size: 0.9rem; margin-bottom: 5px;">Grade</p>
                    <h1 style="font-size: 3rem; margin: 0; color: <?php echo $g['color']; ?>;"><?php echo $g['grade']; ?></h1>
                </div>
                <div style="width: 2px; height: 60px; background: #eee;"></div>
                <div>
                    <p style="color: #636e72; font-size: 0.9rem; margin-bottom: 5px;">Status</p>
                    <span style="display: inline-block; padding: 8px 20px; border-radius: 30px; background: <?php echo $g['color']; ?>15; color: <?php echo $g['color']; ?>; font-weight: 700; font-size: 1.1rem;"><?php echo strtoupper($g['pass']); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>