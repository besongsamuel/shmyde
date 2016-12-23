<!DOCTYPE html>
<html lang="en">
    
    <head>
        <script type="text/javascript">
            function readURL(input, image) {
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        
                        if(parseInt(image) === 0)
                        {
                            $('#button_image').attr('src', e.target.result);
                        }
                        else
                        {
                            $('#button_design_image').attr('src', e.target.result);
                        }
                        
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </head>

    <body>

        <div style="margin-left:5%; margin-top:5%;">
            <span><a href="<?php echo site_url('admin/view/button'); ?>">View All</a> </span>
        </div>

        <div class="container">

            <h2><?php echo $title; ?> BUTTON</h2>

            <form action="<?php if($title == 'CREATE') { echo site_url('admin/create/button'); } else { echo site_url('admin/edit/button/'.$button->id); }  ?>" role="form" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <input onchange="readURL(this, 0);" name="image" id="image" type="file"/>
                <img alt="Button Image Small" style="width : 50px; height: 50px; margin-top: 5px; " id="button_image" src="<?php if(isset($button)) { echo ASSETS_PATH."images/buttons/".$button->image_name; } ?>" />
            </div>
                
            <div class="form-group">
                <input onchange="readURL(this, 1);" name="design_image" id="design_image" type="file"/>
                <img alt="Button Image Big" style="width : 200px; height: 200px; margin-top: 5px; " id="button_design_image" src="<?php if(isset($button)) { echo ASSETS_PATH."images/buttons/".$button->design_image_name; } ?>" />
            </div>    
                
            <div class="form-group">
                <label for="name">Name :</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php  if(isset($button)) { echo $button->name; } ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-block"><?php echo $title; ?></button>

            </form>

        </div>

    </body>

</html>