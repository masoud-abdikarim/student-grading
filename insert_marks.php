<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif($_SESSION['usertype']=='admin') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

// Initialization logic
if(isset($_GET['init']) && isset($_POST['exam_id'])) {
    $exam_id = $_POST['exam_id'];
    $year = $_POST['year'];
    $class_id = $_POST['class_id'];
    $subject = $_POST['subject'];
    
    $check_sql = "INSERT IGNORE INTO exam_context (exam_id, year, class_id, subject) VALUES ('$exam_id', '$year', '$class_id', '$subject')";
    mysqli_query($conn, $check_sql);
    
    $get_ctx = "SELECT id FROM exam_context WHERE exam_id='$exam_id' AND year='$year' AND class_id='$class_id' AND subject='$subject'";
    $ctx_res = mysqli_query($conn, $get_ctx);
    $ctx_data = mysqli_fetch_assoc($ctx_res);
    header("location:insert_marks.php?context_id=" . $ctx_data['id']);
    exit();
}

$context_id = $_GET['context_id'] ?? '';
if(!$context_id) {
    header("location:add_result.php");
    exit();
}

$ctx_query = "SELECT ec.*, e.type_name, c.class_name FROM exam_context ec 
              JOIN exam e ON ec.exam_id = e.id 
              JOIN classes c ON ec.class_id = c.id 
              WHERE ec.id='$context_id'";
$ctx_data = mysqli_fetch_assoc(mysqli_query($conn, $ctx_query));

$is_locked = $ctx_data['is_locked'];
$subject = $ctx_data['subject'];
$class_id = $ctx_data['class_id'];

if(isset($_POST['add_marks'])) {
    if($is_locked) {
        $msg = "Error: Results are locked.";
    } else {
        $enrolls = $_POST['enroll'];
        $marks_list = $_POST['marks'];
        for($i=0; $i<count($enrolls); $i++){
            $e = mysqli_real_escape_string($conn, $enrolls[$i]);
            $m = empty($marks_list[$i]) ? 0 : mysqli_real_escape_string($conn, $marks_list[$i]);
            
            $check = "SELECT id FROM results WHERE context_id='$context_id' AND enroll='$e'";
            if(mysqli_num_rows(mysqli_query($conn, $check)) > 0) {
                mysqli_query($conn, "UPDATE results SET marks='$m' WHERE context_id='$context_id' AND enroll='$e'");
            } else {
                mysqli_query($conn, "INSERT INTO results (context_id, enroll, marks) VALUES ('$context_id', '$e', '$m')");
            }
        }
        $msg = "Marks Saved Successfully!";
    }
}

$students = mysqli_query($conn, "SELECT * FROM studentlist WHERE class_id='$class_id' AND usertype='student' ORDER BY firstname");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Marks | SGS</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>
    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Insert Exam Marks</h1>
                <p style="color: #636e72;">
                    Type: <strong style="color: #6c5ce7;"><?php echo $ctx_data['type_name']; ?></strong> | 
                    Year: <strong><?php echo $ctx_data['year']; ?></strong> | 
                    Subject: <strong style="color: #6c5ce7;"><?php echo $subject; ?></strong> | 
                    Class: <strong><?php echo $ctx_data['class_name']; ?></strong>
                </p>
            </div>
            <a href="add_result.php" class="logout" style="background: #eee; color: #333; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">Back to Dashboard</a>
        </div>

        <?php if(isset($msg)) { 
            $color = strpos($msg, 'Error') !== false ? '#d63031' : '#00b894';
            $bg = strpos($msg, 'Error') !== false ? '#fdeded' : '#e8f8f5';
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
        } ?>

        <form method="POST" action="">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Enrollment No</th>
                        <th style="width: 200px; text-align: center;">Marks Obtained (Max 100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($s = mysqli_fetch_assoc($students)) { 
                        $e = $s['enroll'];
                        $m_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT marks FROM results WHERE context_id='$context_id' AND enroll='$e'"));
                    ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $s['firstname'] . ' ' . $s['lastname']; ?></td>
                        <td><?php echo $e; ?><input type="hidden" name="enroll[]" value="<?php echo $e; ?>"></td>
                        <td style="text-align: center;">
                            <input type="number" name="marks[]" value="<?php echo $m_res['marks'] ?? ''; ?>" min="0" max="100" style="width: 100px; text-align: center; font-weight: 700;" <?php if($is_locked) echo 'disabled'; ?>>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if(!$is_locked) { ?>
                <div style="margin-top: 30px; text-align: center;">
                    <button type="submit" name="add_marks" class="submit-btn" style="width: auto; padding: 15px 60px;">Save All Marks</button>
                </div>
            <?php } ?>
        </form>
    </div>
</body>
</html>