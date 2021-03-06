<?php

session_start();

include "script.php";
include "config.php";

$go_back_to_search = false;
$go_back_to_mypage = false;
$go_back_to_set = false;

if(isset($_POST["pagination_left_sets"])){
	// Decrease current page
	if($_SESSION["sets_page"] > 1){
		$_SESSION["sets_page"]--;
	}
	$go_back_to_search = true;
}
else if(isset($_POST["pagination_right_sets"])){
	// Increase current page

	$max_page_number = getNumberOfPages($_SESSION['search_count']);

	if($_SESSION["sets_page"] < $max_page_number) {
		$_SESSION["sets_page"]++;
	}
	$go_back_to_search = true;
}
else if(isset($_POST["pagination_left_mypage"])){
	$go_back_to_mypage = true;
	if($_SESSION["mypage_page"] > 1){
		$_SESSION["mypage_page"]--;
	}
}
else if(isset($_POST["pagination_right_mypage"])){

	$max_page_number = getNumberOfPages($_SESSION['user_set_count']);
	
	if($_SESSION["mypage_page"] < $max_page_number){
		$_SESSION["mypage_page"]++;
	}
	$go_back_to_mypage = true;
}
else if(isset($_POST["pagination_right_bricks"])){
	$max_page_number = getNumberOfPages($_SESSION["bricks_count"]);
	if($_SESSION["bricks_page"] < $max_page_number){
		$_SESSION["bricks_page"]++;
	}
	$go_back_to_set = true;
}
else if(isset($_POST["pagination_left_bricks"])) {
	if($_SESSION["bricks_page"] > 1) {
		$_SESSION["bricks_page"]--;
	}
	$go_back_to_set = true;
}

if($go_back_to_search) {
	$last_search = $_SESSION['last_search'];
	header("location:../site/add.php? search_string=" . $last_search);
}
elseif($go_back_to_mypage) {
	header("location:../site/mypage.php");
}
elseif($go_back_to_set) {
	$SetID = $_GET["set_id"];
	header("location:../site/set.php?set_id=$SetID");
}

?>