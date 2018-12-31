<?php

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("includes/inc_files.php");

if($session->is_logged_in()) {
  redirect_to("index.php");
}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.
	
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	
	if ((!$username == '') && (!$password == '')) {
		$current_ip = $_SERVER['REMOTE_ADDR'];
		$remember_me = trim($_POST['remember_me']);
		$return = User::check_login($username, $password, $current_ip, $remember_me);
		if($return == "false"){
			redirect_to("login.php");
		} else {
			redirect_to($return);
			// print_r($return);
		}
		
	} else {
		$message = "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Please fill in all required fields.</div>";
	}
  
} else { // Form has not been submitted.
  $username = "";
  $password = "";
}

$current_page = "login";

?>
<?php $page_title = "登录"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>


	<?php echo output_message($message); ?>

	<form action="login.php" method="post" class="center">
		<div class="row-fluid">
			<div class="span12">
		        <input type="text" class="span4" name="username" required="required" placeholder="用户名" value="<?php echo htmlentities($username); ?>" />
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
		        <input type="password" class="span4" name="password" required="required" placeholder="密码" value="<?php echo htmlentities($password); ?>" />
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<input type="checkbox" name="remember_me" value="yes" />
				<span>记得我? (使用Cookie)</span>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<a href="reset_password.php">忘记密码?</a>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<br />
				<input class="btn btn-primary" type="submit" name="submit" value="登录" />
			</div>
		</div>
	</form>
	
	<?php if(OAUTH == "ON"){ ?>
		<hr />
		
	<div class="row">
		<div class="span12 center">
			<div class="span12">
				<a href="<?php echo WWW; ?>auth/sinaweibo" class="zocial sinaweibo">新浪微博</a>
				<a href="<?php echo WWW; ?>auth/qqweibo" class="zocial qqweibo">腾讯微博</a>
				<a href="<?php echo WWW; ?>auth/facebook" class="zocial facebook">Facebook</a>
				<a href="<?php echo WWW; ?>auth/twitter" class="zocial twitter">Twitter</a>
			</div>
		</div>
	</div>
	<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>
