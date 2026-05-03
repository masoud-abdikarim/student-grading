<?php
	error_reporting(0);
	session_start();
	session_destroy();

	if($_SESSION['message'])
	{
		$message=$_SESSION['message'];
		echo "<script type='text/javascript'>
		alert('$message')
		</script>";
	}
?>
<html>
<head>
	<title>Forget Password</title>
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
			padding-top: 50px;
			padding-bottom: 50px;
			border-radius: 40px;
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
    	<!----------------------------------------sidebar code------------------------------------->
      	<?php
      	include 'index_sidebar.php';
      	?>
        <!------------------------------------------------------------------------------------------>
        <div class="content">
		<center>
			<br><br>
		<h1>Forget password</h1><br>
        <div class="div_deg">

		<form action="f11_check.php" method="POST">
			<div class="adm_int">
				<label class="label_text">Email</label>
				<input class="input_deg" type="text" name="email" required>
			</div>
			<br>
			<div class="adm_int">
				<input class="btn" type="Submit" id="submit" value="Apply" name="apply">
			</div>
		</form>
        </center>
        </div>

	<script>
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