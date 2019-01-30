<?php require_once("includes/inc_files.php"); 

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