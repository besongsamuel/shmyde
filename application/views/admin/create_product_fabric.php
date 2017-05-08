<!DOCTYPE html>
<html lang="en">
    
    <?php 
            
        function checkProductHasFabric($product_submenu_fabrics, $product_id, $submenu_id, $fabric_id){
            
            foreach ($product_submenu_fabrics->result() as $product_submenu_fabric) {

                if($product_submenu_fabric->shmyde_product_id == $product_id 
                        && $product_submenu_fabric->shmyde_fabric_id == $fabric_id
                        && $product_submenu_fabric->shmyde_submenu_id == $submenu_id){
                    
                    return true;
                        }
            }
            
            return false;
        }
    ?>
    <head>
        
        <script src="<?php echo ASSETS_PATH; ?>js/my_file_upload.js"></script>
                        
        <script type="text/javascript">
            
        var index = 0;   
        
        var is_edit = Boolean(<?php echo $is_edit; ?>);
                                        
        $(document).ready(function() {
                                  
            var fabric_image_uploader = new My_Uploader({
            
                item_id: "<?= $fabric_id ?>",
                delete_link: "<?= site_url('admin/delete_image') ?>",
                table_name: "shmyde_fabric_images",
                image_dir : "product/fabric/",
                root : $( "#fabric_image" ),
                form : $( "#fabric_image_upload_form" ),
                mode : "single",
                image_name : "product_fabric_image",
                file_name : "file"
            });
            

            
            $( "#add_fabric_image" ).click(function() {
                
                var undefined;
                fabric_image_uploader.add_upload_button(undefined);

            });
            
            if(is_edit)
            {
                var fabric = JSON.parse('<?php echo $product_fabric; ?>');
                
                var id = "#".concat(fabric_image_uploader.image_name).concat("_").concat(fabric.id);
                                        
                fabric_image_uploader.add_upload_button(fabric.id, fabric.pos_x, fabric.pos_y, fabric.is_inner, fabric.depth, fabric.is_back_image);

                var base_path = "<?php echo ASSETS_PATH;  ?>".concat("images/product/fabric/");

                var image_path = base_path.concat(fabric.name);

                $(id).attr("src", image_path);
                
            }
                                                                                       
            var main_form = $("#create_fabric");
            
            main_form.submit(function (ev) {
               
                console.log("Submitting Main Form... ");
                console.log("Uploading Fabric... ");
                submit_upload_form(fabric_image_uploader, 0, 0, 0, 0);
                              
            });
            
                        
        });

        </script>
    </head>

    <body>

        <div style="margin-left:5%; margin-top:5%;">
            <span><a href="<?php echo site_url('admin/view/product_fabric'); ?>">View All</a> </span>
        </div>
        
        <h2><?php echo $title; ?> PRODUCT FABRIC</h2>
        
        

        <div class="container">
            
            <div class="container" >
                
                <button type="button" id="add_fabric_image" class="btn btn-primary" style="">Add Fabric Image</button>

                <form action="<?php echo site_url('admin/upload_image/'.$fabric_id);  ?>" role="form" method="post" enctype="multipart/form-data" style="margin-top: 20px; margin-bottom: 20px;" id="fabric_image_upload_form">

                    <span id="fabric_image" name="fabric_image" class="row">

                    </span>

                    <input type="text" id="parameters" name="parameters" hidden="true" />
                    

                </form>
                
            </div>
            
            <form action="<?php if($title == 'CREATE') echo site_url('admin/create/product_fabric'); else echo site_url('admin/edit/product_fabric/'.$fabric_id);  ?>" role="form" method="post" enctype="multipart/form-data" id="create_fabric">

                <div class="form-group" id="apply_to" class="row">
                    
                    <?php foreach ($products->result() as $product) { ?>
                        
                    <h1><?php echo $product->name; ?></h1>
                    
                    <label><input type="checkbox" value="<?php echo $product->id; ?>" name="product[<?php echo $product->id; ?>][default]" <?php if($product->default_fabric_id == $fabric_id) { echo 'checked'; } ?>>  Is Product Default Fabric</label>
                    
                    <table class="table">
                        
                        <thead>
                            <tr>
                                <th>Fabric Name</th>
                                <th>Apply To</th>                                
                            </tr>
                        </thead>
                        
                        
                        
                        <tbody>
                            <?php foreach($fabric_submenus->result() as $fabric_menu) {?>
                                <tr>
                                    
                                    <td>
                                        <?php echo $fabric_menu->name; ?>
                                    </td>
                                    
                                    <td>
                                        <label><input type="checkbox" value="1" name="product[<?php echo $product->id; ?>][<?php echo $fabric_menu->id; ?>]" 
                                            <?php if(checkProductHasFabric($product_submenu_fabrics, $product->id, $fabric_menu->id, $fabric_id)){ echo "checked"; }?> ></label>
                                    </td>
                                    
                                </tr>
                            <?php }?>
                        </tbody>
                        
                    </table>
                    
                    <?php } ?>
                    
                </div>
                <button type="submit" class="btn btn-primary btn-block" ><?php echo $title; ?></button>
            </form>
        </div>

    </body>
</html>