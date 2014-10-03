    <title>CLUB TEST</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
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
    </div>