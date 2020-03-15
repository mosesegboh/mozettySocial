<?php 
	class Post{
		private $user_obj; //this means its only available to this class
		private $con;

		public function __construct($con, $user){ //this constructor is what is called when the user creates an object of the user class
			$this->con=$con; //this references the variables or properties of this particular class
			$this->user_obj = new User($con, $user);//we are making an instance of the users class within
		}
		public function submitPost($body, $user_to){
			$body = strip_tags($body);//removed html tags
			$body = mysqli_real_escape_string($this->con, $body);//escapes string to remove single quotes cause of my sql
			//to enable user use line breaks in posts
			$body = str_replace('\r\n', '\n', $body);
			$body = nl2br($body);
			$check_empty=preg_replace('/\s+/', '', $body);//deletes all spaces s p tthat empty boxes dont go into the database

			if($check_empty != ""){ //if its still empty after removing white spaces
				//current date and time
				$date_added = date("Y-m-d H:i:s");

				//get username
				$added_by = $this->user_obj->getUsername();
				//if user is not on own prrofile,user_to is none

				if($user_to == $added_by){
					$user_to = "none";
				}
				//insert post into database
				$query = mysqli_query($this->con, "INSERT INTO posts VALUES(NULL, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
				//database insert errors could be caused by typos and empty string being pus as id instead of null
				
				$returned_id = mysqli_insert_id($this->con);//returns the id of the post that was just submitted
				


				//insert notification

				//update post count for user
				$num_posts = $this->user_obj->getNumPosts();
				$num_posts++;
				$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
			}

		}

		public function loadPostsFriends($data, $limit){//these parameters was just added for the ajax call,at first it can run pretty fine without it

			$page = $data['page']; //this variable is to he used in the ajax call
			$page = intval($page);
			$userLoggedIn = $this->user_obj->getUsername();
			if ($page == 1)//this means post has been loaded the first time start at 0 posts
				$start = 0;
			else
				$start = ($page - 1) * $limit;//this will make it start at the 10th or limit post to load the posts again

			$str = ""; //we set it blank for now to be populated later
			$data_query = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted='no' ORDER by id DESC");

			if(mysqli_num_rows($data_query) > 0){ //c if
				$num_iterations = 0;//number of results checcked not necessarily loaded
				$count = 1;

			while ($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];

				//prepare user_to string even if its not posted to a user
				if($row['user_to'] == "none"){
					$user_to =  "";
				}else{

					$user_to_obj = new User ($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to =  " to <a href='" .$row['user_to'] . "'>" . $user_to_name . "</a>"	;			
				}
				//check if user who posted has their account closed
				$added_by_obj = new User ($this->con, $added_by);
				if ($added_by_obj -> isClosed()) {
					continue;//this takes us to the beginning of the loop again
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if ($user_logged_obj->isFriend($added_by)) { //if of to check if is friend
					//what this will do is that it will check if their friends,it will continue and if will not
				


				if ($num_iterations++ < $start)
					continue;//make another iteration to load posts that has not being loaded,when it gets to the number of $starts,it can ten carry on the rest of the loop
				//once 10  posts has been loaded,break
				if ($count > $limit) {
					break; //break the loop before  it loads the post
				}else{
					$count++;//else continue
				}

				if ($userLoggedIn == $added_by) 
					$delete_button= "<button class='delete_button btn-danger' id='post$id'>x</button>";
				else
					$delete_button ="";

				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name,profile_pic FROM users WHERE username= '$added_by'" );
				$user_row = mysqli_fetch_array($user_details_query);
				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];
				?>

				<script>
					function toggle<?php echo $id; ?> () { //this shows which comments to show
						var target = $(event.target);
						if(!target.is("a")){

						var element = document.getElementById("toggleComment<?php echo $id; ?>")//the id was declared in the beginnnig of the while loop;//comment section is the block
						if (element.style.display == "block")//if its showing
						element.style.display = "none";//hide it
						else
							element.style.display = "block"//if its hidden show it
							}
						}	
				</script>

				<?php

				//get number of comments
				$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id ='$id'");
				$comments_check_num = mysqli_num_rows($comments_check);

				//Time frame
				$date_time_now = date("Y-m-d H:i:s");
				$start_date = new DateTime($date_time);//time of post //this class comes inbuilt in php
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
					//each the while loop runs it srops this string
					//the javasript toggle below will show what happens when the div is pressed...then the iframe shows the comment form,
					$str .= "<div class='status_post' onClick='javascript:toggle$id()'> 
								<div class = 'post_profile_pic'>
								    <img src= '$profile_pic' width ='50'>
								</div>
								<div class = 'posted_by' style = 'color: #ACACAC;'>
									<a href ='$added_by'>$first_name $last_name</a>$user_to  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$time_message
									$delete_button
								</div>
								<div id = 'post_body'>
									$body
									<br>
									<br>
									<br>
								</div>
								<div class = 'newfeedPostOptions'>
									Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
									<iframe src='like.php?post_id=$id' scrolling='no'></iframe>
								</div>
							</div>
							<div class ='post_comment' id ='toggleComment$id' style='display:none;'>
							<iframe src = 'comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>
							<hr>";
						}//end of if of to check if user friend
						//the div class newfeedPostsOptions is for displaying the comments
			
						//javascript code for deleteing post
						?>

						<script>
							$(document).ready(function(){
								$('#post<?php echo $id; ?>').on('click',function(){
										//bootbox comes with bootstrap
										bootbox.confirm("Are you sure you want to delete this post?", function(result){
												//in the below script we are sending variable result and we are calling variable result aswell
											$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});
											if (result)
												location.reload();
										});

								});

							})

						</script>

						<?php

			}//while loop end
			if($count > $limit)//if the post count about to the loaded is less than the limit
			$str .=  "<input type='hidden' class='nextPage' value='" . ($page + 1) . " '><input type ='hidden' class='noMorePosts' value='false'>";//we store thr remaining in this variable..we will increase the page by one here waiting for the next time it loads
			else
			$str .= "<input type ='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'>Mo more posts here!</p>";

			}//end of c if
			echo $str; // then we have to echo it here my
		}//end of load post friends function


		public function loadProfilePosts($data, $limit){//these parameters was just added for the ajax call,at first it can run pretty fine without it

			$page = $data['page']; //this variable is to he used in the ajax call
			$page = intval($page);
			$profileUser = $data['profileUsername'];
			$userLoggedIn = $this->user_obj->getUsername();
			if ($page == 1)//this means post has been loaded the first time start at 0 posts
				$start = 0;
			else
				$start = ($page - 1) * $limit;//this will make it start at the 10th or limit post to load the posts again

			$str = ""; //we set it blank for now to be populated later
			$data_query = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted='no'AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser')  ORDER by id DESC");

			if(mysqli_num_rows($data_query) > 0){ //c if
				$num_iterations = 0;//number of results checcked not necessarily loaded
				$count = 1;

			while ($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				//we removed the function for if user is posted to
				//because its not necessary here because its on the 
				//user profile page

				//check if user who posted has their account closed
				//we also removed if the account is closed

				//we removed if users is friends because you cant be friends with yourself


				if ($num_iterations++ < $start)
					continue;//make another iteration to load posts that has not being loaded,when it gets to the number of $starts,it can ten carry on the rest of the loop
				//once 10  posts has been loaded,break
				if ($count > $limit) {
					break; //break the loop before  it loads the post
				}else{
					$count++;//else continue
				}

				if ($userLoggedIn == $added_by) 
					$delete_button= "<button class='delete_button btn-danger' id='post$id'>x</button>";
				else
					$delete_button ="";

				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name,profile_pic FROM users WHERE username= '$added_by'" );
				$user_row = mysqli_fetch_array($user_details_query);
				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];
				?>

				<script>
					function toggle<?php echo $id; ?> () { //this shows which comments to show
						var target = $(event.target);
						if(!target.is("a")){

						var element = document.getElementById("toggleComment<?php echo $id; ?>")//the id was declared in the beginnnig of the while loop;//comment section is the block
						if (element.style.display == "block")//if its showing
						element.style.display = "none";//hide it
						else
							element.style.display = "block"//if its hidden show it
							}
						}	
				</script>

				<?php

				//get number of comments
				$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id ='$id'");
				$comments_check_num = mysqli_num_rows($comments_check);

				//Time frame
				$date_time_now = date("Y-m-d H:i:s");
				$start_date = new DateTime($date_time);//time of post //this class comes inbuilt in php
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
					//each the while loop runs it srops this string
					//the javasript toggle below will show what happens when the div is pressed...then the iframe shows the comment form,
					$str .= "<div class='status_post' onClick='javascript:toggle$id()'> 
								<div class = 'post_profile_pic'>
								    <img src= '$profile_pic' width ='50'>
								</div>
								<div class = 'posted_by' style = 'color: #ACACAC;'>
									<a href ='$added_by'>$first_name $last_name</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$time_message
									$delete_button
								</div>
								<div id = 'post_body'>
									$body
									<br>
									<br>
									<br>
								</div>
								<div class = 'newfeedPostOptions'>
									Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
									<iframe src='like.php?post_id=$id' scrolling='no'></iframe>
								</div>
							</div>
							<div class ='post_comment' id ='toggleComment$id' style='display:none;'>
							<iframe src = 'comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
							</div>
							<hr>";
						
						//the div class newfeedPostsOptions is for displaying the comments
			
						//javascript code for deleteing post
						?>

						<script>
							$(document).ready(function(){
								$('#post<?php echo $id; ?>').on('click',function(){
										//bootbox comes with bootstrap
										bootbox.confirm("Are you sure you want to delete this post?", function(result){
												//in the below script we are sending variable result and we are calling variable result aswell
											$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});
											if (result)
												location.reload();
										});

								});

							})

						</script>

						<?php

			}//while loop end
			if($count > $limit)//if the post count about to the loaded is less than the limit
			$str .=  "<input type='hidden' class='nextPage' value='" . ($page + 1) . " '><input type ='hidden' class='noMorePosts' value='false'>";//we store thr remaining in this variable..we will increase the page by one here waiting for the next time it loads
			else
			$str .= "<input type ='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'>Mo more posts here!</p>";

			}//end of c if
			echo $str; // then we have to echo it here my
		}//end of load post friends function

	}//end of the post class
 ?>