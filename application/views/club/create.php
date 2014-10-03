<<<<<<< HEAD
=======
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
    <title>CLUB TEST</title>
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
    <h1>Create Club</h1>
    <form action="<?php echo site_url('club/create');?>" method="post">
        <?php foreach (array('name','description') as $item):?>
            <p><?php echo $item;?>:<input name="<?php echo $item;?>"/></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
<<<<<<< HEAD
=======
</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
