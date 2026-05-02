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

$user = $_SESSION['username'];
$stu_query = "SELECT class_id FROM studentlist WHERE enroll='$user'";
$stu_res = mysqli_query($conn, $stu_query);
$stu_data = mysqli_fetch_assoc($stu_res);
$stu_class_id = $stu_data['class_id'];

$sql = "SELECT e.*, c.class_name FROM exam e JOIN classes c ON e.class_id = c.id WHERE e.class_id='$stu_class_id' ORDER BY e.year DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Exams & Results | Student Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>

    <div class="content">
        <h1>My Exams & Results</h1>
        <p style="color: #636e72; margin-bottom: 30px;">View your assessment performance and upcoming exams.</p>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Exam Name</th>
                        <th>Class</th>
                        <th>Exam Year</th>
                        <th>Exam Type</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $info['examname']; ?></td>
                        <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;"><?php echo $info['class_name']; ?></span></td>
                        <td><?php echo $info['year']; ?></td>
                        <td><?php echo $info['type']; ?></td>
                        <td style="text-align: center;">
                            <a href="view_marks.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">View My Marks</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
