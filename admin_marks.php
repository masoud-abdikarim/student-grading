<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
} elseif($_SESSION['usertype']=='student') {
    header("location:index.php");
}

$host="localhost";
$user="root";
$password="";
$db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$exam_id = $_GET['exam_id'];
$sql = "SELECT r.*, s.firstname, s.lastname FROM results r JOIN studentlist s ON r.enroll = s.enroll WHERE r.exam_id='$exam_id' ORDER BY r.enroll";
$result = mysqli_query($conn, $sql);

$exam_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM exam WHERE id='$exam_id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Results | Admin Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Exam Performance</h1>
                <p style="color: #636e72;">Exam: <strong><?php echo $exam_info['examname']; ?></strong> | Year: <strong><?php echo $exam_info['year']; ?></strong></p>
            </div>
            <a href="admin_view_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to List</a>
        </div>

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Enrollment No.</th>
                        <th>Student Name</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 700; color: #6c5ce7;"><?php echo $info['enroll']; ?></td>
                        <td><?php echo $info['firstname'] . " " . $info['lastname']; ?></td>
                        <td style="text-align: center;">
                            <a href="admin_view_marks.php?exam_id=<?php echo $exam_id; ?>&student_id=<?php echo $info['enroll']; ?>" class="table-btn table-btn-update">View Full Marks</a>
                            <a href="delete_marks.php?exam_id=<?php echo $exam_id; ?>&student_id=<?php echo $info['id']; ?>" class="table-btn table-btn-delete" onClick="return confirm('Permanently delete these marks?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
