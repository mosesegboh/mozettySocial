<?php 
require'config/config.php';
require'includes/form_handlers/register_handler.php';//this camae on top so thagt when login in the errors array can be handled appropraiately
require'includes/form_handlers/login_handler.php';

 ?>

<html>
<head>
	<title>Welcome to Mozetty Social</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
</head>
	<body>
		<!-- displaying the error properly because of jquery show and hide feature in the login form -->
		<?php 
			if (isset($_POST['register_button'])) {
				echo '
					<script>
						$(document).ready(function(){
							$("#first").hide();
							$("#second").show();
						})
					</script>
				';	
			}


		 ?>
		<div class="wrapper">
			<div class="login_box">
				<div class="login_header">
					<h1>Mozetty Feed</h1>
					Login or sign up below!
				</div>
				<div id="first">
					<form action = "register.php" method = "POST">
						<input type = "email" name="log_email" placeholder="Email Address" value="<?php 
							if (isset($_SESSION['log_email'])) {
								echo $_SESSION['log_email'];
							}
						 ?>" required/> <!-- the required attribute will not let you submit anything until the input feild is entered -->
						<br>
						<input type = "Password" name="log_password" placeholder="Password">
						<br>
						<input type = "submit" name= "login_button" value="login">
						<?php if (in_array("Email or password was incorrect<br>", $error_array)) echo "Email or password was incorrect<br>" ; ?> 
						<br>
						<a href="#" id= "signup" class= "signup">Need an Account? Register here</a>
					</form>
				</div>
				
				<div id="second">
					<form action="register.php" method = "POST">
						<input type="text" name="reg_fname" placeholder="First Name" value="<?php 
							if ($_SESSION['reg_fname']) {
								echo $_SESSION['reg_fname'];
							}
						 ?>" required>
						<br>
						<?php if (in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) {echo "Your first name must be between 2 and 25 characters<br>";}?>

						<input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
							if ($_SESSION['reg_lname']) {
								echo $_SESSION['reg_lname'];
							}
						 ?>" required>
						<br>
						<?php if (in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) {echo "Your last name must be between 2 and 25 characters<br>";} ?>
						<input type="email" name="reg_email" placeholder="Email" value="<?php 
							if ($_SESSION['reg_email']) {
								echo $_SESSION['reg_email'];
							}
						 ?>"required>
						<br>
						<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
							if ($_SESSION['reg_email2']) {
								echo $_SESSION['reg_email2'];
							}
						 ?>" required>
						<br>
						<?php if (in_array("Email already in use<br>", $error_array)) {echo "Email already in use<br>";}
						elseif (in_array("invalid email format<br>", $error_array)) {echo "invalid email format<br>";}
						elseif (in_array("Emails don't match<br>", $error_array)) {echo "Emails don't match<br>";} ?>

						<input type="password" name="reg_password" placeholder="Password" required>
						<br>
						<input type="password" name="reg_password2" placeholder="Confirm Password" required>
						<br>

						<?php if (in_array("Your passwords do not match<br>", $error_array)) {echo "Your passwords do not match<br>";}
						elseif (in_array("Your password can only contain english characters and numbers<br>", $error_array)) {echo "Your password can only contain english characters and numbers<br>";}
						elseif (in_array("Your password must be between 5 and 30 characters<br>", $error_array)) {echo "Your password must be between 5 and 30 characters<br>";} ?>

						<input type = "submit" name= "register_button" value="Register">
						<br>
						<?php if (in_array("<span style ='color:#14c800;'>You are all set,go ahead and log in!</span>", $error_array)) {echo "<span style ='color:#14c800;'>You are all set,go ahead and log in!</span>";} ?>
						<a href="#" id= "signin" class= "signin">Aready have an Account? Login here</a>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>