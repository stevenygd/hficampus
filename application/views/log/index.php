<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Log</title>
</head>

<body>
<h6>Add log</h6>
<form action="<?=site_url('/log/add_log')?>" method="POST">
    <p style="display:inline">TITILE:<input name="title" /></p>
    <p style="display:inline">CONTENT:<textarea name="content"></textarea></p>
    <input type="submit" value="submit" />
</form>
<?php if (isset($err)) echo $err;?>
<div>
    <table>
        <tr>
            <td>User ID</td>
            <td>Title</td>
            <td>Content</td>
            <td>Time</td>
            <td>Comment</td>
        </tr>
    <?php foreach ($list as $i=>$item):?>
        <tr>
            <div class="log_box">
                <td style="border:solid 1px;"><?=$ulist[$item['uid']]['enn'];?></td>
                <td style="border:solid 1px;"><?=$item['title'];?></td>
                <td style="border:solid 1px;"><?=$item['content'];?></td>
                <td><?=$item['time'];?></td>
                <td>
                <ul>
                <?php foreach (array_reverse($item['comment']) as $j=>$jtem):?>
                	<?php if(is_numeric($j)):?>
                    <li style="border:solid 1px;">
						<?=$ulist[$jtem['uid']]['enn'].'comment:<br>'.$jtem['content']?></br><span><?=$jtem['time']?></span>
                    </li>
                    <?php else:?>
                    <li>
                    	Total comments:<?=$jtem;?>
                    </li>
                    <?php endif;?>
                <?php endforeach;?>
                </ul>
                <form style="border:solid 1px;" action="<?=site_url('/log/add_comment')?>" method="POST">
                    <p>Comment:<input name="content" /></p>
                    <input type="hidden" name="lid" value="<?=$item['id']?>" />
                    <input type="submit" value="submit" />
                </form>
                </td>
            </div>
        </tr>
    <?php endforeach;?>
    </table>

</div>
</body>
</html>
