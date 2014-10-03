<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CLUB TEST</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
</head>

<body>
    <h1>Edit Club: <?php echo $data['name']; ?></h1>
    <form action="<?php echo site_url('club/' . $data['id'] . '/edit');?>" method="post">
        <?php foreach (array('name','description') as $item):?>
            <p><?php echo $item;?>:<input name="<?php echo $item;?>" value="<?php echo $data[$item]; ?>"/></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
</body>
</html>
