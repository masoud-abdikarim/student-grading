<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['usertype'] == 'student') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

if(isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];
    $sql = "UPDATE exam SET is_locked=1 WHERE id='$exam_id'";
    if(mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Exam results locked successfully!";
    } else {
        $_SESSION['message'] = "Failed to lock exam.";
    }
}

if($_SESSION['usertype'] == 'admin') {
    header("location:admin_view_result.php");
} else {
    header("location:add_result.php");
}
?>
