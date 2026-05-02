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

$username2 = $firstname2 = $lastname2 = $email2 = $phone2 = $password2 = "";

if(isset($_POST['add_teacher'])) {
    $username2 = $_POST['username'];
    $firstname2 = $_POST['firstname'];
    $lastname2 = $_POST['lastname'];
    $email2 = $_POST['email'];
    $phone2 = $_POST['phone'];
    $password2 = $_POST['password'];
    $usertype = 'teacher';

    if(strlen($phone2) != 10) {
        $msg = "Phone number must be exactly 10 digits.";
    } elseif(!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
    } else {
        $check = "SELECT * FROM teacherlist WHERE username='$username2'";
        $res = mysqli_query($conn, $check);
        if(mysqli_num_rows($res) > 0) {
            $msg = "Username already exists. Please choose another.";
        } else {
            $sql = "INSERT INTO teacherlist(username, firstname, lastname, email, phone, usertype, password) 
                    VALUES ('$username2', '$firstname2', '$lastname2', '$email2', '$phone2', '$usertype', '$password2')";
            if(mysqli_query($conn, $sql)) {
                $msg = "Teacher Added Successfully!";
                $username2 = $firstname2 = $lastname2 = $email2 = $phone2 = $password2 = "";
            } else {
                $msg = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<title>Add Teacher | Admin Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h1 style="text-align: center; margin-bottom: 10px;">Add New Teacher</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Create a new teacher account for the system.</p>

            <?php if(isset($msg)) { 
                $color = strpos($msg, 'Successfully') !== false ? '#00b894' : '#d63031';
                $bg = strpos($msg, 'Successfully') !== false ? '#e8f8f5' : '#fdeded';
                echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
            } ?>

            <form action="#" method="POST">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username2 ?>" placeholder="e.g. john_doe" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="firstname" value="<?php echo $firstname2 ?>" placeholder="e.g. John" required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="lastname" value="<?php echo $lastname2 ?>" placeholder="e.g. Smith" required>
                    </div>
                </div>

                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo $email2 ?>" placeholder="john.smith@academy.com" required>

                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo $phone2 ?>" placeholder="10-digit number" required>

                <label>Login Password</label>
                <input type="password" name="password" value="<?php echo $password2 ?>" placeholder="Create a secure password" required>

                <div style="margin-top: 20px;">
                    <button type="submit" name="add_teacher" class="submit-btn" style="cursor: pointer; border: none;">Add Teacher Account</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>