<!DOCTYPE html>
<html lang="en">
    
    <head>
        <script type="text/javascript">
            function readURL(input) {
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        
                        $('#thread_image').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </head>

    <body>

        <div style="margin-left:5%; margin-top:5%;">
            <span><a href="<?php echo site_url('admin/view/thread'); ?>">View All</a> </span>
        </div>

        <div class="container">

            <h2><?php echo $title; ?> THREAD</h2>

            <form action="<?php if($title == 'CREATE') { echo site_url('admin/create/thread'); } else { echo site_url('admin/edit/thread/'.$thread->id); }  ?>" role="form" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <input onchange="readURL(this);" name="image" id="image" type="file"/>
                <img alt="Thread Image" style="width : 200px; height: 200px; margin-top: 5px; " id="thread_image" src="<?php if(isset($thread)) { echo ASSETS_PATH."images/threads/".$thread->image_name; } ?>" />
            </div>
                
            <div class="form-group">
                <label for="name">Color:</label>
                <input type="color" class="form-control" id="color" name="color" value="<?php  if(isset($thread)) { echo $thread->color; } else { echo '#ffffff'; } ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-block"><?php echo $title; ?></button>

            </form>

        </div>

    </body>

</html>