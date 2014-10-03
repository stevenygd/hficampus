    <link rel="stylesheet" type="text/css" href="/css/course/list.css" />
    <title>Question</title>
    <style>
		/*For question only*/
		#add_question{
			position: fixed;
			top: 19px;
			right: 109px;
			z-index: 100;
			width: 123px;
			height: 32px;
		}
	</style>
    <script src="/js/jquery.js" type="application/javascript" ></script>
    <script>
		var site_url = "<?php echo site_url();?>";
		var cid      = "<?php echo $cid;?>";
		$(document).ready(function(){
			
			$("#add_question").click(function(){
				url=site_url+'/course/'+Number(cid)+'/question/0/editor';
				window.location.href = url;
			});
			
		});
	</script>
    <h1>Questions</h1>   
    <button id="add_question">Ask a Question!</button> 
    <?php if (is_array($questions) && (count($questions)>0)):?>
		<?php foreach($questions as $i => $item):?>
            <div class="question">
                <a class="question_box" href="<?php echo site_url('course/'.$cid.'/question/'.$item['id']);?>">
                    <p class="title"><?php echo $item['title'];?></p>
                    <p class="ctime inlineb">created at:<?php echo $item['created_time'];?></p>
                    <div class="clear"></div>
                </a>
            </div>
        <?php endforeach;?>
    <?php else:?>
    	<p>No question was posted yet.</p>
    <?php endif;?>