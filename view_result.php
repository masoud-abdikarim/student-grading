<?php
session_start();
error_reporting(E_ALL);
include 'subjects.php';

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} elseif(isset($_SESSION['usertype']) && $_SESSION['usertype']=='admin') {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);

$student_enroll = $_SESSION['username'];
$exam_type_id = $_GET['exam_type_id'] ?? '';
$selected_year = $_GET['year'] ?? '';

// Fetch student's class for filtering
$stu_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT class_id FROM studentlist WHERE enroll='$student_enroll'"));
$class_id = $stu_data['class_id'];

// Get unique exam types that HAVE results for this student
$types_query = "SELECT DISTINCT e.id, e.type_name, e.category 
                FROM results r 
                JOIN exam_context ec ON r.context_id = ec.id 
                JOIN exam e ON ec.exam_id = e.id 
                WHERE r.enroll='$student_enroll'";
$types_res = mysqli_query($conn, $types_query);

// Get years for selected type
$years = [];
if($exam_type_id) {
    $years_query = "SELECT DISTINCT ec.year 
                    FROM results r 
                    JOIN exam_context ec ON r.context_id = ec.id 
                    WHERE r.enroll='$student_enroll' AND ec.exam_id='$exam_type_id'
                    ORDER BY ec.year DESC";
    $years_res = mysqli_query($conn, $years_query);
    while($y = mysqli_fetch_assoc($years_res)) $years[] = $y['year'];
}

// Get results for selected type and year
$results = [];
if($exam_type_id && $selected_year) {
    $res_query = "SELECT r.marks, ec.subject, ec.pass_mark 
                  FROM results r 
                  JOIN exam_context ec ON r.context_id = ec.id 
                  WHERE r.enroll='$student_enroll' AND ec.exam_id='$exam_type_id' AND ec.year='$selected_year'";
    $res_data = mysqli_query($conn, $res_query);
    while($row = mysqli_fetch_assoc($res_data)) $results[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Academic Results | SGS</title>
    <?php include 'shared_styles.php'; ?>
    <style>
        .type-card {
            background: #fff; padding: 25px; border-radius: 20px; text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.3s ease;
            border: 2px solid transparent; text-decoration: none; color: inherit; display: block;
        }
        .type-card:hover { transform: translateY(-5px); border-color: #6c5ce7; }
        .type-card.active { background: #f8faff; border-color: #6c5ce7; }
        
        .year-btn {
            padding: 10px 25px; border-radius: 50px; background: #fff; border: 1px solid #ddd;
            color: #636e72; text-decoration: none; font-weight: 600; transition: all 0.2s;
        }
        .year-btn:hover { background: #f1f2f6; }
        .year-btn.active { background: #6c5ce7; color: #fff; border-color: #6c5ce7; }

        .summary-box {
            background: #fff; padding: 30px; border-radius: 25px; box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            margin-top: 30px; border-top: 5px solid #6c5ce7;
        }
        .stat-card { background: #f8faff; padding: 20px; border-radius: 15px; text-align: center; }
    </style>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>
    <div class="content">
        <div style="margin-bottom: 40px;">
            <h1>My Academic Performance</h1>
            <p style="color: #636e72;">Explore your results organized by assessment type and year.</p>
        </div>

        <!-- Step 1: Exam Types -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <?php while($t = mysqli_fetch_assoc($types_res)) { ?>
                <a href="?exam_type_id=<?php echo $t['id']; ?>" class="type-card <?php echo ($exam_type_id == $t['id']) ? 'active' : ''; ?>">
                    <div style="font-size: 2rem; margin-bottom: 10px;">
                        <?php echo ($t['type_name'] == 'Final' || $t['type_name'] == 'Midterm') ? '🏆' : '📝'; ?>
                    </div>
                    <h3 style="margin: 0;"><?php echo $t['type_name']; ?></h3>
                </a>
            <?php } ?>
        </div>

        <?php if($exam_type_id): ?>
            <!-- Step 2: Years -->
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 15px; color: #2d3436;">Select Academic Year</h3>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <?php foreach($years as $y): ?>
                        <a href="?exam_type_id=<?php echo $exam_type_id; ?>&year=<?php echo $y; ?>" class="year-btn <?php echo ($selected_year == $y) ? 'active' : ''; ?>">
                            Year <?php echo $y; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if($exam_type_id && $selected_year): ?>
            <!-- Step 3: Detailed Results -->
            <div class="summary-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h2>Performance Summary (<?php echo $selected_year; ?>)</h2>
                    <a href="final_report.php?year=<?php echo $selected_year; ?>" class="table-btn" style="background: #e8f8f5; color: #00b894; padding: 10px 20px;">Download Full Report</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th style="text-align: center;">Marks</th>
                            <th style="text-align: center;">Pass Mark</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; $fails = 0; $count = 0;
                        foreach($results as $res): 
                            $total += $res['marks'];
                            $is_fail = $res['marks'] < $res['pass_mark'];
                            if($is_fail) $fails++;
                            $count++;
                        ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo $res['subject']; ?></td>
                            <td style="text-align: center; font-weight: 800; font-size: 1.1rem;"><?php echo $res['marks']; ?></td>
                            <td style="text-align: center; color: #b2bec3;"><?php echo $res['pass_mark']; ?></td>
                            <td style="text-align: right;">
                                <span style="font-weight: 700; color: <?php echo $is_fail ? '#d63031' : '#00b894'; ?>;">
                                    <?php echo $is_fail ? 'FAIL' : 'PASS'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 30px;">
                    <div class="stat-card">
                        <p style="color: #636e72; font-size: 0.85rem; margin-bottom: 5px;">Average Score</p>
                        <?php $avg = ($count > 0) ? ($total / 7) : 0; // Standard 7 subjects ?>
                        <h2 style="margin: 0; color: #6c5ce7;"><?php echo number_format($avg, 1); ?>%</h2>
                    </div>
                    <div class="stat-card">
                        <p style="color: #636e72; font-size: 0.85rem; margin-bottom: 5px;">Failed Subjects</p>
                        <h2 style="margin: 0; color: <?php echo ($fails > 2) ? '#d63031' : '#2d3436'; ?>;"><?php echo $fails; ?></h2>
                    </div>
                    <div class="stat-card">
                        <p style="color: #636e72; font-size: 0.85rem; margin-bottom: 5px;">Final Standing</p>
                        <?php $is_passed = ($avg >= 50 && $fails <= 2); ?>
                        <h2 style="margin: 0; color: <?php echo $is_passed ? '#00b894' : '#d63031'; ?>;">
                            <?php echo $is_passed ? 'PASSED' : 'FAILED'; ?>
                        </h2>
                    </div>
                </div>
            </div>
        <?php elseif($exam_type_id): ?>
             <div style="text-align: center; padding: 60px; background: #fff; border-radius: 20px; color: #b2bec3;">
                <i class="fa-solid fa-calendar-days" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.3;"></i>
                <h3>Select a year to see your detailed performance.</h3>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 80px; background: #fff; border-radius: 20px; color: #b2bec3;">
                <i class="fa-solid fa-graduation-cap" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.3;"></i>
                <h2>Choose an Exam Type to begin.</h2>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
