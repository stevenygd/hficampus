<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edit Topic Article</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
</head>

<body>
    <h3>Edit Topic Article "<?php echo $topic['title']; ?>" in "<?php echo $club['name']; ?>"</h3>
    <form action="<?php echo site_url('club/' . $cid . '/topic/' . $tid . '/edit');?>" method="post">
        <p><label for="title">Title:</label><input type="text" name="title" value="<?php echo $topic['title']; ?>"/></p>
        <p><label for="content">Content:</label><textarea name="content" id="" cols="30" rows="10"><?php echo $topic['content']; ?></textarea></p>
        <input type="submit" value="submit" />
    </form>
</body>
</html>
