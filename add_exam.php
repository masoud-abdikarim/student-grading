<?php
session_start();
error_reporting(E_ALL);
include 'subjects.php';

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif($_SESSION['usertype']=='admin') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$classes = mysqli_query($conn, "SELECT * FROM classes");
$exam_types = mysqli_query($conn, "SELECT * FROM exam");

if(isset($_POST['setup_exam'])) {
    $exam_id = $_POST['exam_id']; // This is the ID from 'exam' table (Type)
    $year = $_POST['year'];
    $class_id = $_POST['class_id'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $pass_mark = $_POST['pass_mark'];

    // Check if this context already exists
    $check_sql = "SELECT id FROM exam_context WHERE exam_id='$exam_id' AND year='$year' AND class_id='$class_id' AND subject='$subject'";
    $check_res = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($check_res) > 0) {
        $existing = mysqli_fetch_assoc($check_res);
        $ctx_id = $existing['id'];
        $msg = "This exam context already exists. You can now manage results for it.";
        $msg_type = "info";
        echo "<script>setTimeout(() => { window.location.href = 'insert_marks.php?context_id=$ctx_id'; }, 2000);</script>";
    } else {
        $insert_sql = "INSERT INTO exam_context (exam_id, year, class_id, subject, pass_mark) 
                       VALUES ('$exam_id', '$year', '$class_id', '$subject', '$pass_mark')";
        if(mysqli_query($conn, $insert_sql)) {
            $ctx_id = mysqli_insert_id($conn);
            $msg = "Exam successfully initialized! Redirecting to mark entry...";
            $msg_type = "success";
            echo "<script>setTimeout(() => { window.location.href = 'insert_marks.php?context_id=$ctx_id'; }, 1500);</script>";
        } else {
            $msg = "Error initializing exam: " . mysqli_error($conn);
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup Exam | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div class="form-container" style="max-width: 800px;">
            <h1 style="text-align: center; margin-bottom: 10px;">Setup Academic Exam</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Initialize a specific exam session for your class and subject.</p>

            <?php if(isset($msg)) { 
                $color = ($msg_type == 'success') ? '#00b894' : (($msg_type == 'info') ? '#6c5ce7' : '#d63031');
                $bg = ($msg_type == 'success') ? '#e8f8f5' : (($msg_type == 'info') ? '#f8faff' : '#fdeded');
                echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
            } ?>

            <form action="#" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div>
                        <label>Exam Type</label>
                        <select name="exam_id" required>
                            <option value="">Select Type</option>
                            <?php while($et = mysqli_fetch_assoc($exam_types)) { ?>
                                <option value="<?php echo $et['id']; ?>"><?php echo $et['type_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Academic Year</label>
                        <input type="number" name="year" value="<?php echo date('Y'); ?>" required>
                    </div>
                    <div>
                        <label>Class</label>
                        <select name="class_id" required>
                            <option value="">Select Class</option>
                            <?php while($c = mysqli_fetch_assoc($classes)) { ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Subject</label>
                        <select name="subject" required>
                            <option value="">Select Subject</option>
                            <?php foreach($fixed_subjects as $sub) { ?>
                                <option value="<?php echo $sub; ?>"><?php echo $sub; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <label>Pass Mark (out of 100)</label>
                    <input type="number" name="pass_mark" value="50" min="0" max="100" required style="width: 50%;">
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" name="setup_exam" class="submit-btn" style="cursor: pointer; border: none;">Initialize & Continue</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>