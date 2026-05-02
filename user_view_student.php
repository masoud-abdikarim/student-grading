<?php
session_start();
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

$class_filter = "";
if(isset($_GET['class_id']) && $_GET['class_id'] != '') {
    $cid = $_GET['class_id'];
    $class_filter = "AND s.class_id='$cid'";
}

$sql = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.usertype='student' $class_filter ORDER BY s.enroll";
$result = mysqli_query($conn, $sql);

$classes = mysqli_query($conn, "SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
	<title>Student Directory | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Student Directory</h1>
                <p style="color: #636e72;">View and manage students assigned to your classes.</p>
            </div>
            <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
                <select name="class_id" style="margin-bottom: 0; padding: 10px; border-radius: 10px; border: 1px solid #ddd; outline: none; background: #fff;">
                    <option value="">All Classes</option>
                    <?php while($c = mysqli_fetch_assoc($classes)) { ?>
                        <option value="<?php echo $c['id']; ?>" <?php if(isset($_GET['class_id']) && $_GET['class_id'] == $c['id']) echo 'selected'; ?>><?php echo $c['class_name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="logout" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); color: #fff; padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-weight: 600;">Filter</button>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Enroll No</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 700; color: #6c5ce7;"><?php echo $info['enroll']; ?></td>
                        <td style="font-weight: 600;"><?php echo $info['firstname'] . " " . $info['lastname']; ?></td>
                        <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;"><?php echo $info['class_name'] ?? 'Unassigned'; ?></span></td>
                        <td><?php echo $info['email']; ?></td>
                        <td><?php echo $info['phone']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>