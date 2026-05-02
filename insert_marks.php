<?php
session_start();
error_reporting(0);
    if(!isset($_SESSION['username']))
    {
        header("location:index.php");
    }
    elseif($_SESSION['usertype']=='admin')
    {
        header("location:index.php");
    }

    $host="localhost";
    $user="root";
    $password="";
    $db="sgs";

    $conn=mysqli_connect($host,$user,$password,$db);

    $exam_id=$_GET['exam_id'];

    if(isset($_POST['add']))
    {
        $enrolls = $_POST['enroll'];
        $sub1 = $_POST['sub1'];
        $sub2 = $_POST['sub2'];
        $sub3 = $_POST['sub3'];
        $sub4 = $_POST['sub4'];
        $sub5 = $_POST['sub5'];
        $sub6 = $_POST['sub6'];
        $sub7 = $_POST['sub7'];

        for($i=0; $i<count($enrolls); $i++){
            $e = $enrolls[$i];
            // Treat empty inputs as 0
            $s1 = empty($sub1[$i]) ? 0 : $sub1[$i];
            $s2 = empty($sub2[$i]) ? 0 : $sub2[$i];
            $s3 = empty($sub3[$i]) ? 0 : $sub3[$i];
            $s4 = empty($sub4[$i]) ? 0 : $sub4[$i];
            $s5 = empty($sub5[$i]) ? 0 : $sub5[$i];
            $s6 = empty($sub6[$i]) ? 0 : $sub6[$i];
            $s7 = empty($sub7[$i]) ? 0 : $sub7[$i];

            // Check if exist
            $check="SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$e'";
            $check_marks=mysqli_query($conn,$check);
            if(mysqli_num_rows($check_marks) > 0){
                // Update
                $sql="UPDATE results SET sub1='$s1', sub2='$s2', sub3='$s3', sub4='$s4', sub5='$s5', sub6='$s6', sub7='$s7' WHERE exam_id='$exam_id' AND enroll='$e'";
                mysqli_query($conn,$sql);
            } else {
                // Insert
                $sql="INSERT INTO results (exam_id, enroll, sub1, sub2, sub3, sub4, sub5, sub6, sub7) VALUES ('$exam_id', '$e', '$s1', '$s2', '$s3', '$s4', '$s5', '$s6', '$s7')";
                mysqli_query($conn,$sql);
            }
        }

        echo '<script language="javascript">';
        echo 'alert("Marks Saved Successfully")';
        echo '</script>';
    }

    $students_query="SELECT * FROM studentlist";
    $students_result=mysqli_query($conn,$students_query);

?>
<html>
<head>
    <title>Teacher panel</title>
    <link rel="stylesheet" type="text/css" href="css/admin-style.css">
    <style type="text/css">
        table { border-radius: 10px; margin-top: 20px; border-collapse: collapse; width: 90%; }
        th.table_th { padding: 10px; font-size: 16px; background-color: #de3163; border-radius: 10px; color: white; }
        td.table_td { padding: 10px; background-color: #fcf4a3; text-align: center; }
        input[type="number"], input[type="text"] { width: 50px; text-align: center; }
        .btn { padding: 10px 20px; background: #de3163; color: white; border: none; border-radius: 30px; cursor: pointer; font-size: 16px; margin-top: 20px; }
        .btn:hover { background: #b0254c; }
    </style>
    <!----------------------------------------sidebar code------------------------------------->
    <?php include 'teacher_sidebar.php'; ?>
    <!------------------------------------------------------------------------------------------>
</head>
<body>
    <div class="content">
        <center>
            <h1>Add/Edit Marks for Students</h1>
            <form method="POST" action="#">
                <table border="1">
                    <tr>
                        <th class="table_th">Student Name</th>
                        <th class="table_th">Enroll No.</th>
                        <th class="table_th">English</th>
                        <th class="table_th">Science</th>
                        <th class="table_th">Hindi</th>
                        <th class="table_th">Maths</th>
                        <th class="table_th">Social Science</th>
                        <th class="table_th">Sanskrit</th>
                        <th class="table_th">Computer</th>
                    </tr>
                    <?php
                        while($student = mysqli_fetch_assoc($students_result)){
                            $e = $student['enroll'];
                            $m_query="SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$e'";
                            $m_res=mysqli_query($conn, $m_query);
                            $m_data=mysqli_fetch_assoc($m_res);
                    ?>
                    <tr>
                        <td class="table_td"><?php echo $student['firstname'].' '.$student['lastname']; ?></td>
                        <td class="table_td">
                            <input type="hidden" name="enroll[]" value="<?php echo $e; ?>">
                            <?php echo $e; ?>
                        </td>
                        <td class="table_td"><input type="number" name="sub1[]" value="<?php echo $m_data['sub1']; ?>" min="0" max="100"></td>
                        <td class="table_td"><input type="number" name="sub2[]" value="<?php echo $m_data['sub2']; ?>" min="0" max="100"></td>
                        <td class="table_td"><input type="number" name="sub3[]" value="<?php echo $m_data['sub3']; ?>" min="0" max="100"></td>
                        <td class="table_td"><input type="number" name="sub4[]" value="<?php echo $m_data['sub4']; ?>" min="0" max="100"></td>
                        <td class="table_td"><input type="number" name="sub5[]" value="<?php echo $m_data['sub5']; ?>" min="0" max="100"></td>
                        <td class="table_td"><input type="number" name="sub6[]" value="<?php echo $m_data['sub6']; ?>" min="0" max="100"></td>
                        <td class="table_td"><input type="number" name="sub7[]" value="<?php echo $m_data['sub7']; ?>" min="0" max="100"></td>
                    </tr>
                    <?php } ?>
                </table>
                <br>
                <input type="submit" class="btn" name="add" value="Save Marks">
            </form>
        </center>
    </div>
</body>
</html>