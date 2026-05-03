<?php
	#error_reporting(0);
	session_start();
	session_destroy();

	$host="localhost";
    $user="root";
    $password="";
    $db="sgs";

    $conn=mysqli_connect($host,$user,$password,$db);

    $otp2=$_SESSION['otp'];
	if (isset($_POST['apply2'])) {

    	$otp=$_POST['otp1'];

		if ($otp=$otp2) {
				$pass=$_POST['pass1'];

			$sql="UPDATE studentlist SET password='$pass' WHERE otp='$otp'";
			$res=mysqli_query($conn,$sql);

			if($res) {
				
			echo '<script language="javascript">';
            echo 'alert("Password changed successfully")';
            echo '</script>';
			header("location:student_login.php");
			}
		
		}
		else{
			echo '<script language="javascript">';
            echo 'alert("OTP Not Matched")';
            echo '</script>';
		}
	}
	else{
		
		#header("location:change_student_pass.php");
	}
?>
<html>
<head>
	<title>Enter OTP</title>
	<link rel="stylesheet" type="text/css" href="css/admin-style.css">
	<!-- Font Awesome for Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style type="text/css">
		label
		{
			display: inline-block;
			text-align: right;
			width: 100px;
			padding-top: 10px;
			padding-bottom: 10px;
			font-size: 16px;
		}
		.btn
		{
			padding: 5px 10px;
			background: #fff;
			border-color: #de3163;
			border-radius: 30px;
		}
		.btn:hover{
			background: #de3163;
			color: #fff;
			border-radius: 30px;
		}
		.div_deg
		{
			background-color: #fcf4a3;
			width: 500px;
			padding-top: 70px;
			padding-bottom: 50px;
			border-radius: 40px;
		}

		/* Password Toggle Styles */
		.password-container {
			position: relative;
			width: 100%;
			max-width: 250px;
			margin: 0 auto;
		}

		.password-container input {
			padding-right: 40px;
			width: 100%;
		}

		.toggle-password {
			position: absolute;
			right: 10px;
			top: 50%;
			transform: translateY(-50%);
			cursor: pointer;
			color: #636e72;
			font-size: 1rem;
			transition: all 0.3s;
			z-index: 10;
		}

		.toggle-password:hover {
			color: #de3163;
		}


		/* Back Navigation Styles */
		.back-nav {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 35px;
			height: 35px;
			background: #fff;
			border-radius: 50%;
			color: #2d3436;
			text-decoration: none;
			margin-right: 15px;
			transition: all 0.3s;
			cursor: pointer;
			border: 1px solid #ddd;
			outline: none;
		}

		.back-nav:hover {
			background: #de3163;
			color: #fff;
			transform: translateX(-3px);
			border-color: #de3163;
		}
	</style>

</head>
    <body>
		<div style="padding: 20px;">
			<button class="back-nav" onclick="goBack()" title="Go Back"><i class="fa-solid fa-arrow-left"></i></button>
		</div>

    	<!----------------------------------------sidebar code------------------------------------->
      	<?php
      	#include 'index_sidebar.php';
      	?>
        <!------------------------------------------------------------------------------------------>
        <div class="content">
		<center>
			<br><br>
		<h1>Enter OTP</h1><br>
        <div class="div_deg">
		<form action="#" method="POST">
			<div class="adm_int">
				<label class="label_text">Enter OTP</label>
				<input class="input_deg" type="number" name="otp1" value="<?php $otp2?>">
			</div>
			<div class="adm_int">
				<label class="label_text">New Password</label>
				<div class="password-container">
					<input class="input_deg" type="password" name="pass1" id="password" required>
					<i class="fa-solid fa-eye toggle-password" id="toggleIcon" onclick="togglePassword('password', 'toggleIcon')"></i>
				</div>
			</div>

            
			<div class="adm_int">
				<input class="btn" type="Submit" id="submit" value="Submit" name="apply2">
			</div>
		</form>
        </center>
        </div>

	<script>
	function togglePassword(inputId, iconId) {
		const passwordInput = document.getElementById(inputId);
		const toggleIcon = document.getElementById(iconId);
		
		if (passwordInput.type === "password") {
			passwordInput.type = "text";
			toggleIcon.classList.remove("fa-eye");
			toggleIcon.classList.add("fa-eye-slash");
		} else {
			passwordInput.type = "password";
			toggleIcon.classList.remove("fa-eye-slash");
			toggleIcon.classList.add("fa-eye");
		}
	}
	function goBack() {
		if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
			window.history.back();
		} else {
			window.location.href = 'index.php';
		}
	}
	</script>

</body>

</html>
