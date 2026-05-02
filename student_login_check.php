<?php
error_reporting(0);
session_start();

    $host="localhost";
    $user="root";
    $password="";
    $db="sgs";

    $conn=mysqli_connect($host,$user,$password,$db);

    if($conn===false)
    {
        die("connection error");
    }
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            $email = $_POST['email'];

            $pass = $_POST['password'];

            $sql="SELECT * FROM studentlist where email='".$email."' AND password='".$pass."'";

            $result=mysqli_query($conn,$sql);

            $row=mysqli_fetch_array($result);
            if($row["usertype"]=="student")
            {  
                $_SESSION["username"]=$row["enroll"];

               // $_SESSION['usertype']="student";

                header("location:studenthome.php");
            }
            elseif($row["usertype"]=="admin")
            {
                $_SESSION["username"]=$row["enroll"];

                //$_SESSION['usertype']="admin";

                header("location:adminhome.php");
            }
            else
            {   
                session_start();
                $message="Email or password do not match";
                $_SESSION['loginMessage']=$message;
                header("location:student_login.php");
            }
        }

?>