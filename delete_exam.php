<?php

session_start();
	$host="localhost";
    $user="root";
    $password="";
    $db="sgs";

    $conn=mysqli_connect($host,$user,$password,$db);

    if($_GET['exam_id'])
    {
    	$exam_id=$_GET['exam_id'];

    	$sql="DELETE FROM exam WHERE id='$exam_id'";
        $sql2="DELETE FROM results WHERE exam_id='$exam_id'";

    	$result=mysqli_query($conn,$sql);
        $result2=mysqli_query($conn,$sql2);

    	if($result)
    	{	
    		$_SESSION['message']='Exam Data is Deleted Successfully';
    		header("location:add_result.php");
    	}
	}
?>