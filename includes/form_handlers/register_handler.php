
<?php 
//declaring variables to prevent errors
$f_name = "";//first name
$l_name = "";//last name
$em = "";//email
$em2 = "";//email
$password ="";//password
$password2 ="";//password 2
$date = "";//sign up date
$error_array = array();//hold error messages

if (isset($_POST['register_button'])) {
//registration form values

	//first name
	$f_name = strip_tags($_POST['reg_fname']); //remove html tags for security reasons
	$f_name = str_replace(' ', '', $f_name); //remove spaces
	$f_name = ucfirst(strtolower($f_name));//uppercase letters first only
	$_SESSION['reg_fname'] = $f_name; //stores first name into the session variable

	//first name
	$l_name = strip_tags($_POST['reg_lname']); //remove html tags for security reasons
	$l_name = str_replace(' ', '', $l_name); //remove spaces
	$l_name = ucfirst(strtolower($l_name));//uppercase letters first only
	$_SESSION['reg_lname'] = $l_name; // stores last name into the session variable

	//email
	$em = strip_tags($_POST['reg_email']); //remove html tags for security reasons
	$em = str_replace(' ', '', $em); //remove spaces
	$em = ucfirst(strtolower($em));//uppercase letters first only
	$_SESSION['reg_email'] = $em; //stores email into the session variable

	//email2
	$em2 = strip_tags($_POST['reg_email2']); //remove html tags for security reasons
	$em2 = str_replace(' ', '', $em2); //remove spaces
	$em2 = ucfirst(strtolower($em2));//uppercase letters first only
	$_SESSION['reg_email2'] = $em2; //stores email into the session variable


		//email
	$password = strip_tags($_POST['reg_password']); //remove html tags for security reasons
	$password2 = strip_tags($_POST['reg_password2']); //remove html tags for security reasons

	$date = date("Y-m-d");//current date

if($em == $em2) {
		//Check if email is in valid format 
		if(filter_var($em, FILTER_VALIDATE_EMAIL)) {
 
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);
 
			//Check if email already exists 
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");
 
			//Count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);
 
			if($num_rows > 0) {
				array_push($error_array, "Email already in use<br>");
			}
 
		}
		else {
			array_push($error_array, "Invalid email format<br>");
		}
 
 
	}
	else {
		array_push($error_array, "Emails don't match<br>");//this function pushes the error message to an error array
	}

		if (strlen($f_name)>25 || strlen($f_name)<2) {
			array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
		}

		if (strlen($l_name)>25 || strlen($l_name)<2) {
			array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
		}  
		if ($password != $password2) {
			array_push($error_array, "Your passwords do not match<br>"); 
		}
		else{
			if (preg_match('/[^A-Za-z0-9]/', $password)) {
				array_push($error_array, "Your password can only contain english characters and numbers<br>");
			}
		}
		if (strlen($password) > 30 || strlen($password) < 5) {
			array_push($error_array, "Your password must be between 5 and 30 characters<br>");
		}

		if (empty($error_array)) {
			$password = md5($password); //encrypt the password before sending to the database
			//generate username for the customer by concatenating first name and last name
			$username = strtolower($f_name."_".$l_name);
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
			$i = 0;
			//if username exists add number to username
			while(mysqli_num_rows($check_username_query) != 0){
				$i++;//Add one to i
				$username = $username . "_" . $i;
				$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
			}

			//profile picture assignment
			$rand = rand(1,2);//random number between one and two
			if ($rand==1)
				$profile_pic="assets/images/profile_pics/defaults/head_alizarin.png";
			elseif ($rand==2) 
				$profile_pic="assets/images/profile_pics/defaults/head_amethyst.png";

			$query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$f_name' , '$l_name', '$username', '$em', '$password', '$date' , '$profile_pic', '0' , '0', 'no' , ',')");

			array_push($error_array, "<span style ='color:#14c800;'>You are all set,go ahead and log in!</span>");
			//clear session variable after the form is submitted
			$_SESSION['reg_fname'] = "";
			$_SESSION['reg_lname'] = "";
			$_SESSION['reg_email'] = "";
			$_SESSION['reg_email2'] = "";
		}
	}

	 ?>