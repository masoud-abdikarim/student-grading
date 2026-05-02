<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
} elseif($_SESSION['usertype']!='admin') {
    header("location:index.php");
}

$host="localhost";
$user="root";
$password="";
$db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

if(isset($_POST['add_class'])) {
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    
    $check = "SELECT * FROM classes WHERE class_name='$class_name'";
    $check_res = mysqli_query($conn, $check);
    
    if(mysqli_num_rows($check_res) > 0) {
        $msg = "Class already exists!";
    } else {
        $sql = "INSERT INTO classes(class_name) VALUES ('$class_name')";
        if(mysqli_query($conn, $sql)) {
            $msg = "Class Added Successfully!";
        } else {
            $msg = "Failed to add class: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<title>Add Class | Admin Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h1 style="text-align: center; margin-bottom: 10px;">Add New Class</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Create a new class for student organization.</p>

            <?php if(isset($msg)) { 
                $color = strpos($msg, 'Successfully') !== false ? '#00b894' : '#d63031';
                $bg = strpos($msg, 'Successfully') !== false ? '#e8f8f5' : '#fdeded';
                echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
            } ?>

            <form action="#" method="POST">
                <label>Class Name</label>
                <input type="text" name="class_name" placeholder="e.g. Class 10-A" required>

                <div style="margin-top: 20px;">
                    <button type="submit" name="add_class" class="submit-btn" style="cursor: pointer; border: none;">Create Class</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
