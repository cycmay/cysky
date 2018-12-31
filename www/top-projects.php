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