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

$cid = $_GET['class_id'] ?? '';
$year = $_GET['year'] ?? date('Y');

$where = "WHERE e.year='$year'";
if($cid != '') {
    $where .= " AND e.class_id='$cid'";
}

$sql_main = "SELECT e.*, c.class_name FROM exam e LEFT JOIN classes c ON e.class_id = c.id $where AND e.category='Main' ORDER BY e.type ASC";
$res_main = mysqli_query($conn, $sql_main);

$sql_other = "SELECT e.*, c.class_name FROM exam e LEFT JOIN classes c ON e.class_id = c.id $where AND e.category='Other' ORDER BY e.type ASC";
$res_other = mysqli_query($conn, $sql_other);


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
                <select name="year" style="padding: 10px; border-radius: 10px; border: 1px solid #ddd; outline: none; background: #fff;">
                    <?php for($y = date('Y')+1; $y >= 2020; $y--) { ?>
                        <option value="<?php echo $y; ?>" <?php if($year == $y) echo 'selected'; ?>><?php echo $y; ?></option>
                    <?php } ?>
                </select>
                <select name="class_id" style="padding: 10px; border-radius: 10px; border: 1px solid #ddd; outline: none; background: #fff;">
                    <option value="">All Classes</option>
                    <?php while($c = mysqli_fetch_assoc($classes)) { ?>
                        <option value="<?php echo $c['id']; ?>" <?php if($cid == $c['id']) echo 'selected'; ?>><?php echo $c['class_name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="logout" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); color: #fff; padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-weight: 600;">Filter</button>
            </form>

        </div>

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <div style="margin-bottom: 50px;">
            <h2 style="margin-bottom: 20px; color: #6c5ce7; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-star"></i> Main Academic Exams (Midterm & Final)
            </h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Class</th>
                            <th>Type</th>
                            <th>Pass Mark</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($res_main) == 0) echo "<tr><td colspan='5' style='text-align:center;'>No main exams found for this selection.</td></tr>"; ?>
                        <?php while($info = mysqli_fetch_assoc($res_main)) { ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo $info['examname']; ?></td>
                            <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;"><?php echo $info['class_name'] ?? 'General'; ?></span></td>
                            <td><strong style="color: #6c5ce7;"><?php echo $info['type']; ?></strong></td>
                            <td><?php echo $info['pass_mark']; ?></td>
                            <td style="text-align: center;">
                                <a href="admin_marks.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">View Results</a>
                                <?php if($info['is_locked'] == 0) { ?>
                                    <a href="lock_exam.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #f1f2f6; color: #2d3436;" onClick="return confirm('Lock these results?')"><i class="fa-solid fa-lock"></i> Lock</a>
                                <?php } else { ?>
                                    <span style="color: #d63031; font-size: 0.8rem; font-weight: 700; margin-left: 10px;"><i class="fa-solid fa-lock"></i> Locked</span>
                                <?php } ?>
                            </td>

                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h2 style="margin-bottom: 20px; color: #636e72; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-list-check"></i> Other Assessments (Quizzes & Tests)
            </h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Class</th>
                            <th>Type</th>
                            <th>Pass Mark</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($res_other) == 0) echo "<tr><td colspan='5' style='text-align:center;'>No other assessments found for this selection.</td></tr>"; ?>
                        <?php while($info = mysqli_fetch_assoc($res_other)) { ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo $info['examname']; ?></td>
                            <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;"><?php echo $info['class_name'] ?? 'General'; ?></span></td>
                            <td><?php echo $info['type']; ?></td>
                            <td><?php echo $info['pass_mark']; ?></td>
                            <td style="text-align: center;">
                                <a href="admin_marks.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894;">View Results</a>
                                <?php if($info['is_locked'] == 0) { ?>
                                    <a href="lock_exam.php?exam_id=<?php echo $info['id']; ?>" class="table-btn" style="background: #f1f2f6; color: #2d3436;" onClick="return confirm('Lock these results?')"><i class="fa-solid fa-lock"></i> Lock</a>
                                <?php } else { ?>
                                    <span style="color: #d63031; font-size: 0.8rem; font-weight: 700; margin-left: 10px;"><i class="fa-solid fa-lock"></i> Locked</span>
                                <?php } ?>
                            </td>

                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>
