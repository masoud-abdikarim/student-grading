<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
} elseif($_SESSION['usertype']=='student') {
    header("location:index.php");
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$context_id = $_GET['context_id'];
$sql = "SELECT r.*, s.firstname, s.lastname FROM results r JOIN studentlist s ON r.enroll = s.enroll WHERE r.context_id='$context_id' ORDER BY r.enroll";
$result = mysqli_query($conn, $sql);

$ctx_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ec.*, e.type_name FROM exam_context ec JOIN exam e ON ec.exam_id = e.id WHERE ec.id='$context_id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detailed Results | SGS</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Subject Performance View</h1>
                <p style="color: #636e72;">
                    Type: <strong><?php echo $ctx_info['type_name']; ?></strong> | 
                    Subject: <strong style="color: #6c5ce7;"><?php echo $ctx_info['subject']; ?></strong> | 
                    Year: <strong><?php echo $ctx_info['year']; ?></strong>
                </p>
            </div>
            <a href="admin_view_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to List</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Enrollment No.</th>
                    <th>Student Name</th>
                    <th style="text-align: center;">Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php while($info = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td style="font-weight: 700; color: #6c5ce7;"><?php echo $info['enroll']; ?></td>
                    <td><?php echo $info['firstname'] . " " . $info['lastname']; ?></td>
                    <td style="text-align: center; font-weight: 800;"><?php echo $info['marks']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
