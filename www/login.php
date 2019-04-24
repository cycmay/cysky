<?php

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
		print_r($return);
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

	<?php echo output_message($message); ?>

	<div class="login-wrap">
		<div class="login-cell">
			<div class="box-logreg">
				<dl class="m1-z">
					<dt>
						<h3>蚂蚁天使</h3>
						<p>专注于种子轮的创投平台</p>
					</dt>
					<dd>
						<div class="txt-z1">登录CYSKY</div>
						<form action="login.php" method="post">
							<input type="text" name="username" class="txt1" required="required" placeholder="用户名" value="<?php echo htmlentities($username); ?>" />
							<input type="password" name="password" class="txt1 r" required="required" placeholder="密码" value="<?php echo htmlentities($password); ?>" />
							<div class="c"></div>
							<div class="z-mk">
								<label for="lv"> <input type="checkbox" name="remember_me"
									id="lv" class="checkbox" /><span>下次自动登录</span>
								</label> <a href="reset_password.php" class="color-blue r">忘记密码 ?</a><br/>
								<font color="red"></font>
								<div class="a-txt a-txt2"></div>
								
							</div>

							<div class="btn-pl">
								<button type="submit" name="submit" class="btn-z">登 录</button>
								<a href="register.php" class="btn-z btn-z1 r">注 册</a>
							</div>
						</form>
					</dd>
				</dl>
			</div>
		</div>
	</div>



	

