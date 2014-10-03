    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>aca test</title>
	<link rel="stylesheet" type="text/css" href="/css/course/index.css"  />
	<script type="text/javascript" src="/js/jquery.js"></script>
    <script>
		var type     = "<?php echo $type;?>";
		var subtype  = "<?php echo $type;?>";
		var teacher  = "<?php echo $type;?>";
		var year     = "<?php echo $type;?>";
		var site_url = "<?php echo site_url();?>";
    </script>
	<script type="text/javascript" src="/js/course/index.js"></script>
    <h3><span>HFI</span> Campus Express</h3>
    <h1>Courses</h1>
    <div class="box" id="my">
    	<p class="label">My</p>
        <p class="label">Courses</p>
        <p class="count"><?php echo $mycount;?></p>
        <!--$mycount: number of my courses-->
    	<div class="triangle"></div>        
    </div>
    <div class="list">
    	<div class="triangle2"></div>
        <?php if (is_array($my_course) && (count($my_course)>0)):?>
			<?php foreach($my_course as $item):?>
                <?php if(is_array($item)):?>
                	<div class="block">
                        <a href="<?php echo site_url('course/'.$item['id']);?>">
                            <p class="name"><?php echo $item['name'];?></p>
                            <p class="author">by <?php echo $item['cnfn'].' '.$item['cnln'];?></p>
                            <!--$fn & $ln: first name and last name of the author-->
                        </a>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        <?php else:?>
        	<p>None</p>
        <?php endif;?>
    </div>
    <div class="box" id="other">
    	<p class="label">Other</p>
        <p class="label">Courses</p>
        <p class="count"><?php echo $count;?></p>
        <!--$count: number of all courses-->
        <div class="triangle"></div>        
    </div>    
    <div class="list">
        <div class="triangle2"></div>
        <!--div>
            <input type="text" name="search" id="search" value="" />
            <input type="button" id="cancel_search" value="Cancel" />
        </div>
        <div id="sort">
            <ul id="type">
                <li class="tag type <?php if($type==0) echo 'tag_clicked';?>" >All</li>
                <?php foreach($types as $item):?>
                    <li class="tag type <?php if($subtypes==$item['id']) echo 'tag_clicked';?>" >
                    	<input type="hidden" name="types" value="<?php echo $item['id'];?>" />
                        <?php echo $item['type_name'];?>
                    </li>
                <?php endforeach;?>
            </ul>
            <ul id="subtype">
                <li class="tag subtype <?php if($subtype==0) echo 'tag_clicked';?>" >All</li>
                <?php foreach($subtypes as $item):?>
                    <li class="tag subtype <?php if($subtypes==$item['id']) echo 'tag_clicked';?>" >
                    	<input type="hidden" name="subtype" value="<?php echo $item['id']?>">
                        <?php echo $item['subtype_name'];?>
                    </li>
                <?php endforeach;?>
            </ul>
            <ul id="teacher">
                <li class="tag teacher <?php if($teacher==0) echo 'tag_clicked';?>" >All</li>
                <?php foreach($teachers as $item):?>
                    <li class="tag teacher <?php if($teacher==$item['uid']) echo 'tag_clicked';?>" >
                    	<input type="hidden" name="teacher" value="<?php echo $item['uid']?>" />
                        <?php echo $item['cnfn'].' '.$item['cnln'];?>
                    </li>
                <?php endforeach;?>
            </ul>
            <ul id="years">
            	<li class="tag year <?php if($year==0) echo 'tag_clicked';?>">All</li>
                <?php for($i=-2;$i<3;$i++):?>
                    <?php $out_year=date('Y')+$i;?>
                    <li class="tag year <?php if($out_year==$year) echo 'tag_clicked';?>">
                    	<input type="hidden" name="year" value="<?php echo $out_year?>"/>
                        <?php echo $out_year?>
                    </li>
                <?php endfor;?>
            </ul>
            <input type="button" id="sort_submit" value="Submit" />
            <p class="status">Processing...</p>
		</div-->
		
        <div id="all_courses_list">
			<?php if ((is_array($course_list))&&(count($course_list)>0)):?>
                <?php foreach($course_list as $item):?>
                    <div class="block">
                        <a href="<?php echo site_url('course/'.$item['id']);?>">
                            <p class="name"><?php echo $item['name'];?></p>
                            <p class="author">by <?php echo $item['cnfn'].' '.$item['cnln'];?></p>
                            <!--$fn & $ln: first name and last name of the author-->
                        </a>
                    </div>
                <?php endforeach;?>
            <?php else:?>
                <p>No other courses</p>
            <?php endif;?>
        </div>
    </div>
    <?php if ($role == "teacher"):?>
    <!--$role: role of the user-->
        <div class="triangle2"></div>
        <div class="create tea">
            <img src="/images/course/create.png" />
            <p>Create new course</p>
        </div>
        <div class="list new">
        	<form action="<?php echo site_url('course/create');?>" method="post">
            	<p>Name</p>
                <input type="text" name="cname" class="cname" value=""  />
                <p>Year</p>
                <select name="y">
                	<option value="<?php echo date('Y', time());?>"><?php echo date('Y', time());?></option>
                    <option value="<?php echo date('Y', strtotime('+1 year'));?>"><?php echo date('Y', strtotime('+1 year'));?></option>
                </select>
                <p>Type</p>
                <select name="type">
                	<?php foreach($subtypes as $item):?>
                    	<option value="<?php echo $item['id'];?>"><?php echo $item['subtype_name'];?></option>
                    <?php endforeach;?>
                </select>
                <p>Description</p>
                <textarea name="description"></textarea>
                <input type="hidden" name="uid" value="<?php echo $uid;?>"  />
                <input type="submit" name="submit" class="submit" value="Create" />
            </form>
        </div>
    <?php else:?>
    	<div class="triangle2"></div>
    	<div class="create stu">
            <img src="/images/course/create.png" />
            <p>Join a course</p>
        </div>
        <div class="list">
        	<?php if ((is_array($course_list))&&(count($course_list)>0)):?>
                <?php foreach($course_list as $item):?>
                	<div class="block">
                    	<div class="app">
                    		<p class="name"><?php echo $item['name'];?></p>
                        	<p class="author"><?php echo $item['cnfn'].' '.$item['cnln'];?></p>
                		</div>
                    	<div class="apply">
                            <p>Send a message to inform the teacher.</p>
                            <p>Please to not apply for courses you didn't register.</p>
                            <form action="<?php echo site_url('course/'.$item['id'].'/create');?>" method="post">
                                <input type="hidden" name="gid" value="<?php echo $item['gid'];?>" />
                                <input type="hidden" name="uid" value="<?php echo $uid;?>"  />
                                <input type="hidden" nanme="execution" value="apply" />
                                <textarea name="description"></textarea>
                                <input type="submit" value="Submit"  />
                            </form>
                        </div>
        			</div>
               <?php endforeach;?>
            <?php endif;?>
    	</div>
    <?php endif;?>