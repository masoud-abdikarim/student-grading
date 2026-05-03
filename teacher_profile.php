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

$username = $_SESSION['username'];
$sql = "SELECT * FROM teacherlist WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

if(isset($_POST['update_profile'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql2 = "UPDATE teacherlist SET email='$email', phone='$phone', password='$password' WHERE username='$username'";
    $result2 = mysqli_query($conn, $sql2);
    if($result2) {
        $_SESSION['message'] = "Profile updated successfully!";
        header('location:teacher_profile.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Profile | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            height: 150px;
            border-radius: 20px 20px 0 0;
            position: relative;
            margin-bottom: 70px;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: absolute;
            bottom: -60px;
            left: 40px;
            border: 5px solid #fff;
        }
        .profile-info {
            padding: 20px 40px;
        }
    </style>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <h1>Account Settings</h1>
        
        <div class="form-container" style="padding: 0; overflow: hidden; max-width: 900px;">
            <div class="profile-header">
                <div class="profile-avatar">👨‍🏫</div>
            </div>
            
            <div class="profile-info">
                <div style="margin-bottom: 30px;">
                    <h2><?php echo $info['firstname'] . " " . $info['lastname']; ?></h2>
                    <p style="color: #636e72;">Teacher Account | @<?php echo $info['username']; ?></p>
                </div>

                <?php if(isset($_SESSION['message'])) { 
                    echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
                    unset($_SESSION['message']);
                } ?>

                <form method="POST" action="#">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                        <div>
                            <label>Username (Fixed)</label>
                            <input type="text" value="<?php echo $info['username']; ?>" disabled style="background: #f9f9f9; cursor: not-allowed;">
                        </div>
                        <div>
                            <label>First Name</label>
                            <input type="text" value="<?php echo $info['firstname']; ?>" disabled style="background: #f9f9f9; cursor: not-allowed;">
                        </div>
                        <div>
                            <label>Last Name</label>
                            <input type="text" value="<?php echo $info['lastname']; ?>" disabled style="background: #f9f9f9; cursor: not-allowed;">
                        </div>
                        <div>
                            <label>Email Address</label>
                            <input type="email" name="email" value="<?php echo $info['email']; ?>" required>
                        </div>
                        <div>
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo $info['phone']; ?>" required>
                        </div>
                        <div>
                            <label>Update Password</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" value="<?php echo $info['password']; ?>" required>
                                <i class="fa-solid fa-eye toggle-password" id="toggleIcon" onclick="togglePassword('password', 'toggleIcon')"></i>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                        <button type="submit" name="update_profile" class="submit-btn" style="max-width: 200px;">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>