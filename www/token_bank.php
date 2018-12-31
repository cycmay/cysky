<?php
require_once("includes/inc_files.php");

if(!$session->is_logged_in()) {redirect_to("login.php");}

$user = User::find_by_id($_SESSION['user_id']);
$invites = Invites::find_invites($user->user_id);
$invite_count = Invites::count_all($user->user_id);

$location = "token_bank.php";

$current_page = "token_bank";

if (isset($_POST['bank_tokens'])) {
	if (DEMO_MODE == 'ON') {
		$message = "<div class='notification-box warning-notification-box'><p>Sorry, you can't do that while demo mode is enabled.</p><a href='#' class='notification-close warning-notification-close'>x</a></div><!--.notification-box .notification-box-warning end-->";
	} else {
		if($user->account_lock){
			$message = "<div class='notification-box warning-notification-box'><p>Sorry, you can't do that while your account lock is enabled.</p><a href='#' class='notification-close warning-notification-close'>x</a></div><!--.notification-box .notification-box-warning end-->";
			$bank_tokens = "";
		} else {
			$bank_tokens = $_POST['bank_tokens'];
			$bank_tokens = preg_replace("/[^0-9]/", '', $bank_tokens);
			if($user->tokens < $bank_tokens){
				$message = "<div class='notification-box error-notification-box'><p>Sorry, you don't have {$bank_tokens} tokens to bank.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->";
			} else {
				User::token_bank($user->user_id, $user->tokens, $user->bank_tokens, $bank_tokens, "bank", $location);
			}
		}
	}
} else {
	$bank_tokens = "";
}

if (isset($_POST['withdraw_tokens'])) {
	if (DEMO_MODE == 'ON') {
		$message = "<div class='notification-box warning-notification-box'><p>Sorry, you can't do that while demo mode is enabled.</p><a href='#' class='notification-close warning-notification-close'>x</a></div><!--.notification-box .notification-box-warning end-->";
	} else {
		if($user->account_lock){
			$message = "<div class='notification-box warning-notification-box'><p>Sorry, you can't do that while your account lock is enabled.</p><a href='#' class='notification-close warning-notification-close'>x</a></div><!--.notification-box .notification-box-warning end-->";
			$withdraw_tokens = "";
		} else {
			$withdraw_tokens = $_POST['withdraw_tokens'];
			$withdraw_tokens = preg_replace("/[^0-9]/", '', $withdraw_tokens);
			if($user->bank_tokens < $withdraw_tokens){
				$message = "<div class='notification-box error-notification-box'><p>Sorry, you don't have {$withdraw_tokens} tokens to withdraw.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->";
			} else {
				User::token_bank($user->user_id, $user->tokens, $user->bank_tokens, $withdraw_tokens, "withdraw", $location);
			}
		}
	}
} else {
	$withdraw_tokens = "";
}

?>
<?php $page_title = "Token Bank"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

<form action="<?php echo $location; ?>" method="post" class="form-horizontal">
	
<div class="row-fluid">
	<div class="span3 center">
		<h3>Currently Active</h3>
		<label style="font-size: 35px;color: #2EB0E4;"><?php echo number_format($user->tokens, 0, '.', ',') ?></label>
	</div>
	<div class="span3 center">
		<form action="<?php echo $location; ?>" method="post">
         <h3>Deposit Tokens</h3>
			<input type="text" name="bank_tokens" class="center" required="required" value="<?php echo htmlentities($bank_tokens); ?>" />
			<input class="btn" style="margin-top: 15px;" type="submit" name="submit" value="Deposit" />
		</form>
	</div>
	<div class="span3 center">	
		<h3>Currently Banked</h3>
		<label style="font-size: 35px;color: #2EB0E4;"><?php echo number_format($user->bank_tokens, 0, '.', ',')?></label>
	</div>
	<div class="span3 center">
		<form action="<?php echo $location; ?>" method="post">
         <h3>Withdraw Tokens</h3>
			<input type="text" id="withdraw_tokens" name="withdraw_tokens" class="center" required="required" value="<?php echo htmlentities($withdraw_tokens); ?>" />
			<input class="btn" style="margin-top: 15px;" type="submit" name="submit" value="Withdraw" />
		</form>
	</div>
</div>

</form>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>