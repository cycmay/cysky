<?php require_once("includes/inc_files.php"); 

if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
}

$current_page = "top_projects";

$top_projects = Investments::get_top_projects();

?>

<?php $page_title = "热门项目"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<form action="#" method="get" id="projectForm">
    <input type="hidden"  id="investDirection" name="investDirection" value=""/>
    <input type="hidden"  id="sign" name="sign" value="众筹融资项目">
    <div class="wp">
        <ul class="ul-step mb30 fix">
            <li  class="li-bg">
                <a href="javascript:getTab('众筹融资项目')"><?php echo $page_title; ?></a>
            </li>
            <li >
                <a href="javascript:getTab('待领投项目')">待领投项目</a>
            </li>
            <li >
                <a href="javascript:getTab('已完成项目')">已完成项目</a>
            </li>
        </ul>

        <div class="tit-main fix mb70">
            <ul class="l fix">
            	<li class="on"><a href="javascript:getProjects('')">全部</a></li>
            	<li ><a href="javascript:getProjects('人工智能')">人工智能</a></li>
            	<li ><a href="javascript:getProjects('硬件')">硬件</a></li>
            	<li ><a href="javascript:getProjects('软件工具')">软件工具</a></li>
            	<li ><a href="javascript:getProjects('社交网络')">社交网络</a></li>
            	<li ><a href="javascript:getProjects('企业服务')">企业服务</a></li>
            	<li ><a href="javascript:getProjects('新技术')">新技术</a></li>
            	<li ><a href="javascript:getProjects('教育')">教育</a></li>
            	<li ><a href="javascript:getProjects('医疗')">医疗</a></li>
            	<li ><a href="javascript:getProjects('金融')">金融</a></li>
            	<li ><a href="javascript:getProjects('房产家居')">房产家居</a></li>
            	<li ><a href="javascript:getProjects('交通出行')">交通出行</a></li>
            	<li ><a href="javascript:getProjects('物流')">物流</a></li>
            	<li ><a href="javascript:getProjects('电子商务')">电子商务</a></li>
            	<li ><a href="javascript:getProjects('共享经济')">共享经济</a></li>
            	<li ><a href="javascript:getProjects('消费生活')">消费生活</a></li>
            	<li ><a href="javascript:getProjects('体育')">体育</a></li>
            	<li ><a href="javascript:getProjects('文化娱乐')">文化娱乐</a></li>
            	<li ><a href="javascript:getProjects('媒体广告')">媒体广告</a></li>
            	<li ><a href="javascript:getProjects('旅游')">旅游</a></li>
            	<li ><a href="javascript:getProjects('游戏动漫')">游戏动漫</a></li>
            	<li ><a href="javascript:getProjects('农业')">农业</a></li>
            	<li ><a href="javascript:getProjects('能源环保')">能源环保</a></li>
            	<li ><a href="javascript:getProjects('其他')">其他</a></li>
            </ul>
            <!--
            <div class="r choose">
                <span class="l">排序方式： </span>
                <ul class="l">
                <li class="on"><a href="">按时间</a></li>
                   <li class="on"><a >按时间</a></li>
                </ul>
            </div>-->
        </div>
       

        <ul class="ul-line1">
	<?php foreach($top_projects as $data): ?>
        <li>
        	<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
            <div class="pic">
                <img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" height="275" width="670" alt="Image">
            </div>
            </a>
            <div class="txt">
                <div class="tit">
                    <span class="btn-m1 r">众筹融资中</span>
                    <h2><a href="<?php echo WWW."project.php?id=".$data->id; ?>"><?php echo $data->name; ?></a></h2>
                    <p><?php echo substr($data->description, 0, 20)."..."; ?></p>
                </div> 
                <div class="cen">
                    <p class="p1">融资金额：<?php echo $data->amount_invested; ?> ETH</p>
                   <div class="load-a1">
                        <span style="width: <?php echo $percentage = Investments::get_percentage($data->amount_invested,$data->investment_wanted) ?>%;"></span><em><b><?php echo $percentage; ?></b>%</em>
                    </div>
                    <p class="link-user pt5"> 领投人：<a href="#">火与冰</a></p>
                   	<span class="s1">已有 <?php echo Investments::count("made",$data->id); ?> 人认购投资</span>
                    <p class="p2 mb10">
                      	<em>标签： </em>
         			<span class="r">珠海市</span>
                    </p>
                   <a href="<?php echo WWW."project.php?id=".$data->id; ?>" class="btn-b1">查看详情</a>
                </div>
            </div>
            
        </li>
        <?php endforeach; ?>
        </ul>
        <div class="box-load mb50">
            <p><button class="btn-n1" id="load-more" href="">加载更多</button></p>
        </div>
    </div>
</form>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>