<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("location:index.php");
} elseif($_SESSION['usertype']!='admin') {
    header("location:index.php");
}

$host="localhost";
$user="root";
$password="";
$db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

if(isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    $sql = "DELETE FROM classes WHERE id='$class_id'";
    if(mysqli_query($conn, $sql)) {
        $_SESSION['message']='Class Deleted Successfully';
    } else {
        $_SESSION['message']='Failed to Delete Class';
    }
    header("location:view_classes.php");
}
?>
