<!DOCTYPE html>
<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
<span><a href="<?php echo site_url('admin/create/menu'); ?>">Create New</a> </span>
</div>

<div class="container">
  <h2>SHMYDE MENUS</h2>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Menu Name</th>
        <th>Product Name</th> 
        <th>Mixed Fabric Support</th> 
        <th>Inner Contrast Support</th>
        <th>Is Back Option Menu</th>
        <th>Links</th>
      </tr>
    </thead>
    <tbody>
    
    <?php foreach ($menus->result() as $row) {?>
    	
    	<tr>
            <td><?php echo $row->name; ?></td>
            <td><?php echo $row->product_name; ?></td>
            <td><?php echo $row->mixed_fabric_support == 1 ? "Yes" : "No"; ?></td>
            <td><?php echo $row->inner_contrast_support == 1 ? "Yes" : "No"; ?></td>
            <td><?php echo $row->is_back_menu == 1 ? "Yes" : "No"; ?></td>
            <td>
                <a href="<?php echo site_url('admin/edit/menu/'.$row->id); ?>">Edit</a> | 
                <a href="<?php echo site_url('admin/delete/menu/'.$row->id); ?>">Delete</a>
            </td>
    	</tr>
    
    <?php }?>
    </tbody>
  </table>
</div>

</body>
</html>