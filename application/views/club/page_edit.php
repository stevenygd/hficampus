    <title>Edit Page Article</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
    <h3>Edit Blog Article "<?php echo $page['title']; ?>" in "<?php echo $club['name']; ?>"</h3>
    <form action="<?php echo site_url('club/' . $cid . '/page/' . $pid . '/edit');?>" method="post">
        <p><label for="title">Title:</label><input type="text" name="title" value="<?php echo $page['title']; ?>"/></p>
        <p><label for="content">Content:</label><textarea name="content" id="" cols="30" rows="10"><?php echo $page['content']; ?></textarea></p>
        <input type="submit" value="submit" />
    </form>