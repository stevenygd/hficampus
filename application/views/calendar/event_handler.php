<form style="border:solid 1px;"action="<?php echo site_url('calendar/'.$gid.'/event/create');?>" method="post">
    <h1>Add event test</h1>
    <p>GID:<?php echo $gid?></p>
    <p>Name</p>
    <input name="name" />
    <p>description</p>
    <textarea name="description"></textarea>
    <p>start</p>
    <input name="start" value="<?php echo date('Y-m-d',time())?>"/>
    <p>end</p>
    <input name="end" value="<?php echo date('Y-m-d',time()+3600*24)?>" />
    <p>type</p>
    <input name="type" />
    <input type="submit" value="submit"/>
</form>
