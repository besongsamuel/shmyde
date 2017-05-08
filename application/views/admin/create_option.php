<!DOCTYPE html>
<html lang="en">
    
    <script src="<?php echo ASSETS_PATH; ?>js/my_file_upload.js"></script>
  
    <script type="text/javascript">
        
        var selected_product = <?php echo json_encode($selected_product); ?>
        
        var selected_menu = <?php echo json_encode($selected_menu); ?>
        
        var is_edit = Boolean(<?php echo $is_edit; ?>);
                
        function ProductChanged(_selected_menu){
	
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {

                if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                    $("#menu").empty();

                    var json_array =  JSON.parse(xmlhttp.responseText);

                    for (var key in json_array) {

                        $('#menu').append($("<option/>", {
                            value: json_array[key]['id'],
                            text:  json_array[key]['name']
                        }));

                    }
                        
                    if(parseInt(_selected_menu === -1))  
                        $("#menu").val($("#menu option:first").val());
                    else
                        $("#menu option[value='" + selected_menu + "']").prop('selected', true);
   
                }
            };

            var site_url = "<?php echo site_url('admin/get_menus') ?>";

            site_url = site_url.concat("/").concat($( "#product" ).val());

            xmlhttp.open("GET", site_url, true);

            xmlhttp.send();
        }

        $(document).ready(function() {

            ProductChanged(selected_menu);
                                                                      
            var multi_uploader = new My_Uploader({
            
                item_id: "<?= $option_id ?>",
                delete_link: "<?= site_url('admin/delete_image') ?>",
                table_name: "shmyde_images",
                image_dir : "design/",
                root : $( "#images" ),
                mode : "multiple", 
                image_name : "option_image",
                file_name : "option_file",
                form : $( "#multiple_image_upload_form" )
            });
    
            var single_uploader = new My_Uploader({
            
                item_id: "<?= $option_id ?>",
                delete_link: "<?= site_url('admin/delete_image') ?>",
                table_name: "shmyde_option_thumbnail",
                image_dir : "design/thumbnail/",
                root : $( "#image" ),
                form : $( "#single_image_upload_form" ),
                mode : "single",
                image_name : "option_thumbnail",
                file_name : "caption_file"
            });
                        
            if(is_edit){
                
                var images_json = '<?php  if (isset($option_images) && !empty($option_images)) {echo $option_images;}  ?>';
                
                if(images_json !== ''){
                
                
                    var option_images =  JSON.parse('<?php  if (isset($option_images) && !empty($option_images)) {echo $option_images;}  ?>');

                    for (var key in option_images) {

                        var id = "#".concat(multi_uploader.image_name).concat("_").concat(option_images[key]['id']);
                                                                                                
                        multi_uploader.add_upload_button(option_images[key]['id'], option_images[key]['pos_x'], option_images[key]['pos_y'], option_images[key]['is_inner'], option_images[key]['depth'], option_images[key]['is_back_image'] );

                        var base_path = "<?php echo ASSETS_PATH;  ?>".concat("images/design/");
                        
                        var image_path = base_path.concat(option_images[key]['name']);
                                                
                        $(id).attr("src", image_path);

                    }
                }
                
                var thumbnail_json = '<?php  if (isset($option_thumbnails) && !empty($option_thumbnails)) {echo $option_thumbnails;}   ?>';
                
                if(thumbnail_json !== ''){
                
                    var thumbnail_images =  JSON.parse('<?php  if (isset($option_thumbnails) && !empty($option_thumbnails)) {echo $option_thumbnails;}   ?>');

                    for (var key in thumbnail_images) {
                                                       
                        var id = "#".concat(single_uploader.image_name).concat("_").concat(thumbnail_images[key]['id']);
                                                                        
                        single_uploader.add_upload_button(thumbnail_images[key]['id'], thumbnail_images[key]['pos_x'], thumbnail_images[key]['pos_y'], thumbnail_images[key]['is_inner'], thumbnail_images[key]['depth'], thumbnail_images[key]['is_back_image'] );

                        var base_path = "<?php echo ASSETS_PATH;  ?>".concat("images/design/thumbnail/");

                        var image_path = base_path.concat(thumbnail_images[key]['name']);
                        
                        $(id).attr("src", image_path);

                    }
                }
                
                
            }
                      
            ///Add a new upload button with a preview image space and a delete button
            $( "#add_image" ).click(function() {

                var undefined;
                multi_uploader.add_upload_button(undefined, 0, 0, 0, 0);

            });
            
            ///Add a new upload button with a preview image space and a delete button
            $( "#add_thumbnail" ).click(function() {

                var undefined;
                single_uploader.add_upload_button(undefined, 0, 0, 0, 0);

            });
                       
            
            var main_form = $("#create_option");
            
            main_form.submit(function (ev) {
                                 
                submit_upload_form(single_uploader);
                
                submit_upload_form(multi_uploader);
                
                

            });
            
            
                                              
        });
 
    </script>

    <body>


        <div style="margin-left:5%; margin-top:5%;">
            <span><a href="<?php echo site_url('admin/view/option'); ?>">View All</a> </span>
        </div>

        <div class="container">

            <h2><?php echo $title; ?> OPTION</h2>

            <!-- IMAGE UPLOAD SECTION -->
            
            <div class="container">
            
                <button type="button" id="add_image" class="btn btn-primary" style="margin-top: 25px;">Add Image</button>

                <form action="<?php echo site_url('admin/upload_image/'.$option_id);  ?>" role="form" method="post" enctype="multipart/form-data" style="margin-top: 20px; margin-bottom: 20px;" id="multiple_image_upload_form">

                    <div id="images" name="images" class="row">

                    </div>

                    <input type="text" id="parameters" name="parameters" hidden="true" />

                </form>
            
            </div>
            
            <div class="container">

                <button type="button" id="add_thumbnail" class="btn btn-primary" style="margin-top: 25px;">Add Thumbnail</button>

                <form action="<?php echo site_url('admin/upload_image/'.$option_id);  ?>" role="form" method="post" enctype="multipart/form-data" style="margin-top: 20px; margin-bottom: 20px;" id="single_image_upload_form">


                    <div id="image" name="image" class="row">

                    </div>

                    <input type="text" id="parameters" name="parameters" hidden="true" />


                </form>
            
            </div>

            <!-- IMAGE UPLOAD SECTION END -->

            <!-- OPTION PARAMETERS SECTION -->

            <form action="<?php if($title == 'CREATE') { echo site_url('admin/create/option'); } else { echo site_url('admin/edit/option/'.$option->id); }  ?>" role="form" method="post" enctype="multipart/form-data" id="create_option">

                <div class="form-group">
                    <label for="product">Product:</label>
                    <select class="form-control" id="product" name="product" value="<?php echo $selected_product; ?>" onchange="ProductChanged(-1);">
                        <?php foreach ($products->result() as $row) {?>
                        <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="menu">Menu:</label>
                    <select class="form-control" id="menu" name="menu">
                        
                    </select>
                </div>
                
                <b>Dependent Menus:</b>
                
                <table class="table">
                        
                    <thead>
                        <tr>
                            <th>Menu Name</th>
                            <th>Yes</th>                                
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($menus->result() as $menu) {?>
                            <tr>

                                <td>
                                    <?php echo $menu->name; ?>
                                </td>

                                <td>
                                    <label><input type="checkbox" value="1" name="option_dependent_menu[<?php echo $menu->id; ?>]" 
                                        <?php if(isset($option_dependent_menu[$menu->id])){ echo "checked"; }?> ></label>
                                </td>

                            </tr>
                        <?php }?>
                    </tbody>
                        
                </table>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php  if(isset($option)) { echo $option->name; } ?>">
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php  if(isset($option)) { echo $option->price; } else { echo '0'; } ?>">
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" rows="5" id="description" name="description"><?php  if(isset($option)) { echo $option->description; } ?></textarea>
                </div>

                <div class="form-group">
                    <label for="is_default">Is Default:</label>
                    <input type="checkbox" class="form-control" id="is_default" name="is_default" <?php if(isset($option) && $option->is_default) { echo 'checked'; } ?>>
                </div>

                <button type="submit" class="btn btn-danger btn-block"><?php echo $title; ?></button>
                
            </form>

        </div>

    </body>

</html>