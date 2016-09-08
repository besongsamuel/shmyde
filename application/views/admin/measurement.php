<!DOCTYPE html>

<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
<span><a href="<?php echo site_url('admin/create/measurement'); ?>">Create New</a> </span>
</div>

<div class="container">
  <h2>SHMYDE MEASUREMENTS</h2>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Name</th>
        <th>Product Name</th> 
        <th>Default Value</th> 
        <th>Description</th>
        <th>YouTube Link</th>
        <th>Links</th>
      </tr>
    </thead>
    <tbody>
    
    <?php foreach ($measurements->result() as $row) {?>
    	
    	<tr>
            <td><?php echo $row->name; ?></td>
            <td><?php echo $row->product_name; ?></td>
            <td><?php echo $row->default_value; ?></td>
            <td><?php echo $row->description; ?></td>
            <td><iframe width="180" height="140" src="<?php echo str_replace("watch?v=", "embed/", $row->youtube_video_link);  ?>"></iframe></td>
            <td>
                <a href="<?php echo site_url('admin/edit/measurement/'.$row->id); ?>">Edit</a> | 
                <a href="<?php echo site_url('admin/delete/measurement/'.$row->id); ?>">Delete</a>
            </td>
    	</tr>
    
    <?php }?>
    </tbody>
  </table>
</div>

</body>
</html>