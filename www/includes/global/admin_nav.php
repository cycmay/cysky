<div class="span12">
	<ul class="nav nav-pills" style="margin-bottom: -10px;">
		<li<?php if($active_page == "overview"){echo " class='active'";} ?>><a href="index.php"><?php echo aa_overview; ?></a></li>
		<li<?php if($active_page == "users"){echo " class='active'";} ?>><a href="users.php"><?php echo aa_users; ?></a></li>
		<li<?php if($active_page == "categories"){echo " class='active'";} ?>><a href="categories.php"><?php echo aa_categories; ?></a></li>
		<li<?php if($active_page == "projects"){echo " class='active'";} ?>><a href="projects.php"><?php echo aa_projects; ?></a></li>
		<li<?php if($active_page == "awaiting"){echo " class='active'";} ?>><a href="awaiting_review.php"><?php echo aa_awaiting_review; ?></a></li>
		<li<?php if($active_page == "payout"){echo " class='active'";} ?>><a href="payout_requests.php"><?php echo aa_payout_requests; ?></a></li>
		<li<?php if($active_page == "settings"){echo " class='active'";} ?>><a href="settings.php"><?php echo aa_settings; ?></a></li>
	</ul>
	<hr style="margin-bottom: 9px;" />
</div>