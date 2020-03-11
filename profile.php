<?php 
include("includes/header.php");

//session_destroy();//destroys our session when user not logged in

if (isset($_GET['profile_username'])) { //getting the profile username from the url when profile is clicked
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends =(substr_count($user_array['friend_array'], ",")) -1;//the -1 shows the true amount of friends the user has when creating the table,
	}

	//handle remove friend request here
	if (isset($_POST['remove_friend'])) {
		$user = new User($con, $userLoggedIn);
		$user->removeFriend($username);
	}

	//handle add friend request here
	if (isset($_POST['add_friend'])) {
		$user = new User($con, $userLoggedIn);
		$user->sendRequest($username);
	}

	//redirect to the appropriate page
	if (isset($_POST['respond_request'])) {
		header("Location: requests.php ");
	}
 ?>

 <style type="text/css">
 	.wrapper{
 		margin-left: 0px;
 		padding-left: 0px;
 	}
 </style>

<div class = "profile_left">
	<img src="<?php echo $user_array['profile_pic']; ?>">
	
	<div class="profile_info">
		<p><?php echo "Posts: " .$user_array['num_posts']; ?></p>
		<p><?php echo "Likes: " .$user_array['num_likes']; ?></p>
		<p><?php echo "Friends: " .$num_friends ?></p>
	</div>
	<form action = "<?php echo $username; ?>" method="POST">
		<?php $profile_user_obj = new User($con, $username); //redirect user if account is closed
		if ($profile_user_obj->isClosed()) {
			header("Location: user_closed.php");
		}
		$logged_in_user_obj = new User($con, $userLoggedIn);
		if($userLoggedIn != $username){ //if its not the user viewing the page?
			if ($logged_in_user_obj->isFriend($username)) {//if user viewing is friend
				echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
			}
			else if ($logged_in_user_obj -> didReceiveRequest($username)) {
				echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
			}
			else if ($logged_in_user_obj -> didSendRequest($username)) {
				echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
			}
			else
				echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
		}

		?>
	</form>
	<input type= "submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something">
</div>
	<div class="main_column column">
		<?php echo $username ?>



	</div>



	

<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title" id="myModalLabel">Post Something</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>

      <div class="modal-body">
        <p>This will appear on the users profile page and theis news feed for your friends to see!</p>
      	<form class= "profile_post" action="" method="POST">
      		<div class = "form-group">
      			<textarea class="form-control" name="post_body"></textarea>
      			<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
      			<input type="hidden" name="user_to" value="<?php echo $username; ?>">
      		</div>

      	</form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
      </div>
    </div>
  </div>
</div>			


	
			<!-- this div is from the header -->
	</div>
	</body>
</html>