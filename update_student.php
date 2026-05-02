<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
} elseif($_SESSION['usertype']=='student') {
    header("location:index.php");
}

$host="localhost";
$user="root";
$password="";
$db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$id = $_GET['student_id'];
$sql = "SELECT * FROM studentlist WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

if(isset($_POST['save_update'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $class_id = $_POST['class_id'];

    $query = "UPDATE studentlist SET firstname='$firstname', lastname='$lastname', email='$email', phone='$phone', password='$password', class_id='$class_id' WHERE id='$id'";
    if(mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Student records updated successfully!";
        header("location:view_student.php");
        exit();
    }
}

$classes = mysqli_query($conn, "SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
	<title>Update Student | Admin Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h1 style="text-align: center; margin-bottom: 10px;">Update Student Data</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Modify records for enrollment ID: <strong><?php echo $info['enroll']; ?></strong></p>

            <form action="#" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="firstname" value="<?php echo $info['firstname']; ?>" required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="lastname" value="<?php echo $info['lastname']; ?>" required>
                    </div>
                </div>

                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo $info['email']; ?>" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo $info['phone']; ?>" required>
                    </div>
                    <div>
                        <label>Class Assignment</label>
                        <select name="class_id" required>
                            <option value="">Select Class</option>
                            <?php while($c = mysqli_fetch_assoc($classes)) { ?>
                                <option value="<?php echo $c['id']; ?>" <?php if($info['class_id'] == $c['id']) echo "selected"; ?>><?php echo $c['class_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <label>Login Password</label>
                <input type="text" name="password" value="<?php echo $info['password']; ?>" required>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" name="save_update" class="submit-btn" style="cursor: pointer; border: none; flex: 1;">Save Changes</button>
                    <a href="view_student.php" style="text-decoration: none; background: #eee; color: #333; padding: 12px 20px; border-radius: 12px; font-weight: 600; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>