<?php
session_start();
error_reporting(E_ALL);
include 'subjects.php';

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

if(isset($_POST['create'])) {
    $assign_name = mysqli_real_escape_string($conn, $_POST['assign_name']);
    $class_id = $_POST['class_id'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $date = $_POST['date'];

    $check = "SELECT * FROM assignment WHERE assignment='$assign_name' AND class_id='$class_id' AND subject='$subject'";
    $res = mysqli_query($conn, $check);

    if(mysqli_num_rows($res) > 0) {
        $msg = "Assignment already exists for this subject in this class!";
    } else {
        // File Upload
        $name = $_FILES['file']['name'];
        $tmp_name = $_FILES['file']['tmp_name'];
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $newName = time() . "_" . rand(1000, 9999) . "." . $ext;
        $upload_dir = 'assignment/';
        
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        if(move_uploaded_file($tmp_name, $upload_dir . $newName)) {
            $sql = "INSERT INTO assignment(assignment, subject, duedate, file, class_id) VALUES('$assign_name', '$subject', '$date', '$newName', '$class_id')";
            if(mysqli_query($conn, $sql)) {
                $msg = "Assignment Added Successfully!";
            } else {
                $msg = "Database error: " . mysqli_error($conn);
            }
        } else {
            $msg = "File upload failed!";
        }
    }
}

$classes = mysqli_query($conn, "SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Assignment | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h1 style="text-align: center; margin-bottom: 10px;">Post New Assignment</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Upload coursework and set deadlines per subject.</p>

            <?php if(isset($msg)) { 
                $color = strpos($msg, 'Successfully') !== false ? '#00b894' : '#d63031';
                $bg = strpos($msg, 'Successfully') !== false ? '#e8f8f5' : '#fdeded';
                echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
            } ?>

            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Assignment Title</label>
                <input type="text" name="assign_name" placeholder="e.g. Weekly Homework" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Subject</label>
                        <select name="subject" required>
                            <option value="">Select Subject</option>
                            <?php foreach($fixed_subjects as $sub) { ?>
                                <option value="<?php echo $sub; ?>"><?php echo $sub; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Target Class</label>
                        <select name="class_id" required>
                            <option value="">Select Class</option>
                            <?php while($c = mysqli_fetch_assoc($classes)) { ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <label>Submission Due Date</label>
                <input type="date" name="date" required>

                <label>Assignment File (PDF/Doc/Image)</label>
                <input type="file" name="file" required style="padding: 10px; border: 2px dashed #ddd; background: #fafafa;">

                <div style="margin-top: 20px;">
                    <button type="submit" name="create" class="submit-btn" style="cursor: pointer; border: none;">Post Assignment</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>