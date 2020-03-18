<?php 
	class Message{
		private $user_obj; //this means its only available to this class
		private $con;

		public function __construct($con, $user){ //this constructor is what is called when the user creates an object of the user class
			$this->con=$con; //this references the variables or properties of this particular class
			$this->user_obj = new User($con, $user);//we are making an instance of the users class within
		}//end of constructor
	
	public function getMostRecentUser(){
		$userLoggedIn = $this->user_obj->getUsername(); 
		$query = mysqli_query($this->con, 
			"SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1")
	
		if (mysqli_num_rows($query)==0) 
			return  false;

			$row=mysqli_fetch_array($query);
			$user_to = $row['user_to'];
			$user_from = $row['user_from'];

			if ($user_to != $userLoggedIn) 
				return $user_to;
			else 
				return $user_from;
			
		
	}
	

	}//end of the post class
 ?>