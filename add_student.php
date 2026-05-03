<?php
error_reporting(E_ALL);
session_start();

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

// Initialize variables
$firstname2 = $lastname2 = $email2 = $phone2 = $class_id2 = $password2 = "";

if(isset($_POST['add_student'])) {
    $firstname2 = $_POST['firstname'];
    $lastname2 = $_POST['lastname'];
    $email2 = $_POST['email'];
    $phone2 = $_POST['phone'];
    $class_id2 = $_POST['class_id'];
    $password2 = $_POST['password'];
    $usertype = "student";

    $check = "SELECT * FROM studentlist WHERE email='$email2'";
    $check_user = mysqli_query($conn, $check);
    $row_count = mysqli_num_rows($check_user);

    if($row_count == 1) {
        $msg = "Email already exists. Try another one.";
    } else {
        // Auto-generate enrollment number
        $result = mysqli_query($conn, "SELECT MAX(id) as max_id FROM studentlist");
        $row = mysqli_fetch_assoc($result);
        $next_id = ($row['max_id'] ?? 0) + 1;
        $enroll = "ENR-" . str_pad($next_id, 4, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO studentlist (firstname, lastname, email, phone, enroll, usertype, password, class_id) 
                VALUES ('$firstname2', '$lastname2', '$email2', '$phone2', '$enroll', '$usertype', '$password2', '$class_id2')";
        
        $result = mysqli_query($conn, $sql);

        if($result) {
            $msg = "Student Added Successfully! Enrollment: $enroll";
            $firstname2 = $lastname2 = $email2 = $phone2 = $class_id2 = $password2 = "";
        } else {
            $msg = "Data Upload Failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<title>Add Student | Admin Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h1 style="text-align: center; margin-bottom: 10px;">Add New Student</h1>
            <p style="text-align: center; color: #636e72; margin-bottom: 30px;">Fill in the details to enroll a new student.</p>

            <?php if(isset($msg)) { 
                $color = strpos($msg, 'Successfully') !== false ? '#00b894' : '#d63031';
                $bg = strpos($msg, 'Successfully') !== false ? '#e8f8f5' : '#fdeded';
                echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: $bg; color: $color; border: 1px solid $color; font-weight: 600; text-align: center;'>$msg</div>";
            } ?>

            <form action="#" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="firstname" value="<?php echo $firstname2 ?>" placeholder="e.g. John" required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="lastname" value="<?php echo $lastname2 ?>" placeholder="e.g. Doe" required>
                    </div>
                </div>

                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo $email2 ?>" placeholder="john.doe@example.com" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo $phone2 ?>" placeholder="07xxxxxxxx" required>
                    </div>
                    <div>
                        <label>Assign Class</label>
                        <select name="class_id" required>
                            <option value="">Select Class</option>
                            <?php
                            $classes = mysqli_query($conn, "SELECT * FROM classes");
                            while($c = mysqli_fetch_assoc($classes)) {
                                $sel = ($class_id2 == $c['id']) ? "selected" : "";
                                echo "<option value='{$c['id']}' $sel>{$c['class_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <label>Login Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" value="<?php echo $password2 ?>" placeholder="Set a secure password" required>
                    <i class="fa-solid fa-eye toggle-password" id="toggleIcon" onclick="togglePassword('password', 'toggleIcon')"></i>
                </div>


                <div style="margin-top: 20px;">
                    <button type="submit" name="add_student" class="submit-btn" style="cursor: pointer; border: none;">Add Student Account</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>