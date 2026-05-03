<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username']) || $_SESSION['usertype'] != 'admin') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$year = $_GET['year'] ?? date('Y');
$class_id = $_GET['class_id'] ?? '';

// Handle Promotion Action
if(isset($_POST['process_promotion'])) {
    $target_class = $_POST['target_class'];
    $student_enrolls = $_POST['promote_list'] ?? [];
    $current_year = $_POST['current_year'];
    $current_class = $_POST['current_class'];

    foreach($student_enrolls as $enroll) {
        // 1. Log to history
        $avg_sql = "SELECT AVG(r.marks) as avg 
                    FROM results r 
                    JOIN exam_context ec ON r.context_id = ec.id 
                    WHERE r.enroll='$enroll' AND ec.year='$current_year'";
        $avg_res = mysqli_fetch_assoc(mysqli_query($conn, $avg_sql));
        $avg = $avg_res['avg'] ?? 0;

        $history_sql = "INSERT INTO academic_history (enroll, year, class_id, average_marks, status) 
                        VALUES ('$enroll', '$current_year', '$current_class', '$avg', 'Promoted')";
        mysqli_query($conn, $history_sql);

        // 2. Update current class
        $update_sql = "UPDATE studentlist SET class_id='$target_class' WHERE enroll='$enroll'";
        mysqli_query($conn, $update_sql);
    }
    $_SESSION['message'] = count($student_enrolls) . " students promoted successfully!";
    header("location:promote_students.php?year=$current_year&class_id=$current_class");
    exit();
}

$classes = mysqli_query($conn, "SELECT * FROM classes");
$target_classes = mysqli_query($conn, "SELECT * FROM classes");

// Fetch eligible students
$students = [];
if($class_id != '') {
    // Find ALL Final Exam contexts for this class/year
    $final_ctx_sql = "SELECT ec.id, ec.pass_mark 
                      FROM exam_context ec 
                      JOIN exam e ON ec.exam_id = e.id 
                      WHERE ec.class_id='$class_id' AND ec.year='$year' AND e.type_name='Final'";
    $ctx_res = mysqli_query($conn, $final_ctx_sql);
    $contexts = [];
    $ctx_ids = [];
    while($ctx = mysqli_fetch_assoc($ctx_res)) {
        $contexts[$ctx['id']] = $ctx;
        $ctx_ids[] = $ctx['id'];
    }

    if(!empty($ctx_ids)) {
        $ids_str = implode(',', $ctx_ids);
        $sql = "SELECT s.* FROM studentlist s WHERE s.class_id = '$class_id' AND s.usertype='student'";
        $res = mysqli_query($conn, $sql);
        while($s = mysqli_fetch_assoc($res)) {
            $enroll = $s['enroll'];
            $marks_res = mysqli_query($conn, "SELECT * FROM results WHERE enroll='$enroll' AND context_id IN ($ids_str)");
            
            $total_marks = 0;
            $failed_count = 0;
            $subjects_taken = 0;
            while($m = mysqli_fetch_assoc($marks_res)) {
                $mark = $m['marks'];
                $pm = $contexts[$m['context_id']]['pass_mark'];
                $total_marks += $mark;
                if($mark < $pm) $failed_count++;
                $subjects_taken++;
            }
            $average = $total_marks / 7;
            
            $s['average'] = $average;
            $s['failed_count'] = $failed_count;
            $s['subjects_taken'] = $subjects_taken;
            $students[] = $s;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Promotion | SGS</title>
    <?php include 'shared_styles.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <div class="content">
        <h1>Student Promotion Management</h1>
        <div class="form-container" style="max-width: 1000px; margin-bottom: 30px;">
            <form method="GET" action="" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; align-items: end;">
                <div>
                    <label>Academic Year</label>
                    <select name="year">
                        <?php for($y = date('Y'); $y >= 2020; $y--) { ?>
                            <option value="<?php echo $y; ?>" <?php if($year == $y) echo 'selected'; ?>><?php echo $y; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label>Current Class</label>
                    <select name="class_id" required>
                        <option value="">Select Class</option>
                        <?php mysqli_data_seek($classes, 0); while($c = mysqli_fetch_assoc($classes)) { ?>
                            <option value="<?php echo $c['id']; ?>" <?php if($class_id == $c['id']) echo 'selected'; ?>><?php echo $c['class_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn" style="padding: 12px;">Fetch Candidates</button>
            </form>
        </div>

        <?php if(isset($_SESSION['message'])) { 
            echo "<div style='padding: 15px; border-radius: 12px; margin-bottom: 25px; background: #e8f8f5; color: #00b894; border: 1px solid #00b894; font-weight: 600; text-align: center;'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        } ?>

        <?php if($class_id != ''): ?>
            <?php if(empty($students)): ?>
                <div style="text-align: center; padding: 40px; background: #fff; border-radius: 20px;">
                    <i class="fa-solid fa-circle-info" style="font-size: 3rem; color: #6c5ce7; margin-bottom: 20px;"></i>
                    <p>No promotion candidates found for the selected year/class.</p>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <input type="hidden" name="current_year" value="<?php echo $year; ?>">
                    <input type="hidden" name="current_class" value="<?php echo $class_id; ?>">
                    <div style="background: #fff; padding: 30px; border-radius: 20px; border: 1px solid #eee;">
                        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px; padding: 15px; background: #f8faff; border-radius: 12px;">
                            <label style="margin: 0;">Promote selected to:</label>
                            <select name="target_class" required style="margin: 0; width: auto;">
                                <option value="">Select Target Class</option>
                                <?php mysqli_data_seek($target_classes, 0); while($tc = mysqli_fetch_assoc($target_classes)) { ?>
                                    <option value="<?php echo $tc['id']; ?>"><?php echo $tc['class_name']; ?></option>
                                <?php } ?>
                            </select>
                            <button type="submit" name="process_promotion" class="submit-btn" style="width: auto; margin: 0; padding: 10px 25px;">Process Promotion</button>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 50px;"><input type="checkbox" onclick="toggleAll(this)"></th>
                                    <th>Enrollment</th>
                                    <th>Name</th>
                                    <th>Avg Score</th>
                                    <th>Fails</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($students as $s): 
                                    $is_eligible = ($s['average'] >= 50 && $s['failed_count'] <= 2);
                                ?>
                                <tr style="<?php if(!$is_eligible) echo 'background: #fdf2f2;'; ?>">
                                    <td><?php if($is_eligible): ?><input type="checkbox" name="promote_list[]" value="<?php echo $s['enroll']; ?>" checked><?php endif; ?></td>
                                    <td><?php echo $s['enroll']; ?></td>
                                    <td><?php echo $s['firstname'] . " " . $s['lastname']; ?></td>
                                    <td><strong><?php echo number_format($s['average'], 1); ?>%</strong></td>
                                    <td><?php echo $s['failed_count']; ?></td>
                                    <td><span style="font-weight: 700; color: <?php echo $is_eligible ? '#00b894' : '#d63031'; ?>;"><?php echo $is_eligible ? 'PASS' : 'FAIL'; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script>
    function toggleAll(source) {
        checkboxes = document.getElementsByName('promote_list[]');
        for(var i=0, n=checkboxes.length;i<n;i++) checkboxes[i].checked = source.checked;
    }
    </script>
</body>
</html>
