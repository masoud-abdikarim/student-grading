<?php
session_start();
error_reporting(E_ALL);
include 'subjects.php';

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif($_SESSION['usertype']=='admin') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$exam_id = $_GET['exam_id'] ?? ''; // This is the Type ID now
$year = $_GET['year'] ?? date('Y');
$class_id = $_GET['class_id'] ?? '';
$subject = $_GET['subject'] ?? '';

$exams = mysqli_query($conn, "SELECT * FROM exam");
$classes = mysqli_query($conn, "SELECT * FROM classes");

// Fetch context if exists
$context = null;
if($exam_id && $year && $class_id && $subject) {
    $ctx_sql = "SELECT * FROM exam_context WHERE exam_id='$exam_id' AND year='$year' AND class_id='$class_id' AND subject='$subject'";
    $ctx_res = mysqli_query($conn, $ctx_sql);
    $context = mysqli_fetch_assoc($ctx_res);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Results | Teacher Dashboard</title>
    <?php include 'shared_styles.php'; ?>
    <style>
        .exam-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }
        .exam-card {
            background: #fff;
            padding: 30px;
            border-radius: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .exam-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        .exam-card.active { border-color: #6c5ce7; background: #f8faff; }
        
        .filter-panel {
            background: #fff;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            align-items: end;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <div style="margin-bottom: 40px;">
            <h1>Academic Result Management</h1>
            <p style="color: #636e72;">Step 1: Select an Exam Type globally, then filter by Year, Class, and Subject.</p>
        </div>

        <div class="exam-grid">
            <?php while($e = mysqli_fetch_assoc($exams)) { 
                $isActive = ($exam_id == $e['id']);
            ?>
                <a href="?exam_id=<?php echo $e['id']; ?>&year=<?php echo $year; ?>&class_id=<?php echo $class_id; ?>&subject=<?php echo $subject; ?>" style="text-decoration: none; color: inherit;">
                    <div class="exam-card <?php echo $isActive ? 'active' : ''; ?>">
                        <div style="width: 60px; height: 60px; background: #f1f2f6; border-radius: 15px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #6c5ce7;">
                            <i class="fa-solid <?php echo ($e['type_name'] == 'Final' || $e['type_name'] == 'Midterm') ? 'fa-star' : 'fa-list-check'; ?>"></i>
                        </div>
                        <h3 style="margin-bottom: 5px;"><?php echo $e['type_name']; ?></h3>
                        <p style="color: #b2bec3; font-size: 0.85rem; font-weight: 600;"><?php echo $e['category']; ?> Category</p>
                    </div>
                </a>
            <?php } ?>
        </div>

        <?php if($exam_id): ?>
            <form method="GET" action="" class="filter-panel">
                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                <div>
                    <label>Academic Year</label>
                    <select name="year" onchange="this.form.submit()">
                        <?php for($y = date('Y')+1; $y >= 2020; $y--) { ?>
                            <option value="<?php echo $y; ?>" <?php if($year == $y) echo 'selected'; ?>><?php echo $y; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label>Target Class</label>
                    <select name="class_id" onchange="this.form.submit()" required>
                        <option value="">Select Class</option>
                        <?php mysqli_data_seek($classes, 0); while($c = mysqli_fetch_assoc($classes)) { ?>
                            <option value="<?php echo $c['id']; ?>" <?php if($class_id == $c['id']) echo 'selected'; ?>><?php echo $c['class_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label>Subject</label>
                    <select name="subject" onchange="this.form.submit()" required>
                        <option value="">Select Subject</option>
                        <?php foreach($fixed_subjects as $sub) { ?>
                            <option value="<?php echo $sub; ?>" <?php if($subject == $sub) echo 'selected'; ?>><?php echo $sub; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </form>

            <?php if($class_id && $subject): ?>
                <?php if($context): ?>
                    <div style="background: #e8f8f5; border: 1px solid #00b894; padding: 30px; border-radius: 20px; text-align: center;">
                        <h2 style="color: #00b894; margin-bottom: 10px;">Ready to Manage Marks</h2>
                        <p style="color: #636e72; margin-bottom: 25px;">You are working on: <strong><?php echo $subject; ?></strong> for <strong><?php echo $year; ?></strong>.</p>
                        <div style="display: flex; gap: 15px; justify-content: center;">
                            <?php if($context['is_locked']): ?>
                                <span style="background: #fdeded; color: #d63031; padding: 12px 30px; border-radius: 12px; font-weight: 700;"><i class="fa-solid fa-lock"></i> Results Locked</span>
                                <a href="admin_marks.php?context_id=<?php echo $context['id']; ?>" class="submit-btn" style="width: auto; background: #f1f2f6; color: #2d3436;">View Results</a>
                            <?php else: ?>
                                <a href="insert_marks.php?context_id=<?php echo $context['id']; ?>" class="submit-btn" style="width: auto; padding: 12px 40px;">Open Mark Entry Form</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="background: #fff; padding: 40px; border-radius: 20px; text-align: center; border: 2px dashed #ddd;">
                        <i class="fa-solid fa-folder-plus" style="font-size: 3rem; color: #6c5ce7; margin-bottom: 20px;"></i>
                        <h2>New Result Entry Session</h2>
                        <p style="color: #636e72; margin-bottom: 30px;">Click below to initialize results for this specific combination.</p>
                        <form method="POST" action="insert_marks.php?init=1">
                            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                            <input type="hidden" name="year" value="<?php echo $year; ?>">
                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                            <input type="hidden" name="subject" value="<?php echo $subject; ?>">
                            <button type="submit" class="submit-btn" style="width: auto; padding: 12px 50px;">Initialize & Enter Marks</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 100px; color: #b2bec3;">
                <i class="fa-solid fa-arrow-up" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.3;"></i>
                <h2>Please select an Exam Type to start.</h2>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
