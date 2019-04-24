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
<?php $page_title = "CYSKY股权众筹系统!"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>
	

<style type="text/css">
	@import url(<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/mystyle.css)
</style>

<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <a href="https://www.huodongxing.com/event/1484818871600"> <img src="https://file.mayiangel.com/20190326/648e5985-e624-444b-95ed-fd96df71eeef.png" alt=""></a>
        </div>
        <div class="swiper-slide">
            <a href="https://www.mayiangel.com/mayicollege/news/179.htm"> <img src="https://file.mayiangel.com/20180531/6782ad84-765d-45c2-838c-518dcdabf959.png" alt=""></a>
        </div>
        <div class="swiper-slide">
            <a href="http://www.mayiangel.com/mayicollege/entrepreneur.htm"> <img src="https://file.mayiangel.com/20170828/b227da03-20f2-4a42-a453-8f5de67d0c14.jpg" alt=""></a>
        </div>
        <div class="swiper-slide">
            <a href="https://www.mayiangel.com/mayicollege/news/173.htm"> <img src="https://file.mayiangel.com/20170828/09858a40-c181-4918-a94b-77c4a6f417b2.jpg" alt=""></a>
        </div>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-pagination"></div>
</div>

<div class="index-w2">
    <div class="wp">
        <ul class="w2-ul">
            <li><i>认证投资人 <span><em class="numberRun4"></em></span></i></li>
            <li><i>成功项目数 <span><em class="numberRun3"></em></span></i></li>
            <li><i>融资金额 <span><em class="numberRun"></em></span></i></li>
        </ul>
    </div>
</div>
<!--有分隔符，有小数点：<div class="numberRun"></div> <br><br>-->
<!--只有分隔符：<div class="numberRun2"></div> <br><br>-->
<!--只有小数点：<div class=""></div> <br><br>-->
<!--无分隔符，无小数点：<div class=""></div>-->

<div class="wp index-tBtn">
    <a href="create-project.php">创建项目</a>
    <a href="/member/normal/applyInvestor.htm">认证投资人</a>
</div>


<div class="wp" style="width: 1000px">
    <a href="/project/projectinfo/2343.htm">
        <div class="index-xm">
            <span class="ywc" ></span>
            <img src="https://file.mayiangel.com/20180516/fb4882f0-7169-4df1-badd-197432daa967.png" alt="">

            <div class="xmR">
                <h5>
                    <i>电允超级快充</i><em>上海市</em></h5>
                <h6>  让新零售配送人员用上充电快、价格便宜
                    ...</h6>
                <span>
                我们公司是专注于为新零售行业配送人员提供电池租赁和快速充电服务的科技企业。长久以来，电瓶车电池充电慢、寿命短，浪费了大量的宝贵时间和金钱，这深深困扰着近千万名配送从业人员。他们亟需一款充电快、价格便宜
                    ... 
                </span>

                <div class="progress">
                    <span style="width:100%;"
                          class="blue"><span>116.25%</span></span>
                </div>
                <div class="progressFont">
                    <span>80万</span>融资金额&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>8%</span>出让股权
                    <div class="fr"><span>11</span>人认购</div>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="index-jxxm">
    <div class="wp" style="width: 1104px">
        <h3 class="newH3">精选项目<span>Selection project</span></h3>
        <ul class="jx-ul">



        <?php if(!empty($top_projects)){ ?>
        	<?php foreach($top_projects as $data): ?>
            <li class="fr" >
            	<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
                <span
                    class="yrz">
                </span>
                <h5>
                    <i><?php echo $data->name; ?></i>
                    <?php echo substr($data->description, 0, 20)."..."; ?> 
                    <em>上海市</em>
                </h5>
            	</a>
                <div class="jx-img-div">
                    
                    <img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" alt="Image" />

                </div>
                <div class="progress">
                    <span style="width:  <?php echo $percentage = Investments::get_percentage($data->amount_invested,$data->investment_wanted) ?>%;"
                          class="blue"><span>$percentage%</span></span>
                </div>
                <div class="progressFont">
                    <span>
                    	<?php echo CURRENCYSYMBOL.$data->investment_wanted; ?>
                    </span>融资金额&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>10%</span>出让股权
                    <div class="fr"><span>1</span>人认购</div>
                </div>
            </li>
           <?php endforeach; ?>
		<?php } ?>
        </ul>
        <button onclick="javascript:window.location.href='/project/project.htm'" class="index-more">MORE >></button>
    </div>
</div>

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

<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/myMotion.js"></script>>

<!--</div>-->
<div class="bgf">
    <div class="wp">
        <div class="tit-a1 pt30">
            <!--<h3>全方位助力</h3>-->
        </div>

        <dl class="ul-link-box">
            <dt class="tit">知名创投机构：</dt>
            <dd>
                <ul class="ul-link">
                    <li><a target="_blank" href="http://www.newgenvc.com/#!home/c13if">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/ccdc14e96b014d69946ab8cbfa94aae6.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://www1.qingsongfund.com/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/004b02316d3049bf83fd3ce3a88d7b5c.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://www.newsionvc.com/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/2c730fa0c77548bfb5552cb943df9e92.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://www.ghvc.cn/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/245b24bac7c04a4f9c3e36ce406582c4.jpg" alt="">
                        </div>
                    </a></li> 
                </ul>
                <a href="/mayicollege/friendlyLink.htm" class="more">更多</a>
            </dd>
        </dl>
        <dl class="ul-link-box">
            <dt class="tit">优质孵化器：</dt>
            <dd>
                <ul class="ul-link">
                    <li><a target="_blank" href="http://">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/e0b247c7d55445aaac1fbb7f77ab647a.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://www.innospace.com.cn/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/093d37c89593453682258e121f2aabc2.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://www.innoclub.cn/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/6e0bde80b97940c58e44b1b4ba524d76.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://chinaccelerator.com/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/48f05672a70b4a81ad7374936e7c8bac.jpg" alt="">
                        </div>
                    </a></li> 

                </ul>
                <a href="/mayicollege/friendlyLink.htm" class="more">更多</a>
            </dd>
        </dl>
        <dl class="ul-link-box">
            <dt class="tit">业内媒体：</dt>
            <dd>
                <ul class="ul-link">
                    <li><a target="_blank" href="http://www.zczj.com">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20151113/9f7fd28750234e689b01f59fcd9f8584.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://itjuzi.com/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/0fd8eb26be384b128ec8e2aeaaf5d8da.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://cn.technode.com/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/b5b71385c92442239a7c515822e5cb60.jpg" alt="">
                        </div>
                    </a></li>                     <li><a target="_blank" href="http://www.tuoniao.fm/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20150719/b10988a20d7d4641b0cebb7cd616bb39.jpg" alt="">
                        </div>
                    </a></li> 
                </ul>
                <a href="/mayicollege/friendlyLink.htm" class="more">更多</a>
            </dd>
        </dl>
        <dl class="ul-link-box">
            <dt class="tit">专业法律支持：</dt>
            <dd>
                <ul class="ul-link">
                    <li><a target="_blank" href="http://www.hiwayslaw.com/">
                        <div class="pic">
                            <img src="https://file.mayiangel.com/imageADPath/20151030/5961da9ac668406d82d2c0c908be7b04.jpg" alt="">
                        </div>
                    </a></li> 
                </ul>
            </dd>
        </dl>
    </div>
    <div class="h30"></div>
</div>
<div class="go-top">
    <div class="wp"><a href=""></a></div>
</div>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>