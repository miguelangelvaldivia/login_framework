<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login</title>

</head>
<body>

<div id="container">
	<h1>Login</h1>
<?php
	echo form_open('main/login_validation');
	// If there are errors then this echos them out
	echo validation_errors();
		
	echo "<p>Email: ";
	echo form_input('email');
	echo "</p>";
	echo "<p>Password: ";
	echo form_password('password');
	echo "</p>";
	echo "<p>";
	echo form_submit('login_submit', 'Login');
	echo "</p>";
	$signup_url = base_url()."main/signup";
	echo "<a href=$signup_url>Sign Up</a>";
	echo form_close();
?>
</div>

</body>
</html>
