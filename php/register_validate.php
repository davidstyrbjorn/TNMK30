<?php

require_once("config.php");

// Get credentials from POST using a filter avoiding sql-injections
$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
$unhashed_password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
$password_repeat = filter_input(INPUT_POST, "password-repeat", FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);

// Error handling message
$authentication_msg = "authentication=";
$authentication_error = false;

// Make sure username doesnt overflow VARCHAR size in table
if(strlen($username) > $username_max_length){
	header("location:../site/register.php?authentication=_username_");
	exit();
}

// Simple username & passowrd authentication!
if(strlen($unhashed_password) < $password_min_length){
	$authentication_msg .= '_password_';
	$authentication_error = true;
}
if($unhashed_password!= $password_repeat){
	$authentication_msg .= '_passr_';
	$authentication_error = true;
}
if(strlen($username) < $username_min_length){
	$authentication_msg .= '_username_';
	$authentication_error = true;
}
// There was authentication error
if($authentication_error == true){
	// Redirect back with appropiate error message
	header("location: ../site/register.php?$authentication_msg");
	exit();
}

// We register the user if he/she doesn't already exist with username or email

// Hash the password
$hashed_pasword = hash($hash, $unhashed_password);

// Insert into database!

// Get database login info from config 
$host = $config["db"]["special_edit"]["host"];
$dbname = $config["db"]["special_edit"]["dbname"]; 
$db_username = $config["db"]["special_edit"]["username"]; 
$password =	$config["db"]["special_edit"]["password"];
 
// Connect to database
$db = mysqli_connect($host, $db_username, $password, $dbname) or die("Failed to connect to database");

// Query to check if email or username already exists
$email_result = mysqli_query($db, "SELECT * FROM users WHERE users.email = '$email'");
$username_result = mysqli_query($db, "SELECT * FROM users WHERE users.username = '$username'");
$email_exists = (mysqli_num_rows($email_result)) ? TRUE:FALSE;
$username_exists = (mysqli_num_rows($username_result)) ? TRUE:FALSE;

// Doesn't exist? insert user into table
if(!$email_exists && !$username_exists){
	$query = "INSERT INTO users(username, email, pword) VALUES ('$username', '$email', '$hashed_pasword')";
	$result = mysqli_query($db, $query);
	
	// Login the user_error
	$query = "SELECT users.user_id FROM users WHERE users.email = '$email'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);
	$userid = $row["user_id"]; 
	session_start(); // Start session 
	$_SESSION["logged_in"] = true;
	$_SESSION["user_id"] = $userid;
	
	header("location:../site/mypage.php");
}
else{
	header("location:../site/register.php?authentication=_exists_");
}

mysqli_close($db);
?>
