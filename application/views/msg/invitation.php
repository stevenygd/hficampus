<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Invitations</title>
</head>

<body>
	<?php if (is_array($invitation) && (count($invitation)>0)):?>
            <?php foreach($invitation as $i=>$item):?>
                <div>
                    <p><?php echo $item['cnfn'].' '.$item['cnln'].'('.$item['enn'].')';?></p>
                    <p>wants to be your friend:</p>
                    <p><?php echo $item['description'];?></p>
                    <div>
                        <form action="<?php echo site_url('message/accept/edit');?>" method="POST">
                            <input type="hidden" name="oid" value="<?php echo $item['send'];?>" />
                            <input type="submit" value="Accept" />
                        </form>
                    </div>
                </div>
            <?php endforeach;?>
        <?php else:?>
    <?php endif;?>
</body>
</html>