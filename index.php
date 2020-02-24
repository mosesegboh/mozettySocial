<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
//session_destroy();//destroys our session when user not logged in

if (isset($_POST['post'])) {
	
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['post_text'],'none' );
	header("Location: index.php");//redirects to php and prevents form resubmission
}
 ?>
<div class="user_details column"> <!-- this column will share csss styling with another div -->
	<a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?> "></a>
	<div class = "user_details_left_right">
		<a href="<?php echo $userLoggedIn; ?>">
			<?php 
				echo $user['first_name'] . " " . $user['last_name'];
			?>
		</a>
		 	<?php 
		 		echo "Posts:" . $user['num_posts'] . "<br>";
		 		echo "Likes:" . $user['num_likes'];
			 ?>
	</div>
</div>

<div class="main_column column">
	<form class="post_form" action = "index.php" method="POST">
		<textarea name="post_text"  id="post_text" placeholder = "Got somethng to say?"></textarea>
		<input type = "submit" name="post" id="post_button" value="POST">
		<hr>
	</form>
	

	 <div class="posts_area"></div>
	 <img id="loading" src="assets/images/icons/loading.gif">
</div>
		<!-- make an ajax call to the database without having to reload the page -->
	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		$(document).ready(function() {
			$('#loading').show(); //this basically shows the gif image

			//original ajax request for loading first post
			$.ajax({
				url: "includes/handlers/ajax_load_posts.php",//where is sends the call to for processing
				type: "POST",//the send type
				data: "page=1&userLoggedIn=" + userLoggedIn,//first call to load the posts the page is one..the request on the ajax file is sent here on this line
				cache: false,

				success: function(data){
					$('#loading').hide();
					$('.posts_area').html(data);//put information from the div inside the dic post area
				}
			});
			//everything was fine above...we move
			//this function is what happens when scrolling
			$(window).scroll(function(){
				var height = $('.posts_area').height();//div containing posts
				var scroll_top = $(this).scrollTop();
				var page = $('.posts_area').find('.nextPage').val();
				var noMorePosts = $('.posts_area').find('.noMorePosts').val();

				if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts =='false') {
					$('#loading').show(); 
					//you can user alert('hello') to check if the above is statment is working
					


					var ajaxReq=	$.ajax({
							url: "includes/handlers/ajax_load_posts.php",//where is sends the call to for processing
							type: "POST",//the send type
							data: "page=" + page +"&userLoggedIn=" + userLoggedIn,//the page will not be one anymore here,we are going to append another page
							cache: false,

						success: function(response){
							$('.posts_area').find('.nextPage').remove();//removes current next page
							$('.posts_area').find('.noMorePosts').remove();
							$('#loading').hide();
							$('.posts_area').append(response);//we are appending the data here and not replacing the data,just adding the new posts to the end
						}
					});
				}//end if statement
				return false;
			});//end $(window).scroll(function(){

		});

	</script>
			<!-- this div is from the header -->
		</div>
	</body>
</html>