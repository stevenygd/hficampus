    <title>CLUB TEST</title>
    <style>
        form {
            border:solid 1px;
        }
    </style>
    <h1>Edit Club: <?php echo $data['name']; ?></h1>
    <form action="<?php echo site_url('club/' . $data['id'] . '/edit');?>" method="post">
        <?php foreach (array('name','description') as $item):?>
            <p><?php echo $item;?>:<input name="<?php echo $item;?>" value="<?php echo $data[$item]; ?>"/></p>
        <?php endforeach;?>
        <input type="submit" value="submit" />
    </form>