    <title>CLUB TEST</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
    <?php 
	/*
        if ($this->input->get('cid'))
            $cid=$this->input->get('cid');
        else
            $cid=1;
        if ($this->input->get('bid'))
            $bid=$this->input->get('bid');
        else
            $bid=1;
	*/
    ?>
    <div id="club"><?php echo $club['name']; ?></div>
    <h1 id="title"><?php echo $blog['title']; ?></h1>
    <div id="meta"></div>
    <div id="content"><?php echo $blog['content']; ?></div>
    <div id="comments">
        <ul>
        <?php foreach ($comments as $comment) : ?>
            <li class="comment"><?php echo $comment['content']; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div id="comment-post">
        <form action="<?php echo site_url('club/'.$cid.'/blog/'.$bid.'/comment/create');?>" method="post">
            <h3>Comment on Club Blog</h3>
            <textarea name="content" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="submit" />
        </form>    
    </div>