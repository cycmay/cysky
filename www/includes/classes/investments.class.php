<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

class Investments {
	
	protected static $table_name="investments";
	protected static $db_fields = array('id','creator_id','created','name','description','investment_wanted','amount_invested','invested','expires','main_picture','status','investment_id','question','answer','datetime','user_id','date_pledged','message','sent','type','amount','number_left','thumbnail','main_description','category_id','investment_message','investor_count','featured','project_closed_message','investment_id','question','answer','datetime','user_id','amount','date_invested','message','sent','type','title','theme','top_theme','custom_theme','ctheme','video');
	
	// Table: investments
	
	public $id;
	public $creator_id;
	public $created;
	public $name;
	public $description;
	public $investment_wanted;
	public $amount_invested;
	public $invested;
	public $expires;
	public $main_picture;
	public $status;
	public $thumbnail;
	public $main_description;
	public $category_id;
	public $investment_message;
	public $investor_count;
	public $featured;
	public $project_closed_message;
	public $top_theme;
	public $theme;
	public $custom_theme;
	public $ctheme;
	public $video;
	
	// Table: investments_faq
	
	// public $id;
	public $investment_id;
	public $question;
	public $answer;
	public $datetime;
	
	// Table: investments_made
	
	// public $id;
	public $user_id;
	// public $investment_id;
	public $amount;
	// public $status;
	public $date_invested;
	// public $expires;
	
	// Table: investments_messages
	
	// public $id;
	// public $user_id;
	// public $investment_id;
	// public $status;
	public $message;
	public $sent;
	public $type;
	
	// Table: investments_updates
	
	// public $id;
	// public $investment_id;
	// public $datetime;
	public $title;
	// public $update;
	// public $status;
	
	
	// Table: investments_packages
	
	// public $id;
	// public $name;
	// public $description;
	// public $amount;
	// public $number_left;
	
	// Table: categories
	
	// public $id;
	// public $name;
	// public $status;
	
	public static function create_project($title,$category,$goal,$expires,$investment_message,$complete_message,$description,$main_description){
		global $database;
		$datetime = date("Y-m-d H:i:s", time());
		$user_id = $_SESSION['user_id'];
		$expires_datetime = date("Y-m-d H:i:s", strtotime($expires.' days'));
		// if(NEW_PROJECT_VERIFY == "YES"){
		// 			$project_status = "0";
		// 		} else {
		// 			$project_status = "1";
		// 		}
		
		$default_theme = DEFAULT_THEME;
		$database->query("INSERT INTO investments VALUES ('','{$user_id}','{$datetime}','{$title}','{$description}','{$goal}','0','0','{$expires_datetime}','','0','','{$main_description}','{$category}','{$investment_message}','0','0','{$complete_message}','0','{$default_theme}','0','','') ");
		
		$data = self::find_by_sql("SELECT id FROM investments WHERE created = '{$datetime}' AND expires = '{$expires_datetime}' AND creator_id = '{$user_id}' LIMIT 1 ");
		@mkdir("./assets/project/".$data[0]->id);
		@chmod("./assets/project/".$data[0]->id,  0777);
		@mkdir("./assets/project/".$data[0]->id."/images");
		@chmod("./assets/project/".$data[0]->id."/images", 0777);
		$_SESSION['new_project_id'] = $data[0]->id;
				
		$_SESSION['current_step'] = 2;
		redirect_to(WWW."create-project.php?step=2");
		
	}
	
	public static function approve_decline_project($id,$ad,$staff_message){
		global $database;
		$session = new Session();
		$email_class = new Email();
		$user = User::find_by_id($_SESSION['user_id']);
		if($ad == "approve"){
			$database->query("UPDATE investments SET status = '1' WHERE id = '{$id}' ");
			$subject = "Project Approved";
			$message = "We are very pleased to inform you that your project has been approved by our staff. You can view it <a href='".WWW."project.php?id=".$id."'>here</a><br />";
			$message .= "Staff Message: ".$staff_message;
		} else if($ad == "decline") {
			$database->query("DELETE FROM investments WHERE id = '{$id}' ");
			rrmdir("assets/project/".$id);
			$subject = "Project Rejected";
			$message = "We are very sorry to inform you that your project has been rejected by our staff for the following reason:";
			$message .= "Reason: ".$staff_message;
		}
		$from = SITE_EMAIL;
		$message = $email_class->email_template('custom_email', "", "", "", "", $message);
		$email_class->send_email($user->email, $from, $subject, $message);
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The project has been updated.</div>");
		return "success";
	}
	
	public static function check_images_folder($id){
		$directory = dir("assets/project/".$id."/images/");
		while ((FALSE !== ($item = $directory->read())) && ( ! isset($directory_not_empty))){
			if ($item != '.' && $item != '..' && $item != '.DS_Store'){
				$empty = false;
			} else {
				$empty = true;
			}
		}
		$directory->close();
		
		if($empty == true){
			return "false";
		} else {
			$_SESSION['current_step'] = 3;
			return WWW."create-project.php?step=3";
		}		
	}
	
	public static function update_project($id,$title,$goal,$investment_message,$project_closed_message,$description,$main_description,$top_theme,$custom_theme,$video,$expires="",$category="",$admin=""){
		global $database;
		$project_data = self::find_by_sql("SELECT creator_id FROM investments WHERE id = '{$id}' LIMIT 1 ");
		// return preprint($project_data);
		$user_id = $_SESSION['user_id'];
		if($admin != ""){
			$extra = ", expires = '{$expires}', category_id = '{$category}' ";
		} else {
			$extra = "";
		}
		if($project_data[0]->creator_id == $user_id || $admin == "confirmed"){
			$database->query("UPDATE investments SET name = '{$title}', investment_wanted = '{$goal}', investment_message = '{$investment_message}', project_closed_message = '{$project_closed_message}', description = '{$description}', main_description = '{$main_description}', top_theme = '{$top_theme}', custom_theme = '{$custom_theme}', video = '{$video}' $extra WHERE id = '{$id}' ");
			return "success";
		} else {
			return "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you don't have the correct permissions to edit this project.</div>";
		}
	}
	
	public static function send_email($id,$name,$email,$subject,$message){
		global $database;
		$session = new Session;
		$email_class = new Email;
		
		$project_data = self::find_by_sql("SELECT creator_id,name FROM investments WHERE id = '{$id}' LIMIT 1 ");
		$project_owner = $project_data[0]->creator_id;
		$owner_data = User::find_by_sql("SELECT email,first_name,last_name FROM users WHERE user_id = '{$project_owner}' LIMIT 1 ");
		
		$subject = "Message Though Investment: ".$project_data[0]->name;
		$message = nl2br($message);
		$message = $email_class->email_template('custom_email', "", "", "", "", $message);
		$email_class->send_email($owner_data[0]->email, $email, $subject, $message);
		
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>We've sent your message to {$owner_data[0]->first_name} {$owner_data[0]->last_name}.</div>");
	}
	
	public static function project_payout($id){
		global $database;
		$datetime = date('Y-m-d H:i:s');
		$project_data = self::find_by_sql("SELECT * FROM investments WHERE id = '{$id}' LIMIT 1 ");
		$project_name = $project_data[0]->name;
		$email_class = new Email();
		$from = SITE_EMAIL;
		$user = User::find_by_id($project_data[0]->creator_id);
		$cut = 0;
		$flag = false;
		if(REQUIRE_GOAL == "YES"){
			if($project_data[0]->amount_invested >= $project_data[0]->investment_wanted){
				// if(TAKE_CUT == "YES"){
				// 	$cut = $cut + $project_data[0]->amount_invested * (CUT_PERCENTAGE / 100);
				// 	$credit = $project_data[0]->amount_invested - $cut;
				// } else {
				// 	$credit = $project_data[0]->amount_invested;
				// }
				// $new_credits = $user->credit + $credit;
				// $database->query("UPDATE investments SET invested = '1' WHERE id = '{$id}' ");
				// $database->query("UPDATE users SET credit = '{$new_credits}' WHERE user_id = '{$user->user_id}' ");
				// $database->query("INSERT INTO credit_history VALUES('','$user->user_id','$credit','Payout for project: {$project_name}','$datetime','c')");
				// if($cut != 0){
				// 	$new_site_credit = SITE_CREDIT + $cut;
				// 	$database->query("UPDATE core_settings SET data = '{$new_site_credit}' WHERE name = 'SITE_CREDIT' ");
				// }
				// $subject = $project_name." Payout";
				// $message = "Your project (".$project_name.") has expired with ".CURRENCYSYMBOL.$credit." invested. Your account has been credited with the invested amount.";
				$flag = true;
			} else {
				$investments = self::find_by_sql("SELECT * FROM investments_made WHERE investment_id = '{$id}' ");
				foreach($investments as $investment){
					$investor = User::find_by_id($investment->user_id);
					$new_credits = $investor->credit + $investment->amount;
					$database->query("UPDATE users SET credit = '{$new_credits}' WHERE user_id = '{$investor->user_id}' ");
					$database->query("INSERT INTO credit_history VALUES('','$investor->user_id','$investment->amount','Refund of Investment: {$project_name}','$datetime','c')");
					$subject = $project_name." Refund";
					$message = "The project (".$project_name.") has expired without reaching its goal of ".CURRENCYSYMBOL.$project_data[0]->investment_wanted.". We have refunded any investments which you have made to this project.";
					$message = $email_class->email_template('custom_email', "", "", "", "", $message);
					$email_class->send_email($investor->email, $from, $subject, $message);
				}
				$database->query("UPDATE investments SET invested = '0', amount_invested = '0' WHERE id = '{$id}' ");
				$subject = $project_name." Not Funded";
				$message = "Your project (".$project_name.") has expired without reaching its goal of ".CURRENCYSYMBOL.$project_data[0]->investment_wanted.". We have refunded all investments made.";
				$message = $email_class->email_template('custom_email', "", "", "", "", $message);
				$email_class->send_email($user->email, $from, $subject, $message);
			}
		} else {
			$flag = true;
		}
		if($flag == true){
			if(TAKE_CUT == "YES"){
				$cut = $cut + $project_data[0]->amount_invested * (CUT_PERCENTAGE / 100);
				$credit = $project_data[0]->amount_invested - $cut;
			} else {
				$credit = $project_data[0]->amount_invested;
			}
			$new_credits = $user->credit + $credit;
			$project_name = $project_data[0]->name;
			$database->query("UPDATE investments SET invested = '1' WHERE id = '{$id}' ");
			$database->query("UPDATE users SET credit = '{$new_credits}' WHERE user_id = '{$user->user_id}' ");
			$database->query("INSERT INTO credit_history VALUES('','$user->user_id','$credit','Payout for project: {$project_name}','$datetime','c')");
			if($cut !== 0){
				$new_site_credit = SITE_CREDIT + $cut;
				$database->query("UPDATE core_settings SET data = '{$new_site_credit}' WHERE name = 'SITE_CREDIT' ");
			}
			$subject = $project_name." Payout";
			$message = "Your project (".$project_name.") has expired with ".CURRENCYSYMBOL.$credit." invested. Your account has been credited with the invested amount.";
			$message = $email_class->email_template('custom_email', "", "", "", "", $message);
			$email_class->send_email($user->email, $from, $subject, $message);
		}
	}
	
	public static function cancel_listing($id){
		global $database;
		$session = new Session();
		$listing_data = self::find_by_sql("SELECT * FROM investments WHERE id = '{$id}' LIMIT 1 ");
		$user_id = $_SESSION['user_id'];
		if($user_id == $listing_data[0]->creator_id){
			$database->query("DELETE FROM investments WHERE id = '{$id}' ");
			rrmdir("assets/project/".$id);
			unset($_SESSION['current_step']);
			$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>已经从列表中取消了。</div>");
			return WWW."index.php";
		} else {
			$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>抱歉出错，请再试一次。</div>");
			return WWW."create-project.php?step?=3";
		}		
	}
	
	public static function publish_listing($id,$title,$goal,$investment_message,$project_closed_message,$description,$main_description){
		global $database;
		$session = new Session();
		$project_data = self::find_by_sql("SELECT id,creator_id FROM investments WHERE id = '{$id}' LIMIT 1 ");
		$user = User::find_by_id($_SESSION['user_id']);
		$user_id = $user->user_id;
		if($project_data[0]->creator_id == $user_id){
			if(NEW_PROJECT_VERIFY == "NO"){
				$project_status = '1';
			} else {
				$project_status = '0';
			}
			$database->query("UPDATE investments SET name = '{$title}', investment_wanted = '{$goal}', investment_message = '{$investment_message}', project_closed_message = '{$project_closed_message}', description = '{$description}', main_description = '{$main_description}', status = '{$project_status}' WHERE id = '{$id}' ");
			if(NEW_PROJECT_VERIFY == "NO"){
				$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>您的项目已经创建并且公开。</div>");
				$subject = "您的项目已经成功创建";
				$message = "你的项目已经被创建并可以通过后面的链接查看 <a href='".WWW."project.php?id=".$id."'>点击这里</a>.";
			} else {
				$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>您的项目已创建但正在等待员工的批准。我们将尽快通知您结果。</div>");
				$subject = "项目等待批准";
				$message = "您的项目正在等待我们的员工批准。我们将让你知道结果一旦他们做出了决定。";
			}

			$email_class = new Email();
			$from = SITE_EMAIL;
			$message = $email_class->email_template('custom_email', "", "", "", "", $message);
			$email_class->send_email($user->email, $from, $subject, $message);
			
			unset($_SESSION['current_step']);
			return WWW."project.php?id=".$id;
		} else {
			return "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>对不起，您没有正确的权限来编辑这个项目。</div>";
		}
	}
	
	public static function get_categories($admin=""){
		if($admin == "confirmed"){
			return self::find_by_sql("SELECT * FROM categories ");
		} else {
			return self::find_by_sql("SELECT * FROM categories WHERE status = '1' ");
		}
	}
	
	public static function get_category_data($id){
		return self::find_by_sql("SELECT * FROM categories WHERE id = '{$id}' LIMIT 1 ");
	}
	
	public static function get_all_awaiting_approval(){
		return self::find_by_sql("SELECT * FROM investments WHERE status = '0' ");
	}
	
	public static function get_expired_projects(){
		$current_time = date('Y-m-d H:i:s');
		return self::find_by_sql("SELECT * FROM investments WHERE expires < '{$current_time}' AND invested = '0' ");
	}
	
	public static function create_category($name,$status){
		global $database;
		$session = new Session();
		$database->query("INSERT INTO categories VALUES ('','{$name}','{$status}') ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>已经成功创建分类。</div>");
		redirect_to("categories.php");
	}
	
	public static function update_cateory($id,$name,$status){
		global $database;
		$session = new Session();
		$database->query("UPDATE categories SET name = '{$name}', status = '{$status}' WHERE id = '{$id}' ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>已经成功更新分类。</div>");
		redirect_to("categories.php");
	}
	
	public static function delete_category($id){
		global $database;
		$session = new Session();
		$database->query("DELETE FROM categories WHERE id = '{$id}' ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>已经成功删除分类。</div>");
		redirect_to("categories.php");
	}
	
	public static function get_project_listing($investment_id){
		$data = self::find_by_sql("SELECT * FROM investments WHERE id = '{$investment_id}' LIMIT 1 ");
		return $data[0];
	}
	
	public static function convert_expiry_time($time){
		
		$start_date=$time;
		$end_date=date('Y-m-d H:i:s'); 

		$diff=strtotime($start_date)-strtotime($end_date); 

		// immediately convert to days 
		$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day
		
		$days = floor($temp); $temp=24*($temp-$days); 
		$hours = floor($temp); $temp=60*($temp-$hours); 
		$minutes = floor($temp); $temp=60*($temp-$minutes); 
		$seconds = floor($temp);

		if($end_date < $start_date){
			if($days == 0){
				return $hours."小时 ".$minutes."m ".$seconds."s";
			} else if($days == 0 && $hours == 0){
				return $minutes."分钟 ".$seconds."s";
			} else if($days == 0 && $hours == 0 && $minutes == 0){
				return $seconds."秒";
			} else {
				return $days."天 ".$hours."小时 ".$minutes."分钟 ".$seconds."秒";
			}
		} else {
			return "Listing Complete";
		}
	}

	public static function get_investments_messages($type, $investment_id){
		if($type == "unread"){
			return self::find_by_sql("SELECT * FROM investments_messages WHERE type = '0' AND investment_id = '{$investment_id}' ORDER BY sent DESC ");
		} else if($type == "all"){
			return self::find_by_sql("SELECT * FROM investments_messages WHERE investment_id = '{$investment_id}' ORDER BY sent DESC ");
		}
	}
	
	public static function display_investments_messages($investment_id, $limit = null, $is_admin = FALSE){
		if($limit != null){
			$limit = "LIMIT ".$limit;
		}
		
		$investments_messages = self::find_by_sql("SELECT * FROM investments_messages WHERE investment_id = '{$investment_id}' ORDER BY sent DESC $limit ");
		$investment = self::find_by_sql("SELECT * FROM investments WHERE id = '{$investment_id}' LIMIT 1 ");
		// return preprint($investments_messages);

		if(isset($_SESSION['user_id'])){
			$user = User::find_by_id($_SESSION['user_id']);
		} else {
			$user = "";
			$user->user_id = "";
		}
			
		$messages = "";
		foreach($investments_messages as $message): $message_owner = User::find_profile_message_owner($message->user_id);
		
		if($investment[0]->creator_id == $user->user_id || $is_admin){
			$edit = '<br /><span class="right"><a href="JavaScript:void(0);" onclick="edit_message('.$message->id.');">编辑</a> - <a href="JavaScript:void(0);" onclick="delete_message('.$message->id.');">删除</a></span>';
		} else {
			if($message_owner[0]->user_id == $user->user_id){
				$edit = '<br /><span class="right"><a href="JavaScript:void(0);" onclick="edit_message('.$message->id.');">编辑</a></span>';
			} else {
				$edit = "";
			}
		}
		
		if($message_owner[0]->profile_picture == "male.jpg" || $message_owner[0]->profile_picture == "female.jpg"){
			$profile_image = '<img src="'.WWW.'assets/img/profile/'.$message_owner[0]->profile_picture.'" alt="Profile Picture">';
		} else { 
			$profile_image = '<img src="'.WWW.'assets/img/profile/'.$message_owner[0]->user_id."/".$message_owner[0]->profile_picture.'" alt="头像">';
		}
		
		$messages .= '<div class="profile_message_container" id="message'.$message->id.'">
			<div class="span2 center">
				'.$profile_image.'
			</div>
			<div class="span10">
				<a href="'.WWW.'profile.php?username='.$message_owner[0]->username.'" style="font-size: 16px;">'.$message_owner[0]->first_name.' '.$message_owner[0]->last_name.'</a>
				<span class="right">'.datetime_to_text($message->sent).'</span>
				<div class="clearfix" style="height: 6px;"></div>
				'.nl2br($message->message).$edit.'				
			</div>
			<div class="clearfix"></div>
		</div>';
		endforeach;
		return $messages;
	}
	
	public static function create_investment_message($project_id, $user_id, $message, $datetime){
		global $database;
		$database->query("INSERT INTO investments_messages (id,user_id,investment_id,status,message,sent,type) VALUES ('','{$user_id}','{$project_id}','0','{$message}','{$datetime}','0') ");
	}
	
	public static function get_investors($id){
		// status key: 0=public,1=private,2=returned.
		return self::find_by_sql("SELECT * FROM investments_made WHERE investment_id = '{$id}' ");
	}

	public static function get_user_investments($id){
		return self::find_by_sql("SELECT * FROM investments_made WHERE user_id = '{$id}' AND status != '1' ");
	}
	
	public static function get_user_projects($id){
		return self::find_by_sql("SELECT * FROM investments WHERE creator_id = '{$id}' ");
	}
	
	public static function get_project_details($id){
		return self::find_by_sql("SELECT id,name FROM investments WHERE id = '{$id}' LIMIT 1 ");
	}
	
	public static function get_top_projects($max = 50){
		return self::find_by_sql("SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' ORDER BY investor_count DESC LIMIT $max ");
	}
	
	public static function count($type,$investment_id){
		global $database;	
		$sql = "SELECT COUNT(*) FROM investments_{$type} WHERE investment_id = '{$investment_id}' ";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function count_user_investments($user_id){
		global $database;	
		$sql = "SELECT COUNT(*) FROM investments_made WHERE user_id = '{$user_id}' ";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function count_started_projects($user_id){
		global $database;	
		$sql = "SELECT COUNT(*) FROM investments WHERE creator_id = '{$user_id}' ";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function delete_image($id,$image_name){
		global $database;
		$data = self::find_by_sql("SELECT thumbnail FROM investments WHERE id = '{$id}'");
		if($image_name == $data[0]->thumbnail){
			return false;
		} else {
			unlink("./assets/project/".$id."/images/".$image_name);
			return true;
		}
	}
	
	public static function confirm_investment($through, $amount, $id, $type, $user_id=""){
		global $database;
		$investment = self::find_by_sql("SELECT name,amount_invested FROM investments WHERE id = '{$id}' LIMIT 1 ");
		// $user = User::find_by_id($_SESSION['user_id']);
		$confirmed = false;
		if(empty($user_id)){
			$user = User::find_by_id($_SESSION['user_id']);
		} else {
			$user = User::find_by_id($user_id);
		}		
		
		if($through == "paypal"){
			$confirmed = true;
		}
		elseif($through == "alipay"){
			$confirmed = true;
		}
		else if($through == "site"){
			if($user->credit >= $amount){
				$confirmed = true;
				// echo "yes";
			} else {
				return "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but your account does not contain <strong>".$amount."</strong> active credits to invest.</div>";
			}
		}
		
		if($confirmed == true){
			$new_invested_amount = $investment[0]->amount_invested + $amount;
			$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
			
			$database->query("UPDATE investments SET amount_invested = '{$new_invested_amount}' WHERE id = '{$id}' ");
			$database->query("INSERT INTO investments_made (user_id,investment_id,amount,status,date_invested) VALUES ('{$user->user_id}','{$id}','{$amount}','{$type}','{$datetime}') ");
			
			if($through == "paypal" || $through == "alipay"){
				$description = "Credit added for investment: ".$investment[0]->name;
				$database->query("INSERT INTO credit_history (user_id,credits,description,datetime,status) VALUES ('{$user->user_id}','{$amount}','{$description}','{$datetime}','c') ");
				$new_user_amount + $amount;
			}
			
			$description = "Investment made to: ".$investment[0]->name;
			$database->query("INSERT INTO credit_history (user_id,credits,description,datetime,status) VALUES ('{$user->user_id}','{$amount}','{$description}','{$datetime}','d') ");
			$new_user_amount = $user->credit - $amount;
			$new_investments_made = $user->investments_made + 1;
			$new_amount_invested = $user->amount_invested + $amount;
			
			$database->query("UPDATE users SET credit = '{$new_user_amount}', investments_made = '{$new_investments_made}', amount_invested = '{$new_amount_invested}' WHERE user_id = '{$user->user_id}' ");
			
			$session = new Session();
			$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Thank you, <strong>".$amount."</strong> credits have been invested into this project.</div>");
			
			$email_class = new Email();
			$from = SITE_EMAIL;
			$subject = "Thank you for your Investment";
			$message = "Thank you for your investment in ".$investment[0]->name." for ".CURRENCYSYMBOL.$amount.".";
			$message = $email_class->email_template('custom_email', "", "", "", "", $message);
			$email_class->send_email($user->email, $from, $subject, $message);
			
			return "success";
		}
	}
	
	public static function get_investment_name($id){
		$data = self::find_by_sql("SELECT name FROM investments WHERE id = '{$id}' LIMIT 1 ");
		return $data[0]->name;
	}
	
	
	public static function get_investment_payment_id(){
		$data = self::find_by_sql("SELECT max(id) as max FROM investments_made");
		return $data[0]->max ? $data[0]->max+1 : 1000;
	}
	
	// Get featured projects - for the homepage
	public static function get_featured($limit = 5){
		return self::find_by_sql("SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE featured = '1' AND status = '1' ORDER BY created DESC LIMIT $limit ");
	}
	
	public static function get_recent($limit = 5){
		return self::find_by_sql("SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' ORDER BY created DESC LIMIT $limit ");
	}
	
	public static function get_percentage($invested,$goal){
		return number_format(($invested / $goal) * 100, 2, '.', '');
	}
	
	// Investment Messages
	
	public static function check_message_owner($id){
		global $database;
		$data = self::find_by_sql("SELECT user_id,investment_id FROM investments_messages WHERE id = '{$id}'");
		$user = User::find_by_id($_SESSION['user_id']);
		if($user->user_id == $data[0]->user_id || $user->user_id == $data[0]->investment_id){
			return true;
		} else {
			return false;
		}
	}
	
	public static function delete_message($id){
		global $database;
		if(self::check_message_owner($id)){
			$database->query("DELETE FROM investments_messages WHERE id = '{$id}' ");
			return true;
		} else {
			return false;
		}
	}
	
	public static function get_message_data($id){
		$data = self::find_by_sql("SELECT message FROM investments_messages WHERE id = '{$id}' LIMIT 1");
		return $data[0]->message;
	}
	
	public static function update_message($id,$message){
		global $database;
		$session = new Session();
		if(self::check_message_owner($id)){
			$database->query("UPDATE investments_messages SET message = '{$message}' WHERE id = '{$id}' ");
			$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The message has been updated.</div>");
			return true;
		} else {
			$session->message("<div class='alert alert-failure'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong. Please try again.</div>");
			return false;
		}
	}
	
	// Investment Updates
	
	public static function check_update_owner($id){
		global $database;
		$data = self::find_by_sql("SELECT creator_id FROM investments WHERE id = '{$id}'");
		$user = User::find_by_id($_SESSION['user_id']);
		if($user->user_id == $data[0]->creator_id){
			return true;
		} else {
			return false;
		}
	}
	
	public static function get_investments($id){
		return self::find_by_sql("SELECT * FROM investments_updates WHERE investment_id = '{$id}' ORDER BY datetime DESC ");
	}
	
	public static function delete_update($id){
		global $database;
		// if(self::check_update_owner($id)){
			$database->query("DELETE FROM investments_updates WHERE id = '{$id}' ");
			return true;
		// } else {
		// 	return false;
		// }
	}
	
	public static function set_thumbnail($id,$name){
		global $database;
		$database->query("UPDATE investments SET thumbnail = '{$name}' WHERE id = '{$id}' ");
		return true;
	}
	
	public static function set_theme($id,$name){
		global $database;
		$database->query("UPDATE investments SET theme = '{$name}' WHERE id = '{$id}' ");
		return true;
	}
	
	public static function get_update_data($id){
		$data = self::find_by_sql("SELECT title,message FROM investments_updates WHERE id = '{$id}' LIMIT 1");
		// return $data[0]->message;
		return json_encode(array('the_title' => $data[0]->title, 'message' => $data[0]->message));
	}
	
	public static function update_update($id,$title,$message){
		global $database;
		$session = new Session();
		$database->query("UPDATE investments_updates SET title = '{$title}', message = '{$message}' WHERE id = '{$id}' ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The update has been updated.</div>");
		return true;
	}
	
	public static function add_update($id, $title, $message){
		global $database;
		$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
		$database->query("INSERT INTO investments_updates (id,investment_id,datetime,title,message,status) VALUES ('','{$id}','{$datetime}','{$title}','{$message}','1') ");
	}

	// Investment FAQ
	
	public static function get_faq($id){
		return self::find_by_sql("SELECT * FROM investments_faq WHERE investment_id = '{$id}' ORDER BY datetime DESC ");
	}
	
	public static function delete_faq($id){
		global $database;
		// if(self::check_update_owner($id)){
			$database->query("DELETE FROM investments_faq WHERE id = '{$id}' ");
			return true;
		// } else {
		// 	return false;
		// }
	}
	
	public static function get_faq_data($id){
		$data = self::find_by_sql("SELECT title,message FROM investments_faq WHERE id = '{$id}' LIMIT 1");
		// return $data[0]->message;
		return json_encode(array('the_title' => $data[0]->title, 'message' => $data[0]->message));
	}
	
	public static function update_faq($id,$title,$message){
		global $database;
		$session = new Session();
		$database->query("UPDATE investments_faq SET title = '{$title}', message = '{$message}' WHERE id = '{$id}' ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The update has been updated.</div>");
		return true;
	}
	
	public static function add_faq($id, $title, $message){
		global $database;
		$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
		$database->query("INSERT INTO investments_faq (id,investment_id,datetime,title,message,status) VALUES ('','{$id}','{$datetime}','{$title}','{$message}','1') ");
	}

	
	public static function delete_custom_theme($id,$image_name){
		global $database;
		$data = self::find_by_sql("SELECT ctheme FROM investments WHERE id = '{$id}'");
		if($image_name == $data[0]->profile_picture){
			return false;
		} else {
			unlink("./assets/project/".$id."/themes/".$image_name);
			return true;
		}
	}
	
	public static function set_custom_theme($id,$name){
		global $database;
		$database->query("UPDATE investments SET ctheme = '{$name}', custom_theme = '1' WHERE id = '{$id}' ");
		return true;
	}
	
	
	// Common Database Methods
	
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
  	}
  
  	public static function find_by_id($id=0) {
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user_id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }
  	
  	public static function find_by_sql($sql="") {
    global $database;
    $result_set = $database->query($sql);
    $object_array = array();
    while ($row = $database->fetch_array($result_set)) {
      $object_array[] = self::instantiate($row);
    }
    return $object_array;
    }

	public static function count_all() {
	  global $database;
	  $sql = "SELECT COUNT(*) FROM ".self::$table_name;
     $result_set = $database->query($sql);
	  $row = $database->fetch_array($result_set);
     return array_shift($row);
	}

	private static function instantiate($record) {
		// Could check that $record exists and is an array
    	$object = new self;
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		// return an array of attribute names and their values
	  $attributes = array();
	  foreach(self::$db_fields as $field) {
	    if(property_exists($this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
	}
	
	protected function sanitized_attributes() {
	  global $database;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $database->escape_value($value);
	  }
	  return $clean_attributes;
	}

}
