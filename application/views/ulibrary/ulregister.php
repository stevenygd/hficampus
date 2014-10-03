<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ULibrary Sign Up!</title>
    <link rel="stylesheet" href="/css/ulibrary/ulregister.css" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url ('js/jquery.js')?>"></script>
    <script>
		var site_url="<?php echo site_url()?>";
		var base_url="<?php echo base_url()?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url ('js/ulibrary/ulregister.js')?>"></script>
</head>

<body>
	<h1 class="header" id="ulibrary" >ULibrary    <span id="title">Sign up for Designing 206!<span>
        <button type="button"  id="register">REGISTER</button>
    </h1>
    <div id="description" class="main">
    	<div class="slide_inside">
    		<pre class="intro">
<strong>比赛时间</strong>：即日起至2月28日
<strong>截稿日期</strong>：2月14日
<strong>活动内容</strong>：在满足图书馆功能需求的前提下，发挥想象力，改变图书室现有布局，
         设计有主题特色和创意，并且可以有效实施的图书馆翻新布置。
<strong>参赛形式</strong>：可以以个人和团队名义参加

<strong>作品要求</strong>：

1.	以平面图形式提交,黑白、彩色皆可（附带效果图、模型更好）
	电子版上传至hficampus网站; 手绘版交至活动方

2.	布置中必须含有以下陈设：
	书架（后附对书架的详细要求）
	6张大桌子（大小同图书室现有的一致）
	24张椅子（大小同图书室现有的一致）
    备注：（桌子、椅子数量只能多不能少，以保证有一定的自习空间）
	      书籍分类标示 （Dewey decimal system）

3.	其他可用设施（非必需）：
	4个杂志架
	2张大桌子
	8张椅子
	11个带锁铁轨
	（以上设施规格参照图书馆现有设施）
    
    书架要求：
    为减少对设计创意和作品的限制，参赛者可自行挑选使用所需的书柜，
    U Library将提供1200元预算。为满足图书馆功能需求，所用书柜应
    能容纳3000本书籍，且能较好地分区成九类书籍存放。为方便后期实
    际翻新操作，所用书柜方案应切实可行。
       </pre >
       <pre class="intro">
    书籍分类：
    000  总论
    100　哲学
    200　宗教
    300　社会科学
    400　语言
    500　自然科学和数学
    600　技术（应用科学）
    700　艺术、美术和装饰艺术
    800　文学
    900　地理、历史及辅助学科

4.  所有变动和装饰不得改变现有的且不可移动的设施，
    如：照明系统、电器、地面、墙壁、天花板、电路系统……
    现图书馆地面为浅绿色油漆，考虑到施工的难易度，参赛者不得改变地面颜色

5.  作品要切实可行

<strong>评比规则：</strong>
1.	活动方先初步筛选出符合上述要求的作品
2.	第二轮评比分为学生普选和校方评价
3.  评出冠军

<em>冠军作品将由冠军团队实施完成，U Library协助实施</em>

<strong>奖励方案：</strong> 
冠军（一名）：价值500元奖品

<em>策划方/活动实施方：U Library社团</em>
<em>联系方式 Rowena hfi_books@126.com 18928848217</em>
        	</pre>
        </div>
    </div>
	<div id="register_container" class="">
        <form class="enform" id="enform" action="<?php echo site_url('ulibrary/signup');?>" method="post">
				<?php if (isset($error)):?>
                	<div id="error"><p><?php echo $error;?></p></div>
				<?php else:?>
                	<div id="error"></div>
                <?php endif;?>
            <div class="input_unit">
            	<p>Responsible Person</p>
                <div class="sid">
                    <label for="sid">Student ID</label>
                    <input class="sidinput inputbox" type="text" name="sid" />
                </div>
                <div class="ulemail" >
                    <label for="ulemail">Email</label>
                    <input type="email" name="ulemail" id ="ulemail" class="inputbox" />
                </div>
                <div class="sidcheck"></div>
            </div>
            <div id="more">
                <button type="button" id="show">Add Member</button>
            </div>
            <input type="submit" id="submit" value="JOIN!" />
            <button type="button" id="close">Back</button>
            <div id="submit_check"></div>
        </form>
    </div>
</body>
</html>