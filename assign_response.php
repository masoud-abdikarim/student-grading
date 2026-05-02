<?php
error_reporting(0);
session_start();
    if(!isset($_SESSION['username']))
    {
        header("location:index.php");
    }
    elseif($_SESSION['usertype']=='admin')
    {
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
        $class_filter = "WHERE a.class_id='$cid'";
    }

    $sql="SELECT a.*, c.class_name FROM assignment a LEFT JOIN classes c ON a.class_id = c.id $class_filter ORDER BY a.subject";
    $result=mysqli_query($conn,$sql);

    $classes_query = "SELECT * FROM classes";
    $classes_result = mysqli_query($conn, $classes_query);

?>
<html>
<head>
    <title>Teacher panel</title>
    <link rel="stylesheet" type="text/css" href="css/admin-style.css">
    <style type="text/css">
        table
        {
            border-radius: 10px;
        }
        .table_th
        {
            padding: 20px;
            font-size: 20px;
            background-color: #de3163;
            border-radius: 10px;
        }

        .table_td
        {
            padding: 20px;
            background-color: #fcf4a3;
            border-radius: 10px;
        }
    </style>
</head>
    <body>
        <!----------------------------------------sidebar code------------------------------------->
        <?php
        include 'teacher_sidebar.php';
        ?>
        <!------------------------------------------------------------------------------------------>
        <div class="content">
            <center>
            <h1>Assignment Details</h1>

            <?php
                if($_SESSION['message'])
                {
                    echo $_SESSION['message'];
                }

                unset($_SESSION['message']);
            ?>

            <br>
            <form method="GET" action="">
                <select name="class_id" style="padding: 5px; font-size: 16px;">
                    <option value="">All Classes</option>
                    <?php while($c = mysqli_fetch_assoc($classes_result)) { ?>
                        <option value="<?php echo $c['id']; ?>" <?php if(isset($_GET['class_id']) && $_GET['class_id'] == $c['id']) echo 'selected'; ?>><?php echo $c['class_name']; ?></option>
                    <?php } ?>
                </select>
                <input type="submit" value="Filter" class="btn" style="color: black; text-decoration: none;">
            </form>
            <br>
            <table >
                <tr>
                    <th class="table_th">Class</th>
                    <th class="table_th">Subject Name</th>
                    <th class="table_th">Assignment</th>
                    <th class="table_th">Due Date</th>
                    <th class="table_th">View</th>
                    <th class="table_th">View Response</th>
                    <th class="table_th">Delete</th>
                    <th class="table_th">Response</th>
                </tr>
                <?php
                    while ($info=$result->fetch_assoc()) {
                        
                    
                ?>
                <tr>
                    <td class="table_td"><?php echo "{$info['class_name']}"; ?></td>
                    <td class="table_td"><?php echo "{$info['subject']}"; ?></td>
                    <td class="table_td"><?php echo "{$info['assignment']}"; ?></td>
                    <td class="table_td"><?php echo "{$info['duedate']}"; ?></td>
                    <td class="table_td"><a class='btn' href='Assignment/<?php echo "{$info['file']}"; ?>'>View<a>
                    <!--<a class='btn' download="<?php echo "{$info['file']}"; ?>" href='Assignment/<?php echo "{$info['file']}"; ?>'>Download<a> -->
                    </td>
                    <td class="table_td"><?php echo "<a class='btn' href='view_responses.php?assign_id={$info['id']}'>View Response</a>"; ?></td>
                    <td class="table_td"><?php echo "<a class='btn' onClick=\"javascript:return confirm('Are You Sure to Delete this ?')\" href='delete_assign.php?assign_id={$info['id']}&file_id={$info['file']}'> Delete </a>"; ?></td>
                    <td class="table_td"><?php
                            $tab_id="{$info['id']}";
                            $tabcount="SELECT * FROM assignment_responses WHERE assignment_id='$tab_id'";
                            $recounttab=mysqli_query($conn,$tabcount);
                            $rowstab=mysqli_num_rows($recounttab);
                            echo $rowstab; ?></td>
                </tr>
                <?php
                    }
                ?>
            </center>
            </table>
        </div>
    </body>
</html>
