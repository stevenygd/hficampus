    <title>CLUB TEST</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
    <h1>Create Club</h1>
    <form action="<?php echo site_url('club/create');?>" method="post">
        <?php foreach (array('name','description') as $item):?>
            <p><?php echo $item;?>:<input name="<?php echo $item;?>"/></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>
