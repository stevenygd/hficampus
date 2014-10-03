<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
<?php if ($info==FALSE):?>
	<form action="<?php echo site_url('course/'.$cid.'/question/create');?>" method="post">
		<input type="hidden" name="cid" value="<?php echo $cid;?>"  />
		<p>Title</p>
		<input type="text" name="title"  />
		<p>Text</p>
		<textarea class="ckeditor" name="text"></textarea>
		<input type="submit" value="submit"  />
	</form>
<?php else:?>
	<form action="<?php echo site_url('course/'.$cid.'/question/'.$info['id'].'/edit');?>" method="post">
		<input type="hidden" name="cid" value="<?php echo $cid;?>"  />
		<p>Title</p>
		<input type="text" name="title" value="<?php echo $info['title']?>" />
		<p>Text</p>
		<textarea class="ckeditor" name="text"><?php echo $info['text']?></textarea>
		<input type="submit" value="submit"  />
	</form>
	<a href="<?php echo site_url('course/'.$cid.'/page/'.$info['id'].'/delete');?>">DELETE</a>
<?php endif;?>