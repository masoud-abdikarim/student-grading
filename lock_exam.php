<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['usertype'] == 'student') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

if(isset($_GET['context_id'])) {
    $context_id = $_GET['context_id'];
    $sql = "UPDATE exam_context SET is_locked=1 WHERE id='$context_id'";
    if(mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Results locked successfully!";
    } else {
        $_SESSION['message'] = "Failed to lock results.";
    }
}

if($_SESSION['usertype'] == 'admin') {
    header("location:admin_view_result.php");
} else {
    header("location:add_result.php");
}
?>
