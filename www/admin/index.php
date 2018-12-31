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

$active_page = "overview";

?>

<?php $page_title = "管理面板"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<div class="title">
			<h1><?php echo $page_title; ?></h1>
		</div>
	    <!--Load the AJAX API-->
	    <script type="text/javascript" src="../assets/js/google.ajax.js"></script>
	    <script type="text/javascript">

	      // Load the Visualization API and the piechart package.
	      google.load('visualization', '1.0', {'packages':['corechart']});

	      // Set a callback to run when the Google Visualization API is loaded.
	      google.setOnLoadCallback(drawChart);

	      // Callback that creates and populates a data table,
	      // instantiates the pie chart, passes in the data and
	      // draws it.
	      function drawChart() {

	        // Create the data table.
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', '性别');
	        data.addColumn('number', '数量');
	        data.addRows([
	          ['男', <?php echo Admin::count_users('gender','Male'); ?>],
	          ['女', <?php echo Admin::count_users('gender','Female'); ?>]
	        ]);

	        // Set chart options
	        var options = {'title':'用户性别',
	                       'width':500,
	                       'height':400};

	        // Instantiate and draw our chart, passing in some options.
	        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	        chart.draw(data, options);
	      }
	    </script>

	    <div id="chart_div" style="width: 400px;float: left;"></div>

	    <script type="text/javascript">

	      // Load the Visualization API and the piechart package.
	      google.load('visualization', '1.0', {'packages':['corechart']});

	      // Set a callback to run when the Google Visualization API is loaded.
	      google.setOnLoadCallback(drawChart);

	      // Callback that creates and populates a data table,
	      // instantiates the pie chart, passes in the data and
	      // draws it.
	      function drawChart() {

	        // Create the data table.
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', '状态');
	        data.addColumn('number', '数量');
	        data.addRows([
	          ['激活', <?php echo Admin::count_users('activated',1); ?>],
	          ['未激活', <?php echo Admin::count_users('activated',0); ?>],
				 ['Suspended', <?php echo Admin::count_users('suspended',1); ?>]
	        ]);

	        // Set chart options
	        var options = {'title':'用户状态',
	                       'width':500,
	                       'height':400};

	        // Instantiate and draw our chart, passing in some options.
	        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
	        chart.draw(data, options);
	      }
	    </script>

		 <div id="chart_div2" style="width: 400px;float: left;"></div>
	
		<div class="clear"><!-- --></div>
	
		<div class="row-fluid">
			<div class="span4">
				<h2>用户性别</h2>
				<table class="table table-bordered">
				  <tbody>
				    <tr>
				      <td><?php echo gen_male; ?></td>
						<td><?php echo Admin::count_users('gender','<?php echo gen_male; ?>'); ?></td>
				    </tr>
					<tr>
				      <td><?php echo gen_female; ?></td>
						<td><?php echo Admin::count_users('gender','<?php echo gen_female; ?>'); ?></td>
				    </tr>
					<tr>
				      <td><strong><?php echo lang_total; ?>:</strong></td>
						<td><?php echo Admin::count_all_users(); ?></td>
				    </tr>
				  </tbody>
				</table>
				<p><a class="btn" href="users.php">查看所有用户 &raquo;</a></p>
			</div><!--/span-->
			<div class="span4">
				<h2>用户帐户统计</h2>
				<table class="table table-bordered">
				  <tbody>
				    <tr>
				      <td><?php echo lang_active; ?></td>
						<td><?php echo Admin::count_users('activated',1); ?></td>
				    </tr>
					<tr>
				      <td><?php echo lang_inactive; ?></td>
						<td><?php echo Admin::count_users('activated',0); ?></td>
				    </tr>
					<tr>
				      <td><?php echo lang_suspended; ?></td>
						<td><?php echo Admin::count_users('suspended',1); ?></td>
				    </tr>
					<tr>
				      <td><strong><?php echo lang_total; ?>:</strong></td>
						<td><?php echo Admin::count_all_users(); ?></td>
				    </tr>
				  </tbody>
				</table>
			</div><!--/span-->
		</div><!--/row-->

	</div><!--/span-->

	</div>


<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>