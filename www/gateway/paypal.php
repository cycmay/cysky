<?php

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("../includes/inc_files.php");

$paypal_sandbox = PAYPAL_SANDBOX;
$paypal_email = PAYPAL_EMAIL;

$pp = new paypal(); // initiate an instance
$session = new Session();

if($paypal_sandbox == "YES"){
	$pp->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$pp->paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if(isset($_POST['purchase'])) {
	$id = trim($_POST['id']);
	$credit_package = User::get_package_data($id);
	$credit_package = $credit_package[0];
	$pp_item_name = " Purchase ".$credit_package->qty." Credits";
	$pp_item_price = CREDIT_PRICE * $credit_package->qty;
	$user_id = $_SESSION['user_id'];
	$amount = $credit_package->qty;
	$type = "purchase";
	// exit;
} else if(isset($_POST['invest'])) {
	$id = trim($_POST['id']);
	$investment_amount = clean_value($_POST['amount']);
	$investment_type = clean_value($_POST['investment_type']);
	$investment_name = Investments::get_investment_name($id);
	$pp_item_name = " Investment in ".$investment_name;
	$pp_item_price = $investment_amount;
	$user_id = $_SESSION['user_id'];
	$amount = $investment_amount.",".$id.",".$investment_type;
	$type = "invest";
	// exit;
}

// if no action variable, set 'process' as default action
if (empty($_GET['action'])) $_GET['action'] = 'process';

switch ($_GET['action']) {
	case 'process': // Process and order...
		$pp->add_field('business', $paypal_email);
		$pp->add_field('return', $this_script.'?action=success');
		$pp->add_field('cancel_return', $this_script.'?action=cancel');
		$pp->add_field('notify_url', $this_script.'?action=ipn');
		$pp->add_field('item_name', $pp_item_name);
		$pp->add_field('amount', $pp_item_price);
		$pp->add_field('currency_code', CURRENCY_CODE);
		$pp->add_field('custom', $type.",".$user_id.",".$amount);
		$pp->submit_paypal_post();
	break;
	case 'success': // successful order...
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Thank You, your payment has been received. Your ad will be processed shortly by a member of staff.</div>");
		redirect_to('../index.php');
	break;
	case 'cancel': // Canceled Order...
		// echo "<html>
		// <head><title>Canceled</title></head>
		// <body><h2>The order was canceled.</h2>";
		// echo "</body></html>";
		$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>The transaction was cancelled.</div>");
		redirect_to('../index.php');
	break;
	case 'ipn': // For IPN validation...
		if ($pp->validate_ipn()) {
			
			if($pp->ipn_data['payment_status'] == "Completed"){

				$return_data = explode(",", $pp->ipn_data['custom']);
				if($return_data[0] == "purchase"){
					User::add_credit($return_data[1], $return_data[2]);
				} else if($return_data[0] == "invest"){
					Investments::confirm_investment("paypal", $return_data[2], $return_data[3], $return_data[4], $return_data[1]);
				}
				
			}
		}
	break;
}

?>