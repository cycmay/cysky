<?php
require_once("includes/inc_files.php");

$request = clean_value($_GET['request']);

if($request == "user_details"){
	$username = clean_value($_GET['username']);
	if($username == ""){
		echo json_encode("No user entered");
	} else {
		echo Api::get_user_data($username);
	}
} else if($request == "user_projects"){
	$username = clean_value($_GET['username']);
	if($username == ""){
		echo json_encode("No user entered");
	} else {
		echo Api::get_user_projects($username);
	}
} else if($request == "project_details"){
	$id = clean_value($_GET['id']);
	if($id == ""){
		echo json_encode("No id entered");
	} else {
		echo Api::get_project($id);
	}
}

?>