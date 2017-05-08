<!DOCTYPE html>
<html lang="en">
    
    <head>
        
        <script type="text/javascript">var delete_image_link = "<?= site_url('admin/delete_image') ?>";</script>
        <script src="<?php echo ASSETS_PATH; ?>js/my_file_upload.js"></script>
        
        <script type="text/javascript">

        $(document).ready(function() {

            $("#target option[value='<?php if(isset($product)) echo $product->target; else echo 0; ?>']").prop('selected', true);
                        
            var front_image_uploader = new My_Uploader({
            
                item_id: "<?= $product_id ?>",
                delete_link: "<?= site_url('admin/delete_image') ?>",
                table_name: "shmyde_product_front_image",
                image_dir : "product/front/",
                root : $( "#front_image" ),
                form : $( "#front_image_upload_form" ),
                mode : "single",
                image_name : "front_product_image",
                file_name : "front_file"
            });
            
            var back_image_uploader = new My_Uploader({
            
                item_id: "<?= $product_id ?>",
                delete_link: "<?= site_url('admin/delete_image') ?>",
                table_name: "shmyde_product_back_image",
                image_dir : "product/back/",
                root : $( "#back_image" ),
                form : $( "#back_image_upload_form" ),
                mode : "single",
                image_name : "back_product_image",
                file_name : "back_file"
            });
            
            $( "#add_front_image" ).click(function() {

                var undefined;
                front_image_uploader.add_upload_button(undefined);

            });
            
            $( "#add_back_image" ).click(function() {

                var undefined;
                back_image_uploader.add_upload_button(undefined);

            });
                        
            var front_image_json = '<?php  if (isset($front_images) && !empty($front_images)) {echo $front_images;}   ?>';
                                
            if(front_image_json !== ''){

                var front_images =  JSON.parse('<?php  if (isset($front_images) && !empty($front_images)) {echo $front_images;}   ?>');

                for (var key in front_images) {

                    var id = "#".concat(front_image_uploader.image_name).concat("_").concat(front_images[key]['id']);

                    front_image_uploader.add_upload_button(front_images[key]['id'], front_images[key]['pos_x'], front_images[key]['pos_y'], front_images[key]['is_inner'], front_images[key]['depth'], front_images[key]['is_back_image'] );

                    var base_path = "<?php echo ASSETS_PATH;  ?>".concat("images/product/front/");

                    var image_path = base_path.concat(front_images[key]['name']);

                    $(id).attr("src", image_path);

                }
            }
            
            var back_image_json = '<?php  if (isset($back_images) && !empty($back_images)) {echo $back_images;}   ?>';
                                
            if(back_image_json !== ''){

                var back_images =  JSON.parse('<?php  if (isset($back_images) && !empty($back_images)) {echo $back_images;}   ?>');

                for (var key in back_images) {

                    var id = "#".concat(back_image_uploader.image_name).concat("_").concat(back_images[key]['id']);

                    back_image_uploader.add_upload_button(back_images[key]['id'], back_images[key]['pos_x'], back_images[key]['pos_y'], back_images[key]['is_inner'], back_images[key]['depth'], back_images[key]['depth']);

                    var base_path = "<?php echo ASSETS_PATH;  ?>".concat("images/product/back/");

                    var image_path = base_path.concat(back_images[key]['name']);

                    $(id).attr("src", image_path);

                }
            }
            
            var main_form = $("#create_product");
            
            main_form.submit(function (ev) {
                                                
                submit_upload_form(back_image_uploader);
                
                submit_upload_form(front_image_uploader);
                                

            });
            
        });

        </script>
    </head>

    <body>

        <div style="margin-left:5%; margin-top:5%;">
            <span><a href="<?php echo site_url('admin/view/product'); ?>">View All</a> </span>
        </div>

        <div class="container">
            
          
            
            <div class="container">
                <button type="button" id="add_front_image" class="btn btn-primary" style="margin-top: 25px;">Add Front Image</button>
                <form action="<?php echo site_url('admin/upload_image/'.$product_id);  ?>" role="form" method="post" enctype="multipart/form-data" style="margin-top: 20px; margin-bottom: 20px;" id="front_image_upload_form">                
                    <span id="front_image" name="front_image" class="row">
                    </span>               
                    <input type="text" id="parameters" name="parameters" hidden="true" />
                </form>
            </div>
            
            
            <div class="container">
                <button type="button" id="add_back_image" class="btn btn-primary" style="margin-top: 25px;">Add Back Image</button>
                <form action="<?php echo site_url('admin/upload_image/'.$product_id);  ?>" role="form" method="post" enctype="multipart/form-data" style="margin-top: 20px; margin-bottom: 20px;" id="back_image_upload_form">                               
                    <span id="back_image" name="back_image"  class="row">
                    </span>                
                    <input type="text" id="parameters" name="parameters" hidden="true" />
                </form>
            </div>

            <h2><?php echo $title; ?> PRODUCT</h2>

            <form action="<?php if($title == 'CREATE') echo site_url('admin/create/product'); else echo site_url('admin/edit/product/'.$product->id);  ?>" role="form" method="post" enctype="multipart/form-data" id="create_product">

                <div class="form-group">
                  <label for="name">Name:</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?php  if(isset($product)) echo $product->name; ?>">
                </div>

                <div class="form-group">
                  <label for="name">URL Name:</label>
                  <input type="text" class="form-control" id="url_name" name="url_name" value="<?php  if(isset($product)) echo $product->url_name; ?>">
                </div>

                <div class="form-group">
                  <label for="name">Price (FCFA):</label>
                  <input type="text" class="form-control" id="price" name="price" value="<?php  if(isset($product)) echo $product->base_price; ?>">
                </div>

                <div class="form-group">
                    <label for="target">Target:</label>
                    <select class="form-control" id="target" name="target">
                    <option value="0">Men</option>
                        <option value="1">Women</option>
                        <option value="2">Both</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><?php echo $title; ?></button>

            </form>
        </div>

    </body>
</html>