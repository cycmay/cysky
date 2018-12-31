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

if((empty($_GET['email'])) || (empty($_GET['hash']))) {
    $message = "";
    
  } else {
	$email = trim($_GET['email']);
	$hash = trim($_GET['hash']);
	
	global $database;
	
	// Escape email and hash values to help prevent sql injection.
	$email = $database->escape_value($email);
	$hash = $database->escape_value($hash);
	
	// Check if the provided information is in the database
	Activation::check_activation($email, $hash);
}

if (isset($_POST['submit'])) { // Form has been submitted.
	$email = trim($_POST['email']);
	$hash = trim($_POST['hash']);
	
	if ((!$email == "") && (!$hash == "")) {
		Activation::check_activation($email, $hash);
	} else {
		$message = "<div class='notification-box warning-notification-box'><p>Nothing Entered.</p><a href='#' class='notification-close warning-notification-close'>x</a></div><!--.notification-box .notification-box-warning end-->";
	}
} else { // Form has not been submitted.
	$email = "";
	$hash = "";
}

	
if (isset($_POST['resend_code'])) { // Form has been submitted.
	$email = trim($_POST['email']);
	
	if (!$email == "") {
		Activation::check_resend_code($email);
	} else {
		$message = "<div class='notification-box warning-notification-box'><p>Nothing Entered.</p><a href='#' class='notification-close warning-notification-close'>x</a></div><!--.notification-box .notification-box-warning end-->";
	}
} else { // Form has not been submitted.
	$email = "";
	$hash = "";
}

$current_page = "forgot_password";

?>
<?php $page_title = "激活您的账户"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

<?php echo output_message($message); ?>

<?php if((!isset($_GET['email'])) && (!isset($_GET['hash'])) ) : ?>
<div class="row">
	<form action="activate.php" method="post" >
		<div class="span6 center">
			<div class="span6">
		      <h3>激活账户</h3>
			</div>
			<div class="span6">
		      <input type="text" class="span4" name="email" required="required" placeholder="电邮地址" value="<?php echo htmlentities($email); ?>" />
			</div>
			<div class="span6">
		      <input type="text" class="span4" name="hash" required="required" placeholder="确认码" value="<?php echo htmlentities($hash); ?>" />
			</div>
			<div class="span6">
				<br />
				<input class="btn btn-primary" type="submit" name="submit" value="激活" />
			</div>
		</div>
	</form>
	<form action="activate.php" method="post" >
		<div class="span6 center">
			<div class="span6">
		      <h3>重新发送激活确认码</h3>
			</div>
			<div class="span6">
		      <input type="text" class="span4" name="email" required="required" placeholder="电邮地址" value="<?php echo htmlentities($email); ?>" />
			</div>
			<div class="span6">
				<br />
				<input class="btn btn-primary" type="submit" name="resend_code" value="重新发送" />
			</div>
		</div>
	</form>
</div>
<?php endif ?>
<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>