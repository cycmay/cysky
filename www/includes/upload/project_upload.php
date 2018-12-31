<?php

$id = $_GET['id'];

if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(move_uploaded_file($_FILES['file']['tmp_name'], "../../assets/project/".$id."/images/".$_FILES['file']['name'])){
		echo($_POST['index']);
	}
	exit;
}
?>