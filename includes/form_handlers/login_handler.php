<?php 
	if (isset($_POST['login_button'])) {
		$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);//sanitize email

		$_SESSION['log_email'] = $email; //this stores the email into the session variable
		$password = md5($_POST['log_password']); //get harshed password
		$check_database_query = mysqli_query($con , "SELECT * FROM users WHERE email = '$email' AND password = '$password'");
		$check_login_query = mysqli_num_rows($check_database_query);//returns the number of rows which should be one as per query
			
			if ($check_login_query == 1) { //if there is a result
				$row = mysqli_fetch_array($check_database_query);//gets the result array from the query and saves it as a row
				$username = $row['username']; //to assess the username column from the row as usual
				
				$user_closed_query= mysqli_query($con, "SELECT * FROM users WHERE email = '$email' AND user_closed='yes'");//this code for reopening closed account
				if (mysqli_num_rows($user_closed_query) == 1) {
					$reopen_account = mysqli_query($con, "UPDATE users SET user_closed = 'no'  WHERE email='$email'");
				}


				$_SESSION['username'] = $username;//storing the username in the session..if its nil that means the user is now logged in...we use this to check the user log in status
				header("Location: index.php");
				exit();
			}else{

				array_push($error_array, "Email or password was incorrect<br>");
			}
	}



 ?>