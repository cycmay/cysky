<?php require_once("includes/inc_files.php"); 
$current_page = "home";
?>
<?php $page_title = "开发接口 API"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>
<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>
	
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>请求方法</th>
				<th>请求参数</th>
				<th>返回数据</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>user_details</td>
				<td>username</td>
				<td>username, first_name, last_name, gender, last_login, country, investments_made, amount_invested</td>
			</tr>
			<tr>
				<td>user_projects</td>
				<td>username</td>
				<td>id, name, description, goal, invested, ends, main_description, category_id, investor_count (将返回这个用户所有的项目)</td>
			</tr>
			<tr>
				<td>project_details</td>
				<td>id</td>
				<td>id, name, description, goal, invested, ends, main_description, category_id, investor_count</td>
			</tr>
		</tbody>
	</table>
	
	<hr />	
	<p>该api允许你请求所有用户的公共信息通过使用Get变量。所有的请求将返回JSON格式的数据。</p>
	<hr />
	API请求示例：<br />
	<pre><?php echo WWW; ?>api.php?request=user_details&amp;username=admin</pre>
	这里是上面请求API返回的数据：<br />
	<pre>{
  "api_version":"1.0",
  "data":
   {
     "username":"admin",
     "first_name":"Admin",
     "last_name":"Account",
     "gender":"Male",
     "last_login":"2013-02-03 22:55:03",
     "country":"United Kingdom",
     "investments_made":"0",
     "amount_invested":"0"
   }
}</pre>
	这是一个例子，如何解析API传送的json数据：<br />
	<pre>$json = file_get_contents("<?php echo WWW; ?>api.php?request=user_details&amp;username=admin");
$data = json_decode($json);
preprint($data);
</pre>
	
	上面的代码将输出如下：
	<pre>
stdClass Object
(
    [api_version] => 1.0
    [data] => stdClass Object
        (
            [username] => admin
            [first_name] => Admin
            [last_name] => Account
            [gender] => Male
            [last_login] => 2013-02-03 22:55:03
            [country] => United Kingdom
            [investments_made] => 0
            [investments_made] => 0
        )

)
</pre>

这是一个例子，如何获得API版本和用户名：
<pre>
echo "API Version: ".$data->api_version." | Username: ".$data->data->username;
</pre>

上面代码的输出结果:
<pre>
API Version: 1.0 | Username: admin
</pre>


<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>