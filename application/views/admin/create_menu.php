<!DOCTYPE html>
<html lang="en">

<body>

<div style="margin-left:5%; margin-top:5%;">
    <span><a href="<?php echo site_url('admin/view/menu'); ?>">View All</a> </span>
</div>

<div class="container">
  <h2><?php echo $title; ?> MENU</h2>

  <form action="<?php if($title == 'CREATE') echo site_url('admin/create/menu'); else echo site_url('admin/edit/menu/'.$menu->id);  ?>" role="form" method="post" enctype="multipart/form-data">
      
    <div class="form-group">
        <label for="menu">Product :</label>
        <select class="form-control" id="product" name="product">
            <?php foreach ($products->result() as $product) {?>
            <option value="<?php echo $product->id; ?>"><?php echo $product->name; ?></option>
            <?php } ?>
        </select>
    </div>
      
    <div class="form-group">
        <label for="category">Menu Category :</label>
        <select class="form-control" id="category" name="category">
            <?php foreach ($categories->result() as $category) {?>
            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
            <?php } ?>
        </select>
    </div>
    
    <div class="checkbox" id='is_independent_div'>
        <label><input type="checkbox" value="1" name="is_independent" id="is_independent">Independent Menu</label>
    </div>   
      
    <div class="checkbox" id='is_back_menu_div'>
        <label><input type="checkbox" value="1" name="is_back_menu" id="is_back_menu">Back Option Menu</label>
    </div>  
      
    <div class="checkbox" id='mixed_fabric_support_div'>
        <label><input type="checkbox" value="1" name="mixed_fabric_support" id="mixed_fabric_support">Mixed Fabric Support</label>
    </div>
      
      <div class="checkbox" id="inner_contrast_support_div" hidden="true">
        <label><input type="checkbox" value="1" name="inner_contrast_support" id="inner_contrast_support">Inner Fabric Contrast Support</label>
    </div>  
      
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php  if(isset($menu)) { echo $menu->name; } ?>">
    </div>
      
    <button type="submit" class="btn btn-primary btn-block"><?php echo $title; ?></button>
    
  </form>
</div>

</body>

<script type="text/javascript">
        
    
    function manage_checkbox_element(element, container, enable, check = false){
        
        
        if(!enable){
            
            element.prop('value', 0);
            
            element.prop('checked', false);
            
            container.hide();

            
        }
        else{
            
            container.show();
            
            element.prop('value', 1);
            
            if(check){
                            
                element.prop('checked', true);
                
            }
            else{
                
                element.prop('checked', false);
                
            }
            
            
        }
    }
    

        
    $(document).ready(function(){
        
                
        var is_edit = Boolean(<?php echo $is_edit; ?>);
        
        manage_checkbox_element($('#mixed_fabric_support'), $('#mixed_fabric_support_div'), false);
        
        manage_checkbox_element($('#inner_contrast_support'), $('#inner_contrast_support_div'), false);
        
        if(is_edit){
                
            
            
            $('#product').val(<?php if(isset($menu)) { echo $menu->shmyde_product_id; }  ?>);
                        
            $('#category').val(<?php if(isset($menu_category)) {echo $menu_category->id; }  ?>);
                        
            $('#is_back_menu').prop('checked', Boolean(<?php if(isset($menu)) { echo $menu->is_back_menu; }  ?>)); 
            
            $('#is_independent').prop('checked', Boolean(<?php if(isset($menu)) { echo $menu->is_independent; }  ?>)); 
            
            if(parseInt($('#category').val()) === 2){
                
                
                manage_checkbox_element($('#mixed_fabric_support'), $('#mixed_fabric_support_div'), true, Boolean(<?php if(isset($menu)) { echo $menu->mixed_fabric_support; }  ?>));
        
                                                
            }
            
            if($('#mixed_fabric_support').prop('checked')){
                
                              
                manage_checkbox_element(
                        $('#inner_contrast_support'), 
                        $('#inner_contrast_support_div'), 
                        true, 
                        Boolean(<?php if(isset($menu)) { echo $menu->inner_contrast_support; }  ?>));
                                
            }
            
        }
        
        
        
        $('#mixed_fabric_support').change(function() {
            
            if($(this).is(":checked")) {
                
                
                manage_checkbox_element(
                        $('#inner_contrast_support'), 
                        $('#inner_contrast_support_div'), 
                        true, 
                        false);
            }
            else{
                
                
                manage_checkbox_element($('#inner_contrast_support'), $('#inner_contrast_support_div'), false);
                
                
            }
        });
        
        $('#category').change(function() {
            
            
            
            if(parseInt($(this).val()) === 2) {
                                
                manage_checkbox_element($('#mixed_fabric_support'), $('#mixed_fabric_support_div'), true);
                        
            }
            else{
                                 
                manage_checkbox_element($('#mixed_fabric_support'), $('#mixed_fabric_support_div'), false);
        
                manage_checkbox_element($('#inner_contrast_support'), $('#inner_contrast_support_div'), false);
                
               
            }
        });
                
    });
    
    
</script>

</html>