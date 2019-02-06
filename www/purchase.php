<?php

require_once("includes/inc_files.php");

if(!$session->is_logged_in()) {redirect_to("login.php");}

$user = User::find_by_id($_SESSION['user_id']);
$current_page = "";
$location = "gateway/paypal.php";

$credit_packages = User::get_credit_packages();

$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$total_count = count($credit_packages);
$pagination = new Pagination($page, $per_page, $total_count);
$sql = "SELECT * FROM credit_packages WHERE status = '1' LIMIT {$per_page} OFFSET {$pagination->offset()}";
$credit_packages = User::find_by_sql($sql);

?>
<?php $page_title = "充值积分"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

<?php if(empty($credit_packages)){ ?>
	<strong>对不起，没有发现任何积分。</strong>
<?php } else { ?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>套餐</th>
      <th>积分</th>
      <th>价格</th>
      <th style="width: 100px;"></th>
    </tr>
  </thead>
  <tbody>
	<?php foreach($credit_packages as $data): ?>
    <tr>
		<form action="<?php echo $location; ?>" method="POST" id="purchase" class="modal">
		<td><?php echo $data->name; ?></td>
		<td><?php echo $data->qty; ?></td>
		<td><?php echo CURRENCYSYMBOL . CREDIT_PRICE * $data->qty; ?></td>
		<!-- <td><a href="purchase.php?purchase=<?php echo $data->id; ?>">Purchase</a></td> -->
		<td><button class="btn btn-link" style="margin: -6px 0;" type="submit" name="purchase">购买</button></td>
		<input type="hidden" name="id" value="<?php echo $data->id; ?>" />
		</form>
    </tr>
	<?php endforeach; ?>
  </tbody>
</table>

<?php
	if($pagination->total_pages() > 1) {
	echo "<div class='pagination pagination-centered'><ul>";

		for($i=1; $i <= $pagination->total_pages(); $i++) {
			if($i == $page) {
				echo " <li class='active'><a>{$i}</a></li> ";
			} else {
				echo " <li><a href=\"find.php?search={$query}&amp;filter={$_GET['filter']}&amp;page={$i}\">{$i}</a></li> "; 
			}
		}

	}

	echo "</ul>";
?>
	
	<?php if(isset($_GET['purchase'])) {?>
		<form action="<?php echo $location; ?>" method="POST" id="purchase" class="modal">
		    <div class="modal-header"><a href="purchase.php" class="close" data-dismiss="modal">×</a>
		        <h3 id="myModalLabel">Purchase credits</h3>
		    </div>
		    <div class="modal-body">
		      <strong>Are you sure you want to purchase this package?</strong>
				<input type="hidden" name="id" value="<?php echo $_GET['purchase']; ?>" />
		    </div>
		    <div class="modal-footer">
			   <a href="purchase.php" class="btn">Close</a>
			   <button class="btn btn-danger" type="submit" name="purchase">Purchase</button>
			 </div>
		</form>​
		<div class="modal-backdrop fade in"></div>
	<?php } ?>
	
<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>