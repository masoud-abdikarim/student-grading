<?php
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

$sql = "SELECT * FROM classes";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Class List | SGS Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php 
    if($_SESSION['usertype']=='admin') {
        include 'admin_sidebar.php';
    } else {
        include 'teacher_sidebar.php';
    }
    ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Class Directory</h1>
            <?php if($_SESSION['usertype']=='admin') { ?>
                <a href="add_class.php" class="logout" style="background: linear-gradient(135deg, #00b894 0%, #55efc4 100%); color: #fff; padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-weight: 600; text-decoration: none;">+ Add Class</a>
            <?php } ?>
        </div>

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <div style="overflow-x: auto; max-width: 800px; margin: 0 auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 100px; text-align: center;">ID</th>
                        <th>Class Name</th>
                        <?php if($_SESSION['usertype']=='admin') { ?>
                            <th style="width: 150px; text-align: center;">Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="text-align: center; color: #636e72;">#<?php echo $info['id']; ?></td>
                        <td style="font-weight: 600;"><?php echo $info['class_name']; ?></td>
                        <?php if($_SESSION['usertype']=='admin') { ?>
                            <td style="text-align: center;">
                                <a href="delete_class.php?class_id=<?php echo $info['id']; ?>" class="table-btn table-btn-delete" onClick="return confirm('Delete this class?')">Delete</a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
