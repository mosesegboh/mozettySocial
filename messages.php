<?php 
	include("includes/header.php");

	$message_obj = new Message($con, $userLoggedIn);

	if ($_GET['u']) {
		$user_to = $_GET['u'];
	}else{
		$user_to = $message_obj ->getMostRecentUser();
		if ($user_to == false) {
			$user_to = 'new';
		}
	}

if ($user_to != "new") {
	$user_to_obj = new User($con, $user_to);
}
 ?>

 <div class="user_details column"> <!-- this column will share csss styling with another div -->
	<a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?> "></a>
	<div class = "user_details_left_right">
		<a href="<?php echo $userLoggedIn; ?>">
			<?php 
				echo $user['first_name'] . " " . $user['last_name'] . "<br>";
			?>
		</a>
		 	<?php 
		 		echo "Posts:" . $user['num_posts'] . "<br>";
		 		echo "Likes:" . $user['num_likes'];
			 ?>
	</div>
</div>

<div class="main_column column" id="main_column)">
	<?php 
		if ($user_to!="new") {
			echo "<h4>You and <a href='$user_to'>". $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>" ;
		}
	 ?>

	 <div class="loaded_messages">
	 	<form action="" method="POST">
 		<?php 
 			if ($user_to == "new") {
 				echo "select the friend you would like to message <br>";
 				echo "To:<input  type='text' >";
 				echo"<div class='results'></div>";
 			}
 			else{
 				echo "<textarea name='message_body' id='message_text_area' placeholder='write your message....'></textarea>";
 				echo"<input type='submit' name='post_message'  class= 'info' id='message_submit' value='Send'>";
 			}



	 		 ?>
	 	</form>
	 </div>

</div>