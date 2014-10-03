    <title>Edit Topic Article</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
    <h3>Edit Topic Article "<?php echo $topic['title']; ?>" in "<?php echo $club['name']; ?>"</h3>
    <form action="<?php echo site_url('club/' . $cid . '/topic/' . $tid . '/edit');?>" method="post">
        <p><label for="title">Title:</label><input type="text" name="title" value="<?php echo $topic['title']; ?>"/></p>
        <p><label for="content">Content:</label><textarea name="content" id="" cols="30" rows="10"><?php echo $topic['content']; ?></textarea></p>
        <input type="submit" value="submit" />
    </form>