<!DOCTYPE html>
<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
<span><a href="<?php echo site_url('admin/create/thread'); ?>">Create New</a> </span>
</div>

<div class="container">
  <h2>SHMYDE THREADS</h2>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Thread</th>
        <th>Color</th>
        <th>Links</th>
      </tr>
    </thead>
    <tbody>
    
    <?php foreach ($threads as $thread) {?>
    	
    	<tr>
            <td><img style="width : 100px; height: 100px;" src="<?php echo ASSETS_PATH."images/threads/".$thread->image_name; ?>" /></td>
            <td><div style="width : 100px; height: 100px; background-color: <?php echo $thread->color; ?>"></div></td>
            <td>
                <a href="<?php echo site_url('admin/edit/thread/'.$thread->id); ?>">Edit</a> | 
                <a href="<?php echo site_url('admin/delete/thread/'.$thread->id); ?>">Delete</a>
            </td>
    	</tr>
    
    <?php }?>
    </tbody>
  </table>
</div>

</body>
</html>