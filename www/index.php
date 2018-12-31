<?php require_once("includes/inc_files.php");
if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
}

$current_page = "home";

$top_projects = Investments::get_top_projects(5);
$recent_projects = Investments::get_recent(5);
$featured_projects = Investments::get_featured(5);

$new_site_credit = SITE_CREDIT + "150";
$database->query("UPDATE core_settings SET data = '{$new_site_credit}' WHERE name = 'SITE_CREDIT' ");

?>
<?php $page_title = "BICYCLE国内首家开源众筹系统!"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>
		<div style="max-width: 400px; margin: 0 auto 10px;">
	              <a href="# class="btn btn-large btn-block btn-primary">chishiba</a>
	            </div>

	<hr />

				<div class="row-fluid center">
				  <div class="span2"><h3>管理权限</h3><code style="padding-bottom: 4px;">Username: admin</code><br /><code>Password: admin</code></div>
				</div>
				<br />

	<?php if(!empty($top_projects)){ ?>
	<hr />
	<h2>热门项目</h2>
	<br />
	<ul class="thumbnails">
		<?php foreach($top_projects as $data): ?>
		<li class="span2">
			<div class="thumbnail" style="position: relative; height: 340px;">
				<?php if(strtotime($data->expires) < time()){ ?>
				<div class="ribbon-wrapper-right"><div class="ribbon-red">已关闭</div></div>
				<?php } ?>
				<img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" style="height:110px" alt="Image" />
				<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
					<h4><?php echo $data->name; ?></h4>
				</a>
				<p><?php echo substr($data->description, 0, 100)."..."; ?></p>
				<div style="position: absolute; bottom: 0;width: 96%;">
					<span style="float:left;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->amount_invested; ?> (<?php echo $percentage = Investments::get_percentage($data->amount_invested,$data->investment_wanted) ?>%)</span><span style="float:right;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->investment_wanted; ?></span>
					<div class="clear"></div>
					<div class="progress progress-success progress-striped" style="height:7px;margin-top: 4px;margin-bottom:3px;"><div class="bar" style="width: <?php echo $percentage; ?>%"></div></div>
					<span style="float:left;" class="progress_title">已获支持</span><span style="float:right;" class="progress_title">目标</span>
					<div class="clear"></div>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php } ?>

	<?php if(!empty($recent_projects)){ ?>
	<hr />
	<h2>最新上线</h2>
	<br />
	<ul class="thumbnails">
		<?php foreach($recent_projects as $data): ?>
		<li class="span2">
			<div class="thumbnail" style="position: relative; height: 340px;">
				<?php if(strtotime($data->expires) < time()){ ?>
				<div class="ribbon-wrapper-right"><div class="ribbon-red">已关闭</div></div>
				<?php } ?>
				<img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" style="height:110px" alt="Image" />
				<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
					<h4><?php echo $data->name; ?></h4>
				</a>
				<p><?php echo substr($data->description, 0, 100)."..."; ?></p>
				<div style="position: absolute; bottom: 0;width: 96%;">
					<span style="float:left;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->amount_invested; ?> (<?php echo $percentage = Investments::get_percentage($data->amount_invested,$data->investment_wanted) ?>%)</span><span style="float:right;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->investment_wanted; ?></span>
					<div class="clear"></div>
					<div class="progress progress-success progress-striped" style="height:7px;margin-top: 4px;margin-bottom:3px;"><div class="bar" style="width: <?php echo $percentage; ?>%"></div></div>
					<span style="float:left;" class="progress_title">已获支持</span><span style="float:right;" class="progress_title">目标</span>
					<div class="clear"></div>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php } ?>

	<?php if(!empty($featured_projects)){ ?>
	<hr />
	<h2>推荐项目</h2>
	<br />
	<ul class="thumbnails">
		<?php foreach($featured_projects as $data): ?>
		<li class="span2">
			<div class="thumbnail" style="position: relative; height: 340px;">
				<?php if(strtotime($data->expires) < time()){ ?>
				<div class="ribbon-wrapper-right"><div class="ribbon-red">CLOSED</div></div>
				<?php } ?>
				<img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" style="height:110px" alt="Image" />
				<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
					<h4><?php echo $data->name; ?></h4>
				</a>
				<p><?php echo substr($data->description, 0, 100)."..."; ?></p>
				<div style="position: absolute; bottom: 0;width: 96%;">
					<span style="float:left;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->amount_invested; ?> (<?php echo $percentage = Investments::get_percentage($data->amount_invested,$data->investment_wanted) ?>%)</span><span style="float:right;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->investment_wanted; ?></span>
					<div class="clear"></div>
					<div class="progress progress-success progress-striped" style="height:7px;margin-top: 4px;margin-bottom:3px;"><div class="bar" style="width: <?php echo $percentage; ?>%"></div></div>
					<span style="float:left;" class="progress_title">已获支持</span><span style="float:right;" class="progress_title">目标</span>
					<div class="clear"></div>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>