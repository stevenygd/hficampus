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
    <div id="club"><?php echo $club['name']; ?></div>
    <h1 id="title"><?php echo $page['title']; ?></h1>
    <div id="meta"></div>
    <div id="content"><?php echo $page['content']; ?></div>
    <div id="comments">
        <ul>
        <?php foreach ($comments as $comment) : ?>
            <li class="comment"><?php echo $comment['content']; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div id="comment-post">
        <form action="<?php echo site_url('club/'.$cid.'/page/'.$pid.'/comment/create');?>" method="post">
            <h3>Comment on Club Blog</h3>
            <textarea name="content" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="submit" />
        </form>    
<<<<<<< HEAD
    </div>
=======
    </div>
    
    

</body>
</html>
>>>>>>> 030000420ad7bbf6d2ae738842e2f87ac09c37f9
