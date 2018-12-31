<?php require_once("includes/inc_files.php"); 

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
}

$current_page = "top_investors";

$top_investors = User::get_top_investors();

?>

<?php $page_title = "支持者"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

	<?php echo output_message($message); ?>
	<table class="table table-bordered">
	  <thead>
	    <tr>
			<th>#</th>
			<th>姓名</th>
			<th>账户</th>
			<th>投资项目</th>
			<th>投资金额</th>
			<!--<th>国家</th>-->
	    </tr>
	  </thead>
	  <tbody>
	    	<?php $counter = 1; $mini_counter = 1; $total_rank = User::get_investment_rank($top_investors); foreach($top_investors as $investor){ ?>
			<tr>
				<?php $count = $total_rank[$investor->investments_made]; if($count > 1){?>
					<?php if($mini_counter == 1){ ?>
						<td rowspan="<?php echo $count; ?>"><?php echo $counter; ?></td>
					<?php  } $mini_counter++; ?>
				<?php } else { ?>
					<td><?php echo $counter; $mini_counter = 1; ?></td>
				<?php } ?>
				<td><?php echo $investor->first_name." ".$investor->last_name; ?></td>
				<td><?php echo "<a href=\" ".WWW."profile.php?username=".$investor->username." \">".$investor->username."</a>"; ?></td>
				<td><?php echo $investor->investments_made; ?></td>
				<td><?php echo CURRENCYSYMBOL." ".$investor->amount_invested; ?></td>
				<!--<td><?php echo $investor->country; ?></td>-->
			</tr>
			<?php $counter++; } ?>
	  </tbody>
	</table>



<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>