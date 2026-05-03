<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif($_SESSION['usertype']=='student') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$cid = $_GET['class_id'] ?? '';
$year_filter = $_GET['year'] ?? '';

// Build hierarchical data using context
$where = "WHERE 1=1";
if($year_filter != '') $where .= " AND ec.year='$year_filter'";
if($cid != '') $where .= " AND ec.class_id='$cid'";

$sql = "SELECT ec.*, e.type_name, e.category, c.class_name 
        FROM exam_context ec 
        JOIN exam e ON ec.exam_id = e.id 
        JOIN classes c ON ec.class_id = c.id 
        $where 
        ORDER BY ec.year DESC, e.type_name ASC, c.class_name ASC, ec.subject ASC";
$res = mysqli_query($conn, $sql);

$hierarchy = [];
while($row = mysqli_fetch_assoc($res)) {
    $y = $row['year'];
    $t = $row['type_name'];
    $cl = $row['class_name'] ?? 'General';
    $hierarchy[$y][$t][$cl][] = $row;
}

$classes = mysqli_query($conn, "SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organized Reports | Admin</title>
    <?php include 'shared_styles.php'; ?>
    <style>
        .year-section { margin-bottom: 50px; }
        .year-title { 
            background: #2d3436; color: #fff; padding: 15px 30px; border-radius: 15px; 
            margin-bottom: 25px; display: inline-block; font-size: 1.5rem; font-weight: 800;
        }
        .type-section { margin-left: 20px; margin-bottom: 30px; }
        .type-title { 
            color: #6c5ce7; font-size: 1.2rem; font-weight: 700; margin-bottom: 15px; 
            display: flex; align-items: center; gap: 10px;
        }
        .class-group { 
            background: #fff; border-radius: 20px; padding: 20px; margin-bottom: 20px; 
            border: 1px solid #eee; box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        }
        .class-title { color: #636e72; font-size: 1rem; font-weight: 700; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #f1f2f6; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1>Global Result Reports</h1>
                <p style="color: #636e72;">Hierarchical grouping by Year, Type, and Class.</p>
            </div>
            <form method="GET" action="" style="display: flex; gap: 10px;">
                <select name="year" style="padding: 10px; border-radius: 10px; border: 1px solid #ddd;">
                    <option value="">All Years</option>
                    <?php for($y = date('Y')+1; $y >= 2020; $y--) { ?>
                        <option value="<?php echo $y; ?>" <?php if($year_filter == $y) echo 'selected'; ?>><?php echo $y; ?></option>
                    <?php } ?>
                </select>
                <select name="class_id" style="padding: 10px; border-radius: 10px; border: 1px solid #ddd;">
                    <option value="">All Classes</option>
                    <?php mysqli_data_seek($classes, 0); while($c = mysqli_fetch_assoc($classes)) { ?>
                        <option value="<?php echo $c['id']; ?>" <?php if($cid == $c['id']) echo 'selected'; ?>><?php echo $c['class_name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="logout" style="background: #6c5ce7; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer;">Filter Results</button>
            </form>
        </div>

        <?php foreach($hierarchy as $year => $types): ?>
            <div class="year-section">
                <div class="year-title"><?php echo $year; ?> Academic Year</div>
                <?php foreach($types as $type => $classes_data): ?>
                    <div class="type-section">
                        <div class="type-title">
                            <i class="fa-solid <?php echo ($type == 'Final' || $type == 'Midterm') ? 'fa-star' : 'fa-list-check'; ?>"></i>
                            <?php echo $type; ?> Results
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(450px, 1fr)); gap: 20px;">
                            <?php foreach($classes_data as $className => $exams): ?>
                                <div class="class-group">
                                    <div class="class-title"><i class="fa-solid fa-users"></i> <?php echo $className; ?></div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th style="text-align: right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($exams as $ex): ?>
                                                <tr>
                                                    <td style="font-weight: 600;"><?php echo $ex['subject']; ?></td>
                                                    <td>
                                                        <?php if($ex['is_locked']): ?>
                                                            <span style="color: #d63031;"><i class="fa-solid fa-lock"></i> Locked</span>
                                                        <?php else: ?>
                                                            <span style="color: #00b894;"><i class="fa-solid fa-unlock"></i> Open</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="text-align: right;">
                                                        <a href="admin_marks.php?context_id=<?php echo $ex['id']; ?>" class="table-btn" style="padding: 4px 10px; font-size: 0.75rem;">View</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
