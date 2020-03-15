<?php 
	class Message{
		private $user_obj; //this means its only available to this class
		private $con;

		public function __construct($con, $user){ //this constructor is what is called when the user creates an object of the user class
			$this->con=$con; //this references the variables or properties of this particular class
			$this->user_obj = new User($con, $user);//we are making an instance of the users class within
		}//end of constructor
		
	

	}//end of the post class
 ?>