<?php

session_start();
	$host="localhost";
    $user="root";
    $password="";
    $db="sgs";

    $conn=mysqli_connect($host,$user,$password,$db);
    $table=$_GET['assign_id'];

    if($_GET['assign_id'])
    {
    	$assign_id=$_GET['assign_id'];
        $fl=$_GET['response_id'];
        @unlink("responses/".$fl);

    	$sql="DELETE FROM assignment_responses WHERE file='$fl' AND assignment_id='$assign_id'";

    	$result=mysqli_query($conn,$sql);

    	if($result)
    	{	
    		$_SESSION['message']='Response  is Deleted Successfully';
    		header("location:view_responses.php?assign_id={$table}");
    	}
	}
?>