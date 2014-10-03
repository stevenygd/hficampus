<<<<<<< HEAD
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    <title>Create Page Article</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
<<<<<<< HEAD
=======
</head>

<body>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    <h3>Create Page Article in "<?php echo $club['name']; ?>"</h3>
    <form action="<?php echo site_url('club/'.$cid.'/page/create');?>" method="post">
        
        <?php foreach (array('title','content') as $item):?>
            <p><?php echo $item;?>:<input name="<?php echo $item;?>" /></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
<<<<<<< HEAD
    </form>
=======
    </form>
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
