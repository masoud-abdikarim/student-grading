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

$result_query = "SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$student_enroll'";
$result_res = mysqli_query($conn, $result_query);
$marks_data = mysqli_fetch_assoc($result_res);

$student_query = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.enroll='$student_enroll'";
$student_data = mysqli_fetch_assoc(mysqli_query($conn, $student_query));

$exam_query = "SELECT * FROM exam WHERE id='$exam_id'";
$exam_info = mysqli_fetch_assoc(mysqli_query($conn, $exam_query));

function getGrade($marks) {
    if ($marks >= 90) return ["grade" => "A+", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 80) return ["grade" => "A", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 70) return ["grade" => "B+", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 60) return ["grade" => "B", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 50) return ["grade" => "C", "pass" => "Pass", "color" => "#00b894"];
    if ($marks >= 40) return ["grade" => "P", "pass" => "Pass", "color" => "#fdcb6e"];
    return ["grade" => "F", "pass" => "Fail", "color" => "#d63031"];
}

$subjects = [
    "sub1" => "English",
    "sub2" => "Science",
    "sub3" => "Hindi",
    "sub4" => "Maths",
    "sub5" => "Social Science",
    "sub6" => "Sanskrit",
    "sub7" => "Computer"
];

$total_marks = 0;
foreach($subjects as $key => $name) {
    $total_marks += ($marks_data[$key] ?? 0);
}
$percentage = number_format(($total_marks / 7), 2);
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
                <h1>Detailed Marks Report</h1>
                <p style="color: #636e72;">Exam: <strong><?php echo $exam_info['examname']; ?></strong> | Year: <strong><?php echo $exam_info['year']; ?></strong></p>
            </div>
            <a href="view_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to List</a>
        </div>

        <div style="background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 40px; border-left: 5px solid #6c5ce7;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <p><strong>Student Name:</strong> <?php echo $student_data['firstname'] . " " . $student_data['lastname']; ?></p>
                <p><strong>Enrollment No:</strong> <?php echo $student_data['enroll']; ?></p>
                <p><strong>Class:</strong> <?php echo $student_data['class_name']; ?></p>
                <p><strong>Overall Status:</strong> <span style="color: <?php echo ($percentage >= 40) ? '#00b894' : '#d63031'; ?>; font-weight: 700;"><?php echo ($percentage >= 40) ? 'PASSED' : 'FAILED'; ?></span></p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Grade</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($subjects as $key => $name) { 
                        $m = $marks_data[$key] ?? 0;
                        $g = getGrade($m);
                    ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $name; ?></td>
                        <td><?php echo $m; ?> / 100</td>
                        <td><span style="font-weight: 700; color: <?php echo $g['color']; ?>;"><?php echo $g['grade']; ?></span></td>
                        <td><span style="background: <?php echo $g['color']; ?>15; color: <?php echo $g['color']; ?>; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?php echo $g['pass']; ?></span></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr style="background: #fafafa;">
                        <td style="font-weight: 700; padding: 20px;">TOTAL PERFORMANCE</td>
                        <td style="font-weight: 700; padding: 20px;"><?php echo $total_marks; ?> / 700</td>
                        <td colspan="2" style="font-weight: 700; padding: 20px; text-align: right; color: #6c5ce7; font-size: 1.2rem;">Percentage: <?php echo $percentage; ?>%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>