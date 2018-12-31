<?php

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("../includes/inc_files.php"); 

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
} else {
	$user_id = "";
}

if(isset($_POST['page'])){
	// $page = $database->escape_value($_POST['page']);
	$page = clean_value($_POST['page']);
	if($page == "login"){
		if(isset($_POST['action'])){
			if($_POST['action'] == "login"){
				if(isset($_POST['username']) && isset($_POST['password'])){
					$username = $database->escape_value($_POST['username']);
					$password = $database->escape_value($_POST['password']);
					$remember_me = $database->escape_value($_POST['remember_me']);
					$current_ip = $_SERVER['REMOTE_ADDR'];
					$return = User::check_login($username, $password, $current_ip, $remember_me);
					if($return == "false"){
						echo "false";
					} else {
						echo $return;
					}
				} else {
					echo "false";
				}
			} else if($_POST['action'] == "update_msg"){
				echo output_message($message);
			}
		}
	} else if($page == "profile"){
		if(isset($_POST['profile']) && isset($_POST['message'])){
			$profile_id = clean_value($_POST['profile']);
			// $message = clean_value($_POST['message']);
			$message = $_POST['message'];
			$user = User::find_by_id($_SESSION['user_id']);
			$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
			Profile::create_profile_message($profile_id, $user->user_id, $message, $datetime);
			$type = "unread";
			echo Profile::display_profile_messages($type, $profile_id);
		} else if(isset($_POST['profile']) && isset($_POST['get']) && isset($_POST['limit'])){
			echo Profile::display_profile_messages($_POST['get'], $_POST['profile'], $_POST['limit']);
		} else if(isset($_POST['get_message'])){
			$id = clean_value($_POST['get_message']);
			echo Profile::get_profile_message_data($id);
		} else if(isset($_POST['confirm_edit']) && isset($_POST['message'])){
			$id = clean_value($_POST['confirm_edit']);
			$message = clean_value($_POST['message']);
			Profile::update_message($id,$message);
		} else if(isset($_POST['delete_message'])){
			$id = clean_value($_POST['id']);
			if(Profile::check_message_owner($id)){
				echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The message has been deleted.</div>";
			} else {
				$session->message("<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong. Please try again.</div>");
				echo "failure";
			}
		} else if(isset($_POST['update_profile'])){
			$id = clean_value($_POST['update_profile']);
			$profile_message = clean_value($_POST['profile_message']);
			$about_me = clean_value($_POST['about_me']);
			echo Profile::update_profile($id,$profile_message,$about_me);
		}
	} else if($page == "project"){
		if(isset($_POST['project']) && isset($_POST['comment'])){
			$project_id = clean_value($_POST['project']);
			// $message = clean_value($_POST['message']);
			$comment = $_POST['comment'];
			$user = User::find_by_id($_SESSION['user_id']);
			$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
			Investments::create_investment_message($project_id, $user->user_id, $comment, $datetime);
			echo Investments::display_investments_messages($project_id);
		} else if(isset($_POST['get_message'])){
			$id = clean_value($_POST['get_message']);
			echo Investments::get_message_data($id);
		} else if(isset($_POST['confirm_edit']) && isset($_POST['message'])){
			$id = clean_value($_POST['confirm_edit']);
			$message = clean_value($_POST['message']);
			Investments::update_message($id,$message);
		} else if(isset($_POST['delete_message'])){
			$id = clean_value($_POST['id']);
			if(Investments::delete_message($id)){
				echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The message has been deleted.</div>";
			} else {
				$session->message("<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong. Please try again.</div>");
				echo "failure";
			}
		} else if(isset($_POST['get_update'])){
			$id = clean_value($_POST['get_update']);
			echo Investments::get_update_data($id);
		} else if(isset($_POST['confirm_edit_update']) && isset($_POST['message'])){
			$id = clean_value($_POST['confirm_edit_update']);
			$title = clean_value($_POST['title']);
			$message = clean_value($_POST['message']);
			echo Investments::update_update($id,$title,$message);
		} else if(isset($_POST['delete_update'])){
			$id = clean_value($_POST['id']);
			if(Investments::delete_update($id)){
				echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The message has been deleted.</div>";
			} else {
				$session->message("<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong. Please try again.</div>");
				echo "failure";
			}
		} else if(isset($_POST['add_update'])){
			$id = clean_value($_POST['add_update']);
			$title = clean_value($_POST['title']);
			$message = clean_value($_POST['message']);
			Investments::add_update($id,$title,$message);
		} else if(isset($_POST['confirm_edit_update'])){
			$id = clean_value($_POST['confirm_edit_update']);
			$title = clean_value($_POST['title']);
			$message = clean_value($_POST['message']);
			Investments::edit_update($id,$title,$message);
		} else if(isset($_POST['delete_image'])){
			$id = clean_value($_POST['delete_image']);
			$image_name = clean_value($_POST['name']);
			if(Investments::delete_image($id,$image_name)){
				echo "success";
			} else {
				echo "<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>You must set another image as your thumbnail before you can delete this image.</div>";
			}
		} else if(isset($_POST['set_thumbnail'])){
			$id = clean_value($_POST['set_thumbnail']);
			$image_name = clean_value($_POST['name']);
			if(Investments::set_thumbnail($id,$image_name)){
				echo "success";
			} else {
				echo "<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong, please refresh and try again.</div>";
			}
		} else if(isset($_POST['set_theme'])){
			$id = clean_value($_POST['set_theme']);
			$image_name = clean_value($_POST['name']);
			if(Investments::set_theme($id,$image_name)){
				echo "success";
			} else {
				echo "<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong, please refresh and try again.</div>";
			}
		} else if(isset($_POST['update_project'])){
			$id = clean_value($_POST['update_project']);
			$title = clean_value($_POST['title']);
			$goal = clean_value($_POST['goal']);
			$investment_message = clean_value($_POST['investment_message']);
			$project_closed_message = clean_value($_POST['project_closed_message']);
			$description = clean_value($_POST['description']);
			$main_description = clean_value($_POST['main_description']);
			$expires = clean_value($_POST['expires']);
			$category = clean_value($_POST['category']);
			$top_theme = clean_value($_POST['top_theme']);
			$custom_theme = clean_value($_POST['custom_theme']);
			$video = $database->escape_value(trim($_POST['video']));
			echo Investments::update_project($id,$title,$goal,$investment_message,$project_closed_message,$description,$main_description,$top_theme,$custom_theme,$video,$expires,$category,"confirmed");
		} if(isset($_POST['make_payment'])){
			$id = clean_value($_POST['make_payment']);
			$amount = clean_value($_POST['amount']);
			$payment = clean_value($_POST['payment']);
			echo Investments::confirm_investment("site", $amount, $id);
		} else if(isset($_POST['check_project_images'])){
			$id = $_SESSION['new_project_id'];
			echo Investments::check_images_folder($id);
		} else if(isset($_POST['cancel_listing'])){
			$id = $_SESSION['new_project_id'];
			echo Investments::cancel_listing($id);
		} else if(isset($_POST['publish_listing'])){
			$id = $_SESSION['new_project_id'];
			$title = clean_value($_POST['title']);
			$goal = clean_value($_POST['goal']);
			$investment_message = clean_value($_POST['investment_message']);
			$project_closed_message = clean_value($_POST['project_closed_message']);
			$description = clean_value($_POST['description']);
			$main_description = clean_value($_POST['main_description']);
			echo Investments::publish_listing($id,$title,$goal,$investment_message,$project_closed_message,$description,$main_description);
		} else if(isset($_POST['payout'])){
			$id = $_POST['payout'];
			Investments::project_payout($id);
			echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>This project has been paid out.</div>";
		} else if(isset($_POST['review_project'])){
			$id = $_POST['review_project'];
			$decision = clean_value($_POST['decision']);
			$reason = clean_value($_POST['reason']);
			echo Investments::approve_decline_project($id,$decision,$reason);
		}
	} else if($page == "settings"){
		if(isset($_POST['action'])){
			$action = clean_value($_POST['action']);
			if($action == "transfer_credit"){
				if(User::get_current_credit("active",$_SESSION['user_id']) < $_POST['wanted']){
					echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but your account does not have <strong>".$_POST['wanted']."</strong> active credits to transfer.</div>";
				} else {
					User::transfer_credit("to", $_POST['wanted']);
					$session = new Session();
					if($_POST['wanted'] == 1){
						$credits_name = "Credit has";
					} else {
						$credits_name = "Credits have";
					}
					$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button><strong>".$_POST['wanted']."</strong> ".$credits_name." been successfully transferred.</div>");
					echo "transferred";
				}
			} else if($action == "withdraw_credit"){
				if(User::get_current_credit("banked",$_SESSION['user_id']) < $_POST['wanted']){
					echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but your account does not have <strong>".$_POST['wanted']."</strong> banked credits to transfer.</div>";
				} else {
					User::transfer_credit("from", $_POST['wanted']);
					$session = new Session();
					if($_POST['wanted'] == 1){
						$credits_name = "Credit has";
					} else {
						$credits_name = "Credits have";
					}
					$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button><strong>".$_POST['wanted']."</strong> ".$credits_name." been successfully transferred.</div>");
					echo "transferred";
				}
			} else if($action == "request_payout"){
				$amount = clean_value($_POST['amount']);
				$through = clean_value($_POST['through']);
				$user = User::find_by_id($_SESSION['user_id']);
				if($user->credit < $amount){
					echo "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but your account does not have <strong>".$amount."</strong> active credits to be paid out.</div>";
				} else {
					if($through == "paypal"){
						$email = clean_value($_POST['email']);
						$options = "Email: ".$email.";<br />";
						echo User::request_payout($user->user_id,$amount,$options);
					} else if($through == "wire"){
						$bank_holders_name = clean_value($_POST['bank_holders_name']);
						$iban = clean_value($_POST['iban']);
						$swift_code = clean_value($_POST['swift_code']);
						$bank_name = clean_value($_POST['bank_name']);
						$branch_country = clean_value($_POST['bank_name']);
						$transaction_country = clean_value($_POST['branch_country']);
						$options = "Bank Holders Name: ".$bank_holders_name.",<br />";
						$options .= "IBANK: ".$iban.",<br />";
						$options .= "Swift Code: ".$swift_code.",<br />";
						$options .= "Bank Name: ".$bank_name.",<br />";
						$options .= "Branch Country: ".$branch_country.",<br />";
						$options .= "Transaction Country: ".$transaction_country.",";
						echo User::request_payout($user->user_id,$amount,$options);
					}
				}
			}
		}
	} else if($page == "profile_picture"){
		 if(isset($_POST['delete_image'])){
			$image_name = clean_value($_POST['name']);
			if(Profile::delete_image($image_name)){
				echo "success";
			} else {
				echo "<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>You must set another image as your thumbnail before you can delete this image.</div>";
			}
		} else if(isset($_POST['set_thumbnail'])){
			$image_name = clean_value($_POST['name']);
			if(Profile::set_thumbnail($image_name)){
				echo "success";
			} else {
				echo "<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong, please refresh and try again.</div>";
			}
		}
	} else if($page == "payout_request"){
		if(isset($_POST['get_payout'])){
			$id = clean_value($_POST['get_payout']);
			echo User::get_payout($id);
		} else if(isset($_POST['mark_as_paid'])){
			$id = clean_value($_POST['mark_as_paid']);
			echo User::mark_as_paid($id);
		}
	} else if($page == "misc"){
		
	} else {
		echo "false";
	}
}

?>