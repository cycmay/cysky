<?php 

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("../includes/inc_files.php"); 
require_once("../includes/classes/admin.class.php");
if(!$session->is_logged_in()) {redirect_to("../login.php");}
$admin = User::find_by_id($_SESSION['user_id']);
$active_page = "payout";
$requests = User::get_payout_requests();
// echo User::get_payout(2);
?>
<?php $page_title = "支付请求"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

<script>
function payout(id){
	$.ajax({
		type: "POST",
		url: "data.php",
		dataType: "json",
		data: "page=payout_request&get_payout="+id,
		success: function(json){
			if(json){				
				$("#payout #user_id").html(json.user_id);
				$("#payout #current_credit").html("<?php echo CURRENCYSYMBOL; ?>"+json.current_credit);
				$("#payout #amount").html("<?php echo CURRENCYSYMBOL; ?>"+json.amount);
				$("#payout #option").html(json.option);
				$("#payout #mark_as_paid").attr('onclick','mark_as_paid('+id+')');
			}
		}
	});
}
function mark_as_paid(id){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=payout_request&mark_as_paid="+id,
		success: function(data){
			if($.trim(data) == "success"){
				location.reload();
			} else {
				$("#payout #message").html(data);
			}
		}
	});
}
</script>


	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php echo output_message($message); ?>
	
		<div class="title">
			<h1><?php echo $page_title; ?></h1>
		</div>
	
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>ID</th>
					<th>用户ID</th>
					<th>金额</th>
					<th>选项</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($requests as $data) : ?>
				<tr>
					<td><?php echo $data->id ?></td>
					<td><?php echo $data->user_id ?></td>
					<td><?php echo $data->amount ?></td>
					<td><?php echo $data->options ?></td>
					<td><?php echo User::convert_payout_status($data->status) ?></td>
					<td><a href="#payout" data-toggle="modal" onclick="payout('<?php echo $data->id ?>')">编辑</a></td>
				</tr>
				<?php endforeach; ?>
				<?php if(empty($requests)){ ?>
				<tr>
					<td colspan="6">没有未解决的支付请求</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	
	</div>

	</div>

<div class="clear"><!-- --></div>

<div id="payout" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">编辑支付请求</h3>
  </div>
  <div class="modal-body">
	<div id="message"></div>
	<label><strong>用户ID</strong></label>
	<label id="user_id"></label>
	<label><strong>当前有效的积分</strong></label>
	<label id="current_credit"></label>
	<label><strong>总额</strong></label>
	<label id="amount" ></label>
	<label><strong>支付方式</strong></label>
	<label id="option" ></label>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
	<button class="btn btn-danger" id="mark_as_paid">标记为已支付</button>
  </div>
</div>


<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>