<!DOCTYPE html>
<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
    <span><a href="<?php echo site_url('admin/view/measurement'); ?>">View All</a> </span>
</div>

<div class="container">
  <h2><?php echo $title; ?> MENU</h2>

  <form action="<?php if($title == 'CREATE') echo site_url('admin/create/measurement'); else echo site_url('admin/edit/measurement/'.$measurement->id);  ?>" role="form" method="post" enctype="multipart/form-data">
      
    <div class="form-group">
        <label for="menu">Product :</label>
        <select class="form-control" id="product" name="product">
            <?php foreach ($products->result() as $product) {?>
            <option value="<?php echo $product->id; ?>"><?php echo $product->name; ?></option>
            <?php } ?>
        </select>
    </div>
        
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php  if(isset($measurement)) { echo $measurement->name; } ?>">
    </div>
      
    <div class="form-group">
      <label for="default_value">Default Value :</label>
      <input type="number" class="form-control" id="default_value" name="default_value" value="<?php  if(isset($measurement)) { echo $measurement->default_value; } ?>">
    </div> 
      
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" rows="5" id="description" name="description"><?php  if(isset($measurement)) { echo $measurement->description; } ?></textarea>
    </div>  
      
    <div class="form-group">
      <label for="youtube_video">YouTube Video Link :</label>
      <input type="text" class="form-control" id="youtube_video" name="youtube_video" value="<?php  if(isset($measurement)) { echo $measurement->youtube_video_link; } ?>">
    </div>  
      
    <button type="submit" class="btn btn-primary btn-block"><?php echo $title; ?></button>
    
  </form>
</div>

</body>

<script type="text/javascript">
        
        
    $(document).ready(function(){
        
                
        var is_edit = Boolean(<?php echo $is_edit; ?>);
        
        if(is_edit){
                        
            $('#product').val(<?php if(isset($measurement)) { echo $measurement->shmyde_product_id; }  ?>);
                                                                     
        }
                
    });
    
    
</script>

</html>