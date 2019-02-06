<?php

require_once("../includes/inc_files.php");
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if(isset($_POST['purchase'])) {
	$id = trim($_POST['id']);
	$credit_package = User::get_package_data($id);
	$credit_package = $credit_package[0];
	$item_name = " Purchase ".$credit_package->qty." Credits";
	$item_price = CREDIT_PRICE * $credit_package->qty;
	$user_id = $_SESSION['user_id'];
	$amount = $credit_package->qty;
	$type = "purchase";
	// exit;
} else if(isset($_POST['invest'])) {
	$id = trim($_POST['id']);
	$investment_amount = clean_value($_POST['amount']);
	$investment_type = clean_value($_POST['investment_type']);
	$investment_name = Investments::get_investment_name($id);
	$investment_paymentid = Investments::get_investment_payment_id();
	$item_name = "支持: ".$investment_name;
	$item_body = "支持并投资: ".$investment_name;
	$item_price = $investment_amount;
	$user_id = $_SESSION['user_id'];
	$amount = $investment_amount.",".$id.",".$investment_type;
	$out_trade_no = $user_id.'-'.$id.'-'.$investment_paymentid;
	$type = "invest";
	// exit;
}
// if no action variable, set 'process' as default action
if (empty($_GET['action'])) $_GET['action'] = 'process';
switch ($_GET['action']) {
	case 'process': // Process and order...
		$parameter = array(
			"service"        => "create_direct_pay_by_user",  		//交易类型 立即到账
			"partner"        => ALIPAY_PARTNER,         			//合作商户号
			"return_url"     => $this_script.'?action=return',      //同步返回
			"notify_url"     => $this_script.'?action=notify',      //异步返回
			"_input_charset" => 'UTF-8',  			//字符集，默认为GBK
			"subject"        => $item_name, 	//商品名称，必填
			"body"           => $item_body,       	//商品描述，必填
			"out_trade_no"   => $out_trade_no, 	//商品外部交易号，必填（保证唯一性）
			"total_fee"      => $item_price, 		//商品单价，必填（价格不能为0）
			"payment_type"   => "1",              	//默认为1,不需要修改
			"quantity"       => "1",              	//商品数量，必填
			"paymethod"		=> 'directPay',
			"defaultbank"		=> 'directPay',
			"logistics_fee"      =>'0.00',        	//物流配送费用
			"logistics_payment"  =>'BUYER_PAY',   	//物流费用付款方式：SELLER_PAY(卖家支付)
			"logistics_type"     =>'EXPRESS',     	//物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)
			"show_url"       => WWW,        		//商品相关网站
			"seller_email"   => ALIPAY_SELLER,
		);
		$initial = array('parameter' => $parameter, 'key' => ALIPAY_KEY, 'sign_type' => 'MD5');
		$alipay = new Alipay($initial); // initiate an instance
		$url = $alipay->create_url();
		echo json_encode($url);
	break;
	case 'notify': // successful order...
		$alipay = new alipay_notify(ALIPAY_PARTNER, ALIPAY_KEY, 'MD5', 'UTF-8', 'https');
		$verify_result = $alipay->notify_verify();
		if($verify_result) {   //认证合格
			//获取支付宝的反馈参数
			$order_id      = $_POST['out_trade_no'];   //获取支付宝传递过来的订单号
			$total_fee     = $_POST['total_fee'];      //获取支付宝传递过来的总价格
			$txn_id = explode('-', $order_id);
			$user_id = $txn_id[0]; //get the real order
			$project_id = $txn_id[1];
			$payment_id = $txn_id[2];
			if($_POST['trade_status'] == 'TRADE_SUCCESS') { //交易成功结束
				//这里放入你自定义代码,比如根据不同的trade_status进行不同操作
				Investments::confirm_investment("alipay", $total_fee, $project_id, 0, $user_id);
				echo "success";
			}
			else {
				echo "fail";
			}
		}
		else  {    //认证不合格
		  echo "fail";
		}
	break;
	case 'return': // successful order...
		$alipay = new alipay_notify(ALIPAY_PARTNER, ALIPAY_KEY, 'MD5', 'UTF-8', 'https');
		$verify_result = $alipay->return_verify();
		if($verify_result) {   //认证合格
			//获取支付宝的反馈参数
			$order_id    = $_GET['out_trade_no'];   //获取订单号
			$total_fee  = $_GET['total_fee'];      //获取总价格
			$txn_id = explode('-', $order_id);
			$user_id = $txn_id[0]; //get the real order
			$project_id = $txn_id[1];
			$payment_id = $txn_id[2];
			$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>谢谢您，我已经收到您的付款。</div>");
			redirect_to('../index.php');
		}
		else  {    //认证不合格
			$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>支付宝交易认证失败。</div>");
			redirect_to('../index.php');
		}
	break;
}

?>