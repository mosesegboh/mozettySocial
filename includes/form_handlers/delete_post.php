<?php 
require'../../config/config.php';

if (isset($_GET['post_id'])) {
	$post_id = $_GET['post_id'];
		//this one is if they have answered the conirmation box
	if (isset($_POST['result'])) {
		if ($_POST['result'] == 'true') {
			//the post is not actally deleted in this query just setting the column to yes
			$query = mysqli_query($con, "UPDATE posts SET deleted = 'yes' WHERE id = '$post_id'");
		}
	}
}



 ?>