<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif(isset($_SESSION['usertype']) && $_SESSION['usertype']=='student') {
    header("location:index.php");
    exit();
}


$host="localhost";
$user="root";
$password="";
$db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

// Fetch Stats
$student_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM studentlist"));
$teacher_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM teacherlist"));
$class_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM classes"));
$exam_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM exam"));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard | SGS Academy</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <h1>Admin Dashboard Overview</h1>
        
        <div class="card-grid">
            <div class="card blue">
                <h3>Total Students</h3>
                <div class="value"><?php echo $student_count; ?></div>
            </div>
            <div class="card green">
                <h3>Total Teachers</h3>
                <div class="value"><?php echo $teacher_count; ?></div>
            </div>
            <div class="card orange">
                <h3>Total Classes</h3>
                <div class="value"><?php echo $class_count; ?></div>
            </div>
            <div class="card red">
                <h3>Total Exams</h3>
                <div class="value"><?php echo $exam_count; ?></div>
            </div>
        </div>

        <h2>Assignment Overview</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Assignment Name</th>
                    <th>Due Date</th>
                    <th>Responses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT a.*, (SELECT COUNT(*) FROM assignment_responses WHERE assignment_id = a.id) as response_count FROM assignment a ORDER BY a.subject";
                $result = mysqli_query($conn, $sql);
                while($info = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $info['subject']; ?></td>
                    <td><?php echo $info['assignment']; ?></td>
                    <td><?php echo $info['duedate']; ?></td>
                    <td><span style="font-weight: bold; color: #6c5ce7;"><?php echo $info['response_count']; ?></span></td>
                    <td>
                        <a href="Assignment/<?php echo $info['file']; ?>" class="table-btn table-btn-update">View File</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>