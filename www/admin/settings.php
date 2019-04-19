<?php 

require_once("../includes/inc_files.php"); 
require_once("../includes/classes/admin.class.php");

login_check();

$active_page = "settings";

$settings = Core_Settings::find_by_sql("SELECT * FROM core_settings");

//print_r($settings);

if(isset($_POST['update_settings'])){
	
	if(DEMO_MODE == "OFF"){
		// $max = count($settings);
		// for($i=1;$i <= $max; $i++){
		// 	$array =  (array) $settings[$i-1];
		// 	$$array['name'] = $_POST[$array['name']];
		// 	$sql = "UPDATE core_settings SET data = '".$data."' WHERE name = '".$name."' ";
		// 	$database->query($sql);
		// }
		
		foreach($settings as $setting) {
			$array =  (array) $setting;
			$$array['name'] = $_POST[$array['name']];
			
			// echo $$array['name']."<hr />";
			$database->query("UPDATE core_settings SET data = '".$$array['name']."' WHERE name = '".$array['name']."' ");
		}

		$database->query("UPDATE core_settings SET data = 'OFF' WHERE name = 'DEMO_MODE' ");

		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>设置已成功更新。</div>");
	} else {
		$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>对不起，演示模式已启用，您不能做任何操作。</div>");
	}
	
	redirect_to("settings.php");
} else {
	foreach($settings as $setting) {
		$array =  (array) $setting;

		${$array['name']} = $array['data'];
	}
}

?>

<?php $page_title = "站点配置"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php echo output_message($message); ?>
	
		<div class="title">
			<h1><?php echo $page_title; ?></h1>
		</div>
	
		<form action="settings.php" method="POST">
			<div class="row-fluid">
				<div class="span3">
					<label>站点访问路径</label>
			      <input type="text" name="WWW" class="span12" required="required" value="<?php echo htmlentities($WWW); ?>" />
				</div>
				<div class="span3">
					<label>站点名称</label>
		      	<input type="text" name="SITE_NAME" class="span12" required="required" value="<?php echo $SITE_NAME; ?>" />
				</div>
				<div class="span3">
					<label>站点描述</label>
					<input type="text" name="SITE_DESC" class="span12" required="required" value="<?php echo $SITE_DESC; ?>" />
				</div>
				<div class="span3">
					<label>站点关键词</label>
					<input type="text" name="SITE_KEYW" class="span12" required="required" value="<?php echo $SITE_KEYW; ?>" />
				</div>
			</div>
			<div class="row-fluid">
				<div class="span3">
					<label>管理路径</label>
					<input type="text" name="ADMINDIR" class="span12" required="required" value="<?php echo $ADMINDIR; ?>" />
				</div>
				<div class="span3">
					<label>站点电邮地址</label>
					<input type="text" name="SITE_EMAIL" class="span12" required="required" value="<?php echo $SITE_EMAIL; ?>" />
				</div>
				<div class="span2">
					<label>验证邮件</label>
			    	<select name="VERIFY_EMAIL" class="span12" required="required" value="<?php echo $VERIFY_EMAIL ?>">
						<option value="是" <?php if($VERIFY_EMAIL == 'YES') { echo 'selected="selected"';} else { echo ''; } ?>>Yes</option>
						<option value="否" <?php if($VERIFY_EMAIL == 'NO') { echo 'selected="selected"';} else { echo ''; } ?>>No</option> 
					</select>
				</div>
				<div class="span2">
					<label>站点维护</label>
			      <select name="MAINTENANCE_MODE" class="span12" required="required" value="<?php echo $MAINTENANCE_MODE ?>">
						<option value="是" <?php if($MAINTENANCE_MODE == 'ON') { echo 'selected="selected"';} else { echo ''; } ?>>On</option>
						<option value="否" <?php if($MAINTENANCE_MODE == 'OFF') { echo 'selected="selected"';} else { echo ''; } ?>>Off</option> 
					</select>
				</div>
				<div class="span2">
					<label>项目发布审核</label>
			    	<select name="NEW_PROJECT_VERIFY" class="span12" required="required" value="<?php echo $NEW_PROJECT_VERIFY ?>">
						<option value="是" <?php if($NEW_PROJECT_VERIFY == 'YES') { echo 'selected="selected"';} else { echo ''; } ?>>Yes</option>
						<option value="否" <?php if($NEW_PROJECT_VERIFY == 'NO') { echo 'selected="selected"';} else { echo ''; } ?>>No</option> 
					</select>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span2">
					<label>货币代码</label>
					<input type="text" name="CURRENCY_CODE" class="span12" required="required" value="<?php echo htmlentities($CURRENCY_CODE); ?>" />
				</div>
				<div class="span2">
					<label>货币符号</label>
					<input type="text" name="CURRENCYSYMBOL" class="span12" required="required" value="<?php echo htmlentities($CURRENCYSYMBOL); ?>" />
				</div>
				<div class="span2">
					<label>PayPal沙盒测试</label>
			      <select name="PAYPAL_SANDBOX" class="span12" required="required" value="<?php echo $PAYPAL_SANDBOX ?>">
						<option value="YES" <?php if($PAYPAL_SANDBOX == 'YES') { echo 'selected="selected"';} else { echo ''; } ?>>是</option>
						<option value="NO" <?php if($PAYPAL_SANDBOX == 'NO') { echo 'selected="selected"';} else { echo ''; } ?>>否</option> 
					</select>
				</div>
				<div class="span4">
					<label>PayPal商家电邮</label>
					<input type="text" name="PAYPAL_EMAIL" class="span12" required="required" value="<?php echo htmlentities($PAYPAL_EMAIL); ?>" />
				</div>
			</div>
			<!--
			<div class="row-fluid">	
				<div class="span4">
					<label>支付宝合作身份者ID</label>
					<input type="text" name="ALIPAY_PARTNER" class="span12" required="required" value="<?php echo $ALIPAY_PARTNER; ?>" />
				</div>	
				<div class="span4">
					<label>支付宝安全检验码</label>
					<input type="text" name="ALIPAY_KEY" class="span12" required="required" value="<?php echo $ALIPAY_KEY; ?>" />
				</div>	
				<div class="span4">
					<label>签约支付宝商家账号</label>
					<input type="text" name="ALIPAY_SELLER" class="span12" required="required" value="<?php echo $ALIPAY_SELLER; ?>" />
				</div>
			</div>
			-->
			<div class="row-fluid">
				<div class="span3">
					<label>数据库密钥</label>
					<input type="text" name="DATABASE_SALT" class="span12" required="required" value="<?php echo htmlentities($DATABASE_SALT); ?>" />
				</div>
				<div class="span4">
					<label>时区</label>
			      <select name="TIMEZONE" class="span12" required="required" value="<?php echo $TIMEZONE ?>">
						<?php
						
						foreach ($timezones as $key => $value) {
							if($value == $TIMEZONE){
								$selected = ' selected="selected"';
							} else {
								$selected = '';
							}
							echo '<option value="' .$value. '" '.$selected.' >' .$key. '</option>';
						}
						
						?>
					</select>
				</div>
				
				<div class="span2">
					<label>每积分价格</label>
					<input type="text" name="CREDIT_PRICE" class="span12" required="required" value="<?php echo htmlentities($CREDIT_PRICE); ?>" />
				</div>
				<div class="span3">
					<label>默认项目主题</label>
					<input type="text" name="DEFAULT_THEME" class="span12" required="required" value="<?php echo htmlentities($DEFAULT_THEME); ?>" />
				</div>
				
			</div>
			<div class="row-fluid">	
				<div class="span1">
					<label>OAuth</label>
					<select name="OAUTH" class="span12" required="required" value="<?php echo $OAUTH ?>">
						<option value="ON" <?php if($OAUTH == 'ON') { echo 'selected="selected"';} else { echo ''; } ?>>启用</option>
						<option value="OFF" <?php if($OAUTH == 'OFF') { echo 'selected="selected"';} else { echo ''; } ?>>关闭</option> 
					</select>
				</div>	
				<div class="span2">
					<label>网站主题名称</label>
					<input type="text" name="THEME_NAME" class="span12" required="required" value="<?php echo htmlentities($THEME_NAME); ?>" />
				</div>
				<div class="span1">
					<label>分页</label>
					<input type="text" name="PAGINATION_PER_PAGE" class="span12" required="required" value="<?php echo htmlentities($PAGINATION_PER_PAGE); ?>" />
				</div>
				<div class="span2">
					<label>需要目标支付</label>
					<select name="REQUIRE_GOAL" class="span12" required="required" value="<?php echo $REQUIRE_GOAL ?>">
						<option value="YES" <?php if($REQUIRE_GOAL == 'YES') { echo 'selected="selected"';} else { echo ''; } ?>>是</option>
						<option value="NO" <?php if($REQUIRE_GOAL == 'NO') { echo 'selected="selected"';} else { echo ''; } ?>>否</option> 
					</select>
				</div>
				<div class="span2">
					<label>拿提成</label>
					<select name="TAKE_CUT" class="span12" required="required" value="<?php echo $TAKE_CUT ?>">
						<option value="YES" <?php if($TAKE_CUT == 'YES') { echo 'selected="selected"';} else { echo ''; } ?>>是</option>
						<option value="NO" <?php if($TAKE_CUT == 'NO') { echo 'selected="selected"';} else { echo ''; } ?>>否</option> 
					</select>
				</div>	
				<div class="span2">
					<label>提成百分比</label>
					<input type="text" name="CUT_PERCENTAGE" class="span12" required="required" value="<?php echo htmlentities($CUT_PERCENTAGE); ?>" />
				</div>
				<div class="span2">
					<label>目标站点积分</label>
					<input type="text" name="SITE_CREDIT" class="span12" required="required" value="<?php echo htmlentities($SITE_CREDIT); ?>" />
				</div>
			</div>

			<div class="form-actions" style="text-align: center;">
				<input class="btn btn-primary" type="submit" name="update_settings" value="更新设置" />
			</div>
		</form>
	
	</div>

	</div>


<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>