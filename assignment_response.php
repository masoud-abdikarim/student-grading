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

$sql = "SELECT a.*, c.class_name FROM assignment a JOIN classes c ON a.class_id = c.id WHERE a.class_id='$stu_class_id' ORDER BY a.duedate";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Assignments | Student Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>

    <div class="content">
        <h1>My Coursework & Assignments</h1>
        <p style="color: #636e72; margin-bottom: 30px;">Download assignments and upload your completed work before the deadline.</p>

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Assignment Title</th>
                        <th>Due Date</th>
                        <th>Documents</th>
                        <th style="text-align: center;">Your Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $info['subject']; ?></td>
                        <td><?php echo $info['assignment']; ?></td>
                        <td>
                            <span style="color: <?php echo (strtotime($info['duedate']) < time()) ? '#d63031' : '#2d3436'; ?>; font-weight: 600;">
                                <?php echo date('M d, Y', strtotime($info['duedate'])); ?>
                            </span>
                        </td>
                        <td>
                            <a href="assignment/<?php echo $info['file']; ?>" class="table-btn table-btn-update" target="_blank">View File</a>
                        </td>
                        <td style="text-align: center;">
                            <a href="your_response.php?assign_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #f1f2f6; color: #636e72;">Review</a>
                            <a href="upload_response.php?assign_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">Upload Solution</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>