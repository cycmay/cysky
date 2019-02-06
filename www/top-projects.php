<?php require_once("includes/inc_files.php"); 

if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
}

$current_page = "top_projects";

$top_projects = Investments::get_top_projects();

?>

<?php $page_title = "热门项目"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

	<?php echo output_message($message); ?>
	
	<ul class="thumbnails">
		<?php foreach($top_projects as $data): ?>
		<li class="span2">
			<div class="thumbnail" style="height: 315px;position: relative;">
				<img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" alt="Image" />
				<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
					<h4><?php echo $data->name; ?></h4>
				</a>
				<p><?php echo substr($data->description, 0, 100)."..."; ?></p>
				<div style="position: absolute; bottom: 0;width: 96%;">
					<span style="float:left;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->amount_invested; ?></span> <span style="float:right;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->investment_wanted; ?></span>
					<div class="clear"></div>
					<div class="progress progress-success progress-striped" style="height:7px;margin-top: 4px;margin-bottom:3px;"><div class="bar" style="width: <?php echo $percentage; ?>%"></div></div>
					<span style="float:left;" class="progress_title">已获支持</span><span style="float:right;" class="progress_title">目标</span>
					<div class="clear"></div>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>



<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>