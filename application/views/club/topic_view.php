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
    <div id="club"><?php echo $club['name']; ?></div>
    <h1 id="title"><?php echo $topic['title']; ?></h1>
    <div id="meta"></div>
    <div id="content"><?php echo $topic['content']; ?></div>
    <div id="comments">
        <ul>
        <?php foreach ($comments as $comment) : ?>
            <li class="comment"><?php echo $comment['content']; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div id="comment-post">
        <form action="<?php echo site_url('club/'.$cid.'/topic/'.$tid.'/comment/create');?>" method="post">
            <h3>Comment on Club Topic</h3>
            <textarea name="content" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="submit" />
        </form>    
    </div>
    
    

</body>
</html>
