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

$class_filter = "";
if(isset($_GET['class_id']) && $_GET['class_id'] != '') {
    $cid = $_GET['class_id'];
    $class_filter = "WHERE e.class_id='$cid'";
}

$sql = "SELECT e.*, c.class_name FROM exam e LEFT JOIN classes c ON e.class_id = c.id $class_filter ORDER BY e.year DESC";
$result = mysqli_query($conn, $sql);

$classes = mysqli_query($conn, "SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Exam List | Admin Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Exam Results Management</h1>
                <p style="color: #636e72;">Review student performance across all classes and exams.</p>
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

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Exam Name</th>
                        <th>Class</th>
                        <th>Year</th>
                        <th>Type</th>
                        <th style="text-align: center;">Reports</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $info['examname']; ?></td>
                        <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;"><?php echo $info['class_name'] ?? 'General'; ?></span></td>
                        <td><?php echo $info['year']; ?></td>
                        <td><?php echo $info['type']; ?></td>
                        <td style="text-align: center;">
                            <a href="admin_marks.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">View Results</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
