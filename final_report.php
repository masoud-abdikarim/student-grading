<?php
session_start();
error_reporting(E_ALL);

if(!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}

$host="localhost"; $user="root"; $password=""; $db="sgs";
$conn=mysqli_connect($host,$user,$password,$db);
include 'subjects.php';

$enroll = $_GET['enroll'] ?? $_SESSION['username'];
$year = $_GET['year'] ?? date('Y');

// Fetch student info
$stu_sql = "SELECT s.*, c.class_name FROM studentlist s LEFT JOIN classes c ON s.class_id = c.id WHERE s.enroll='$enroll'";
$stu_data = mysqli_fetch_assoc(mysqli_query($conn, $stu_sql));
$class_id = $stu_data['class_id'];

// Fetch results using context join
$results_sql = "SELECT r.*, ec.subject, ec.pass_mark 
                FROM results r 
                JOIN exam_context ec ON r.context_id = ec.id 
                JOIN exam e ON ec.exam_id = e.id 
                WHERE r.enroll='$enroll' AND ec.year='$year' AND e.type_name='Final'";
$results_res = mysqli_query($conn, $results_sql);
$marks = [];
$pass_marks = [];
while($r = mysqli_fetch_assoc($results_res)) {
    $marks[$r['subject']] = $r['marks'];
    $pass_marks[$r['subject']] = $r['pass_mark'];
}

$total_marks = 0;
$failed_subjects = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Annual Report | SGS</title>
    <?php include 'shared_styles.php'; ?>
    <style>
        .report-card { background: #fff; padding: 40px; border-radius: 30px; box-shadow: 0 15px 50px rgba(0,0,0,0.08); max-width: 1000px; margin: 0 auto; }
        .status-badge { font-size: 2rem; padding: 15px 40px; border-radius: 50px; font-weight: 800; display: inline-block; margin-top: 20px; }
        .pass { background: #e8f8f5; color: #00b894; border: 2px solid #00b894; }
        .fail { background: #fdeded; color: #d63031; border: 2px solid #d63031; }
    </style>
</head>
<body>
    <?php 
    if($_SESSION['usertype'] == 'admin') include 'admin_sidebar.php';
    else include 'student_sidebar.php';
    ?>
    <div class="content">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1>Annual Performance Report</h1>
            <p style="color: #636e72;">Academic Year: <strong><?php echo $year; ?></strong> | Class: <strong><?php echo $stu_data['class_name']; ?></strong></p>
        </div>
        <div class="report-card">
            <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #f1f2f6; padding-bottom: 20px; margin-bottom: 30px;">
                <div>
                    <h3 style="color: #6c5ce7;"><?php echo $stu_data['firstname'] . " " . $stu_data['lastname']; ?></h3>
                    <p style="color: #636e72;">Enrollment: <?php echo $stu_data['enroll']; ?></p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th style="text-align: center;">Pass Mark</th>
                        <th style="text-align: center;">Marks</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($fixed_subjects as $subject): 
                        $m = $marks[$subject] ?? 0;
                        $pm = $pass_marks[$subject] ?? 50;
                        $total_marks += $m;
                        $is_fail = $m < $pm;
                        if($is_fail) $failed_subjects++;
                    ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo $subject; ?></td>
                        <td style="text-align: center; color: #b2bec3;"><?php echo $pm; ?></td>
                        <td style="text-align: center; font-weight: 700;"><?php echo $m; ?></td>
                        <td style="text-align: center;">
                            <span style="color: <?php echo $is_fail ? '#d63031' : '#00b894'; ?>; font-weight: 700;">
                                <?php echo $is_fail ? 'FAIL' : 'PASS'; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div style="background: #f1f2f6; padding: 25px; border-radius: 20px; text-align: center;">
                    <p style="color: #636e72;">Overall Average</p>
                    <?php $average = $total_marks / 7; ?>
                    <h2 style="font-size: 2.5rem; margin: 0;"><?php echo number_format($average, 1); ?>%</h2>
                </div>
                <div style="background: #f1f2f6; padding: 25px; border-radius: 20px; text-align: center;">
                    <p style="color: #636e72;">Failed Subjects</p>
                    <h2 style="font-size: 2.5rem; margin: 0; color: <?php echo ($failed_subjects > 2) ? '#d63031' : '#2d3436'; ?>;"><?php echo $failed_subjects; ?></h2>
                </div>
            </div>
            <div style="text-align: center; margin-top: 50px;">
                <?php 
                    $is_passed = ($average >= 50 && $failed_subjects <= 2);
                    if($is_passed):
                ?>
                    <div class="status-badge pass">PASSED</div>
                    <p style="margin-top: 15px; color: #00b894; font-weight: 600;">Congratulations!</p>
                <?php else: ?>
                    <div class="status-badge fail">FAILED</div>
                    <p style="margin-top: 15px; color: #d63031; font-weight: 600;">Academic Review Required.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
