<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif(isset($_SESSION['usertype']) && $_SESSION['usertype']=='admin') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$context_id = $_GET['context_id'] ?? '';
$student_enroll = $_SESSION['username'];

if(!$context_id) {
    header("location:view_result.php");
    exit();
}

// Fetch context info
$ctx_query = "SELECT ec.*, e.type_name, e.category, c.class_name 
              FROM exam_context ec 
              JOIN exam e ON ec.exam_id = e.id 
              JOIN classes c ON ec.class_id = c.id 
              WHERE ec.id='$context_id'";
$ctx_info = mysqli_fetch_assoc(mysqli_query($conn, $ctx_query));

// Fetch specific student result for this context
$result_query = "SELECT * FROM results WHERE context_id='$context_id' AND enroll='$student_enroll'";
$marks_data = mysqli_fetch_assoc(mysqli_query($conn, $result_query));

$student_query = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.enroll='$student_enroll'";
$student_data = mysqli_fetch_assoc(mysqli_query($conn, $student_query));

$pass_mark = $ctx_info['pass_mark'] ?? 50;

function getGrade($marks, $pm) {
    if ($marks >= 90) return ["grade" => "A+", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 80) return ["grade" => "A", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 70) return ["grade" => "B+", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 60) return ["grade" => "B", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= $pm) return ["grade" => "C", "pass" => "Pass", "color" => "#00b894"];
    return ["grade" => "F", "pass" => "Fail", "color" => "#d63031"];
}

$m = $marks_data['marks'] ?? 0;
$g = getGrade($m, $pass_mark);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grade Report | SGS</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>
    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Subject Grade Report</h1>
                <p style="color: #636e72;">
                    Exam: <strong><?php echo $ctx_info['type_name']; ?></strong> | 
                    Year: <strong><?php echo $ctx_info['year']; ?></strong> | 
                    Subject: <strong style="color: #6c5ce7;"><?php echo $ctx_info['subject']; ?></strong>
                </p>
            </div>
            <a href="view_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to List</a>
        </div>

        <div style="background: #fff; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: center; gap: 50px; align-items: center;">
                <div>
                    <p style="color: #636e72; font-size: 0.9rem; margin-bottom: 5px;">Marks Obtained</p>
                    <h1 style="font-size: 3.5rem; margin: 0;"><?php echo $m; ?><span style="font-size: 1.2rem; color: #b2bec3;">/100</span></h1>
                    <p style="font-size: 0.8rem; color: #636e72;">Target Pass Mark: <?php echo $pass_mark; ?></p>
                </div>
                <div style="width: 2px; height: 80px; background: #f1f2f6;"></div>
                <div>
                    <p style="color: #636e72; font-size: 0.9rem; margin-bottom: 5px;">Grade</p>
                    <h1 style="font-size: 3.5rem; margin: 0; color: <?php echo $g['color']; ?>;"><?php echo $g['grade']; ?></h1>
                </div>
                <div style="width: 2px; height: 80px; background: #f1f2f6;"></div>
                <div>
                    <p style="color: #636e72; font-size: 0.9rem; margin-bottom: 5px;">Status</p>
                    <span style="display: inline-block; padding: 10px 25px; border-radius: 30px; background: <?php echo $g['color']; ?>15; color: <?php echo $g['color']; ?>; font-weight: 700; font-size: 1.2rem;"><?php echo strtoupper($g['pass']); ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>