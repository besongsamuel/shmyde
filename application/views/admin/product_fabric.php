<!DOCTYPE html>
<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
<span><a href="<?php echo site_url('admin/create/product_fabric'); ?>">Create New</a> </span>
</div>

<div class="container">
  <h2>SHMYDE PRODUCT FABRICS</h2>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Affected Sub Menus</th>
	<th>Image</th>
        <th>Links</th>
      </tr>
    </thead>
    <tbody>
    <?php $fabric_id = -1; $name = ""; $fabric_image_name = ""; ?>
        
    <?php foreach ($product_fabrics as $fabric) { $name = "";?>
    	
        <?php foreach ($fabric as $value) {
            
            $fabric_id = $value->fabric_id;
            
            $fabric_image_name = $value->fabric_image_name;
            
            if(!empty($value->product_name) && isset($value->product_name))
            {
                $name .= $value->product_name.'->'.$value->mainmenu_name.'<br />';          
            }

        }?>
                
        <tr>
            <td><?php echo $name; ?></td>

            <td><img style="width: 100px; height : 100px" src="<?php echo ASSETS_PATH."images/product/fabric/".$fabric_image_name; ?>"></img></td>

            <td>
                <a href="<?php echo site_url('admin/edit/product_fabric/'.$fabric_id); ?>">Edit</a> | 
                <a href="<?php echo site_url('admin/delete/product_fabric/'.$fabric_id); ?>">Delete</a>
            </td>
    	</tr>
        
    <?php }?>
           
    </tbody>
  </table>
</div>

</body>
</html>