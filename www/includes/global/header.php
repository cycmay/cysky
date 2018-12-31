<?php 

if($session->is_logged_in()) { 
	$user = User::find_by_id($_SESSION['user_id']);
	if($user->suspended == "1") { 
		redirect_to('logout.php?msg=suspended'); 
	} else if(MAINTENANCE_MODE == "ON" && $user->staff != 1){ 
		redirect_to('logout.php?msg=maintenance'); 
	}
 	
} else {
	$user = new User;
	$user->staff = "";
}

?>