	<?php //bringing this to the top solves the problem of cannot modify header information error
		require'config/config.php';
		include("includes/classes/User.php");
		include("includes/classes/Post.php");
		//this will prevent not logged in user to log in
		if (isset($_SESSION['username'])) {
			$userLoggedIn = $_SESSION['username'];
			$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
			$user = mysqli_fetch_array($user_details_query);
		} else {
			header("Location: register.php");
		}
		?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
	<style type="text/css"> /*infile css for the font of the comment section*/
		*{
			font-size: 12px;
			font-family: Arial, Helvetica, Sans-serif;
		}
	</style>

		<script>
			function toggle () {
				var element = document.getElementById("comment_section");//comment section is the block
				if (element.style.display == "block")//if its showing
				element.style.display = "none";//hide it
				else
					element.style.display = "block"//if its hidden show it
			}
		</script> <!-- as of html 5 you dont need the script tag anymore -->

<?php 

	//get id of post
if(isset($_GET['post_id'])){//if the element in the above script it sent to this page as a get variable
	$post_id = $_GET['post_id'];

}

$user_query = mysqli_query($con, "SELECT added_by,user_to FROM posts WHERE id='$post_id'");
$row = mysqli_fetch_array($user_query);



$posted_to = $row['added_by'];

if (isset($_POST['postComment' . $post_id])) { //this is thesame name we placed in the form tag below
	$post_body = $_POST['post_body'];
	$post_body = mysqli_escape_string($con, $post_body);
	$date_time_now = date("Y-m-d H:i:s");

	$insert_post = mysqli_query($con, "INSERT INTO comments VALUES (NULL, '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id') "); 
		echo "<p>Comment Posted!</p>";
}

 ?>
<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST"><!-- this gets the post id of the section cliced as a get variable at  once -->
<!-- the name varible above means if post id is one the post comment will be one -->
	<textarea name="post_body"></textarea>
	<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post"> <!-- you can mix words and php eco together -->
</form>
<!-- load comments -->
<?php 
$get_comments = mysqli_query($con,"SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY id ASC");
$count = mysqli_num_rows($get_comments);
if ($count != 0) {
	while ($comment = mysqli_fetch_array($get_comments)) {//while the loop goes aroung the result from the query will be stored in the comments
		$comment_body = $comment['post_body'];
		$posted_to = $comment['posted_to'];
		$posted_by = $comment['posted_by'];
		$date_added = $comment['date_added'];
		$removed = $comment['removed'];


		//Time frame
				$date_time_now = date("Y-m-d H:i:s");
				$start_date = new DateTime($date_added);//time of post //this class comes inbuilt in php
				$end_date = new DateTime($date_time_now);//current time
				$interval = $start_date->diff($end_date);//Differnece between the 2 dates
				if ($interval->y >=1 ) { //if interval is greater than a year ago?
					if($interval ==1 )
						$time_message = $interval->y . "year ago";//this will preduce on year ago
					else
						$time_message = $interval->y . "years ago";//this will 
						//+1preduce on year ago
					}else if($interval->m >= 1){
						if ($interval->d == 0 ){
							$days =  " ago";
						}
						else if($interval->d = 1 ){
							$days = $interval->d . "days ago";
						}else{
							$days = $interval->d . "days ago";
						}

						if ($interval->m = 1 ) {
							$time_message = $interval->m . "month" . $days;
						}
						else{
							$time_message = $interval->m . "months" . $days;

						}
					}

					else if ($interval->d >=1){
						if($interval->d == 1 ){
							$time_message ="Yesterday";
						}else{
							$time_message = $interval->d . "days ago";
						}
					}
					else if ($interval->h >=1 ) {
						if($interval->h = 1 ){
							$time_message = $interval->h . "hour ago";
						}else{
							$time_message = $interval->h . "hours ago";
						}

					}else if ($interval->i >=1 ) {
							if($interval->i = 1 ){
								$time_message = $interval->i . "minute ago";
							}else{
								$time_message = $interval->i . "minutes ago";
							}
						}
					else{ 
						if ($interval->s <30) {
							$time_message = "just now";
						}else{
							$time_message = $interval->s . "seconds ago";
						}
					}
	$user_obj = new User($con, $posted_by);
	?>
	<!-- where the actual comment will be displayed, we put it inside the while loop so that is loads all comments -->
	 <div class = "comment_section">
	 	<a href="<?php  echo $posted_by; ?>" target="_parent">
	 		<img src="<?php echo $user_obj->getProfilePic(); ?>" title=" <?php echo $posted_by; ?>" style = "float:left;" height="30">
	 	</a> <!-- to make the iframe load outside  the page -->
	 	<a href="<?php  echo $posted_by; ?>" target="_parent">
	 		<b> <?php echo $user_obj->getFirstAndLastName(); ?> </b>
	 	</a>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . "<br>" .
	 	$comment_body; ?>
	 	<hr>
	 </div>

	<?php

	}//end of while loop
	
} //end of first if staement....if no comments it will show this
else{
	echo"<center><br><br>No comments to show here!</center>";
}
 ?>



</body>
</html>