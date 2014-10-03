<form action="<?php echo site_url('account/add_group');?>" method="post">
    <h2>Add Group</h2>
    <input type="text" name="gname" id="gname" />
    <input type="password" name="pword" id="pword"  />
    <input type="text" name="email" id="email"  />
    <input type="submit" name="submit" id="submit"  />
</form>
<form action="<?php echo site_url('account/admin/adduser');?>" method="post">
    <h2>Add Special User</h2>
    <input type="text" name="uname" id="gname" />
    <input type="password" name="pw" id="pword"  />
    <input type="text" name="email" id="email"  />
    <select name="type">
        <option selected="selected" value="">Please selecet what type of user</option>
        <option value="developer">Developer</option>
        <option value="aca">Academic Teacher</option>
        <option value="office">Officer</option>
    </select>
    <input type="submit" name="submit" id="submit"  />
</form>
