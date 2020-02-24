<?php 
	class User{
		private $user; //this means its only available to this class
		private $con;

		public function __construct($con, $user){ //this constructor is what is called when the user creates an object of the user class
			$this->con=$con; //this references the variables or properties of this particular class
			$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$user'" );
			$this -> user = mysqli_fetch_array($user_details_query);
		}

		public function getUsername(){
			return $this->user['username'];
		}

		public function getNumPosts(){
			$username = $this -> user['username'];
			$query = mysqli_query($this->con,"SELECT num_posts FROM users WHERE username='$username'");
			$row = mysqli_fetch_array($query);
			return $row['num_posts'];
		}

		public function getFirstAndLastName(){
			$username = $this -> user['username'];
			$query = mysqli_query($this ->con, "SELECT first_name, last_name FROM users WHERE username='$username'"); //selecting just what we need will make out project faster
			$row = mysqli_fetch_array($query);
			return $row['first_name'] . " " . $row['last_name'];
		}

		public function getProfilePic(){
			$username = $this -> user['username'];
			$query = mysqli_query($this->con, "SELECT profile_pic FROM users WHERE username='$username'"); //selecting just what we need will make out project faster
			$row = mysqli_fetch_array($query);
			return $row['profile_pic'];

		}

		public function isClosed(){
			$username = $this -> user['username'];
			$query = mysqli_query($this -> con, "SELECT user_closed FROM users WHERE username='$username'");
			$row = mysqli_fetch_array($query);

			if($row['user_closed'] == 'yes')
				return true;
			else {
				return false;
			}
		}

		public function isFriend($username_to_check){
			$usernameComma = "," . $username_to_check . ",";
			if (strstr($this->user['friend_array'], $usernameComma) || $username_to_check == $this->user['username']) { //to check if a string is in another string
				return true;
			}else{
				return false;
			}
		}


	}


 ?>