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

$exam_query = "SELECT e.*, c.class_name FROM exam e JOIN classes c ON e.class_id = c.id WHERE e.id='$exam_id'";
$exam_data = mysqli_fetch_assoc(mysqli_query($conn, $exam_query));
$exam_class_id = $exam_data['class_id'];
$exam_subject = $exam_data['subject'];

if(isset($_POST['add'])) {
    $enrolls = $_POST['enroll'];
    $marks_list = $_POST['marks'];

    for($i=0; $i<count($enrolls); $i++){
        $e = mysqli_real_escape_string($conn, $enrolls[$i]);
        $m = empty($marks_list[$i]) ? 0 : mysqli_real_escape_string($conn, $marks_list[$i]);

        $check = "SELECT * FROM results WHERE exam_id='$exam_id' AND enroll='$e' AND subject='$exam_subject'";
        $res = mysqli_query($conn, $check);
        
        if(mysqli_num_rows($res) > 0){
            $sql = "UPDATE results SET marks='$m' WHERE exam_id='$exam_id' AND enroll='$e' AND subject='$exam_subject'";
        } else {
            $sql = "INSERT INTO results (exam_id, enroll, subject, marks) VALUES ('$exam_id', '$e', '$exam_subject', '$m')";
        }
        mysqli_query($conn, $sql);
    }
    $msg = "Marks for $exam_subject Saved Successfully!";
}

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
                <p style="color: #636e72;">Exam: <strong><?php echo $exam_data['examname']; ?></strong> | Subject: <strong style="color: #6c5ce7;"><?php echo $exam_subject; ?></strong> | Class: <strong><?php echo $exam_data['class_name']; ?></strong></p>
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
                            <th>Student Name</th>
                            <th>Enrollment No</th>
                            <th style="width: 200px; text-align: center;"><?php echo $exam_subject; ?> Marks (Max 100)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($s = mysqli_fetch_assoc($students)) { 
                            $e = $s['enroll'];
                            $m_query = mysqli_query($conn, "SELECT marks FROM results WHERE exam_id='$exam_id' AND enroll='$e' AND subject='$exam_subject'");
                            $m_data = mysqli_fetch_assoc($m_query);
                        ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo $s['firstname'] . ' ' . $s['lastname']; ?></td>
                            <td style="color: #636e72;">
                                <input type="hidden" name="enroll[]" value="<?php echo $e; ?>">
                                <?php echo $e; ?>
                            </td>
                            <td style="text-align: center;">
                                <input type="number" name="marks[]" value="<?php echo $m_data['marks'] ?? ''; ?>" min="0" max="100" placeholder="0" style="width: 100px; margin-bottom: 0; text-align: center; padding: 10px; font-weight: 700;">
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 30px; text-align: center;">
                <button type="submit" name="add" class="submit-btn" style="cursor: pointer; border: none; width: auto; padding: 15px 60px; font-size: 1.1rem;">Save All Marks</button>
            </div>
        </form>
    </div>
</body>
</html>