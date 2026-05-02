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

$exam_id = $_GET['exam_id'];

if(isset($_POST['add'])) {
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
        $s1 = empty($sub1[$i]) ? 0 : $sub1[$i];
        $s2 = empty($sub2[$i]) ? 0 : $sub2[$i];
        $s3 = empty($sub3[$i]) ? 0 : $sub3[$i];
        $s4 = empty($sub4[$i]) ? 0 : $sub4[$i];
        $s5 = empty($sub5[$i]) ? 0 : $sub5[$i];
        $s6 = empty($sub6[$i]) ? 0 : $sub6[$i];
        $s7 = empty($sub7[$i]) ? 0 : $sub7[$i];

        $check = "SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$e'";
        $res = mysqli_query($conn, $check);
        if(mysqli_num_rows($res) > 0){
            $sql = "UPDATE results SET sub1='$s1', sub2='$s2', sub3='$s3', sub4='$s4', sub5='$s5', sub6='$s6', sub7='$s7' WHERE exam_id='$exam_id' AND enroll='$e'";
        } else {
            $sql = "INSERT INTO results (exam_id, enroll, sub1, sub2, sub3, sub4, sub5, sub6, sub7) VALUES ('$exam_id', '$e', '$s1', '$s2', '$s3', '$s4', '$s5', '$s6', '$s7')";
        }
        mysqli_query($conn, $sql);
    }
    $msg = "Marks Saved Successfully!";
}

$exam_query = "SELECT e.*, c.class_name FROM exam e JOIN classes c ON e.class_id = c.id WHERE e.id='$exam_id'";
$exam_data = mysqli_fetch_assoc(mysqli_query($conn, $exam_query));
$exam_class_id = $exam_data['class_id'];

$students = mysqli_query($conn, "SELECT * FROM studentlist WHERE class_id='$exam_class_id' AND usertype='student' ORDER BY firstname");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Insert Marks | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Insert Exam Marks</h1>
                <p style="color: #636e72;">Exam: <strong><?php echo $exam_data['examname']; ?></strong> | Class: <strong><?php echo $exam_data['class_name']; ?></strong></p>
            </div>
            <a href="add_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to Exams</a>
        </div>

        <?php if(isset($msg)) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>$msg</div>";
        } ?>

        <form method="POST" action="#">
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Enrollment</th>
                            <th>English</th>
                            <th>Science</th>
                            <th>Hindi</th>
                            <th>Maths</th>
                            <th>Social</th>
                            <th>Sanskrit</th>
                            <th>Comp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($s = mysqli_fetch_assoc($students)) { 
                            $e = $s['enroll'];
                            $m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$e'"));
                        ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo $s['firstname'] . ' ' . $s['lastname']; ?></td>
                            <td style="color: #636e72; font-size: 0.9rem;">
                                <input type="hidden" name="enroll[]" value="<?php echo $e; ?>">
                                <?php echo $e; ?>
                            </td>
                            <td><input type="number" name="sub1[]" value="<?php echo $m['sub1'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                            <td><input type="number" name="sub2[]" value="<?php echo $m['sub2'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                            <td><input type="number" name="sub3[]" value="<?php echo $m['sub3'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                            <td><input type="number" name="sub4[]" value="<?php echo $m['sub4'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                            <td><input type="number" name="sub5[]" value="<?php echo $m['sub5'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                            <td><input type="number" name="sub6[]" value="<?php echo $m['sub6'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                            <td><input type="number" name="sub7[]" value="<?php echo $m['sub7'] ?? ''; ?>" min="0" max="100" style="width: 60px; margin-bottom: 0;"></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 30px; text-align: center;">
                <button type="submit" name="add" class="submit-btn" style="cursor: pointer; border: none; width: auto; padding: 15px 40px; font-size: 1.1rem;">Save All Marks</button>
            </div>
        </form>
    </div>
</body>
</html>