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
    $class_filter = "WHERE e.class_id='$cid'";
}

$sql = "SELECT e.*, c.class_name FROM exam e LEFT JOIN classes c ON e.class_id = c.id $class_filter ORDER BY e.id DESC";
$result = mysqli_query($conn, $sql);

$classes = mysqli_query($conn, "SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Results | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Manage Student Results</h1>
                <p style="color: #636e72;">Select an exam to insert or modify student marks.</p>
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
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600;'>{$_SESSION['message']}</div>";
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
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $info['examname']; ?></td>
                        <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;"><?php echo $info['class_name']; ?></span></td>
                        <td><?php echo $info['year']; ?></td>
                        <td><?php echo $info['type']; ?></td>
                        <td style="text-align: center;">
                            <?php if($info['is_locked'] == 1) { ?>
                                <span style="background: #fdeded; color: #d63031; padding: 6px 15px; border-radius: 50px; font-size: 0.85rem; font-weight: 700;"><i class="fa-solid fa-lock"></i> Locked</span>
                                <a href="admin_marks.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #eee; color: #666;">View Results</a>
                            <?php } else { ?>
                                <a href="insert_marks.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">Insert Marks</a>
                                <a href="lock_exam.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #f1f2f6; color: #2d3436;" onClick="return confirm('Lock these results? They cannot be edited after locking.')"><i class="fa-solid fa-lock"></i> Lock</a>
                                <a href="delete_exam.php?exam_id=<?php echo $info['id']; ?>" class="table-btn table-btn-delete" onClick="return confirm('Delete this exam and all results?')">Delete</a>
                            <?php } ?>
                        </td>

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
