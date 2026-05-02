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

if(isset($_POST['create'])) {
    $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);
    $class_id = $_POST['class_id'];
    $year = $_POST['exam_year'];
    $type = $_POST['exam_type'];

    $check = "SELECT * FROM exam WHERE examname='$table_name' AND class_id='$class_id'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0) {
        $msg = "Exam already exists for this class!";
    } else {
        $sql = "INSERT INTO exam(examname, year, type, class_id) VALUES('$table_name', '$year', '$type', '$class_id')";
        if(mysqli_query($conn, $sql)) {
            $msg = "Exam Created Successfully!";
        } else {
            $msg = "Failed to create exam: " . mysqli_error($conn);
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
	<title>Add Exam | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h1 style="text-align: center; margin-bottom: 10px;">Create New Exam</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Set up a new assessment for your students.</p>

            <?php if(isset($msg)) { 
                $color = strpos($msg, 'Successfully') !== false ? '#00b894' : '#d63031';
                $bg = strpos($msg, 'Successfully') !== false ? '#e8f8f5' : '#fdeded';
                echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
            } ?>

            <form action="#" method="POST">
                <label>Exam Name</label>
                <input type="text" name="table_name" placeholder="e.g. Midterm Mathematics" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Exam Year</label>
                        <input type="text" name="exam_year" placeholder="e.g. 2024" required>
                    </div>
                    <div>
                        <label>Exam Type</label>
                        <input type="text" name="exam_type" placeholder="e.g. Written" required>
                    </div>
                </div>

                <label>Assign to Class</label>
                <select name="class_id" required>
                    <option value="">Select Class</option>
                    <?php while($c = mysqli_fetch_assoc($classes)) { ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['class_name']; ?></option>
                    <?php } ?>
                </select>

                <div style="margin-top: 20px;">
                    <button type="submit" name="create" class="submit-btn" style="cursor: pointer; border: none;">Create Assessment</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>