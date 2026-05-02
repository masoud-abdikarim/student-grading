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
$sql = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.enroll='$username'";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

if(isset($_POST['update_profile'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql2 = "UPDATE studentlist SET email='$email', phone='$phone', password='$password' WHERE enroll='$username'";
    $result2 = mysqli_query($conn, $sql2);
    if($result2) {
        $_SESSION['message'] = "Profile updated successfully!";
        header('location:student_profile.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Profile | Student Dashboard</title>
    <?php include 'shared_styles.php'; ?>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
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
    <?php include 'student_sidebar.php'; ?>

    <div class="content">
        <h1>My Account Profile</h1>
        
        <div class="form-container" style="padding: 0; overflow: hidden; max-width: 900px;">
            <div class="profile-header">
                <div class="profile-avatar">🎓</div>
            </div>
            
            <div class="profile-info">
                <div style="margin-bottom: 30px;">
                    <h2><?php echo $info['firstname'] . " " . $info['lastname']; ?></h2>
                    <p style="color: #636e72;">Student Account | Enroll: <strong><?php echo $info['enroll']; ?></strong> | Class: <strong><?php echo $info['class_name'] ?? 'Not Assigned'; ?></strong></p>
                </div>

                <?php if(isset($_SESSION['message'])) { 
                    echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
                    unset($_SESSION['message']);
                } ?>

                <form method="POST" action="#">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                        <div>
                            <label>Enrollment Number (Fixed)</label>
                            <input type="text" value="<?php echo $info['enroll']; ?>" disabled style="background: #f9f9f9; cursor: not-allowed;">
                        </div>
                        <div>
                            <label>Class (Fixed)</label>
                            <input type="text" value="<?php echo $info['class_name'] ?? 'Not Assigned'; ?>" disabled style="background: #f9f9f9; cursor: not-allowed;">
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
                            <input type="text" name="password" value="<?php echo $info['password']; ?>" required>
                        </div>
                    </div>

                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                        <button type="submit" name="update_profile" class="submit-btn" style="max-width: 200px; background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>