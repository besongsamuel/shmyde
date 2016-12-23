<!DOCTYPE html>
<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
<span><a href="<?php echo site_url('admin/create/button'); ?>">Create New</a> </span>
</div>

<div class="container">
  <h2>SHMYDE BUTTONS</h2>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Button</th>
        <th>Name</th>
        <th>Links</th>
      </tr>
    </thead>
    <tbody>
    
    <?php foreach ($buttons as $button) {?>
    	
    	<tr>
            <td><img style="width : 100px; height: 100px;" src="<?php echo ASSETS_PATH."images/buttons/".$button->image_name; ?>" /></td>
            <td><?php echo $button->name; ?></td>
            <td>
                <a href="<?php echo site_url('admin/edit/button/'.$button->id); ?>">Edit</a> | 
                <a href="<?php echo site_url('admin/delete/button/'.$button->id); ?>">Delete</a>
            </td>
    	</tr>
    
    <?php }?>
    </tbody>
  </table>
</div>

</body>
</html>