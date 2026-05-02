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

$assign_id = $_GET['assign_id'];
$sql = "SELECT r.*, s.firstname, s.lastname FROM assignment_responses r JOIN studentlist s ON r.enroll = s.enroll WHERE r.assignment_id='$assign_id' ORDER BY r.enroll";
$result = mysqli_query($conn, $sql);

$assign_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM assignment WHERE id='$assign_id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Assignment Submissions | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Student Submissions</h1>
                <p style="color: #636e72;">Assignment: <strong><?php echo $assign_info['assignment']; ?></strong> | Subject: <strong><?php echo $assign_info['subject']; ?></strong></p>
            </div>
            <a href="assign_response.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to Assignments</a>
        </div>

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Enrollment</th>
                        <th>Student Name</th>
                        <th>Submitted Date</th>
                        <th style="text-align: center;">Score</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 700; color: #6c5ce7;"><?php echo $info['enroll']; ?></td>
                        <td><?php echo $info['firstname'] . " " . $info['lastname']; ?></td>
                        <td><?php echo date('M d, Y H:i', strtotime($info['submited'])); ?></td>
                        <td style="text-align: center;">
                            <span style="font-weight: 700; color: <?php echo ($info['marks'] >= 40) ? '#00b894' : '#d63031'; ?>;">
                                <?php echo ($info['marks'] > 0) ? $info['marks'] . ' / 100' : 'Not Graded'; ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="responses/<?php echo $info['file']; ?>" class="table-btn table-btn-update" target="_blank">View File</a>
                            <a href="response_marks.php?assign_id=<?php echo $assign_id; ?>&response_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">Grade</a>
                            <a href="delete_response.php?assign_id=<?php echo $assign_id; ?>&response_id=<?php echo $info['id']; ?>" class="table-btn table-btn-delete" onClick="return confirm('Delete this submission?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
