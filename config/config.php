<?php 
ob_start();//turns on output buffering when moving your site to live server
session_start();

$timezone = date_default_timezone_set("Europe/London");


$con = mysqli_connect("localhost","root", "", "social");//connection variable
if (mysqli_connect_errno()){
	echo"failed to connect:".mysqli_connect_errno();
}


 ?>