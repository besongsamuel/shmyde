<!DOCTYPE html>
<html lang="en">
    
    <head>
                
    <script type="text/javascript">
        
        var id;
        
        var editor_type;
        
        var selected_item_id = -1;
        
        var selected_button_id = -1;
        
        var button_image_size = 10;
        
        var button_picked = false;
        
        var button_x_pos = 0;
        
        var button_y_pos = 0;
        
        var step = 0.5;
        
        
         
        /**
         * This function loads for a given product, all the style images
         * associated with it
         * @param {type} option_id
         * @returns {undefined}
         */ 
        function load_style_images(){
                       
            var base_image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/design/");
            
            var xmlhttp = new XMLHttpRequest();
                       
            xmlhttp.onreadystatechange = function(){ 
            
                if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {
                                        
                    $("#images").empty();
                    
                    $("#buttons").empty();

                    var image_styles =  JSON.parse(xmlhttp.responseText);
                    
                    console.log(image_styles);
                                        
                    for(var index in image_styles){
                          
                        var style_image = image_styles[index];

                        var image_path = base_image_dir + style_image["name"];

                        var image_id = style_image["id"];

                        $("#images").append(
                            $("<a>").attr("href", "#")
                            .attr("class", "list-group-item")
                            .attr("onclick", "load_style_buttons(" + image_id + ")")
                            .append(
                                $("<img>").attr("style", "width : 100px; height : 100px")
                                .attr("src", image_path)
                                .attr("id", "image_" + image_id)
                            )
                        );
 
                    }
                }
                
                
            };
            
            var site_url = "<?php echo site_url('admin/get_product_style_images') ?>";

            site_url = site_url.concat("/").concat(id);

            xmlhttp.open("GET", site_url, true);

            xmlhttp.send();
            
        }
        
        function load_product_images(){
            
            var base_image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/product/front/");
            
            var xmlhttp = new XMLHttpRequest();
                       
            xmlhttp.onreadystatechange = function(){ 
            
                if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {
                                        
                    $("#images").empty();
                    
                    $("#buttons").empty();

                    var image_styles =  JSON.parse(xmlhttp.responseText);
                                        
                    for(var index in image_styles){
                                                    
                        var style_image = image_styles[index];

                        var image_path = base_image_dir + style_image["name"];

                        var image_id = style_image["id"];
                        
                        $("#images").append($("<h4>").text("Front Images"));
                        
                        $("#images")
                            .append(
                            $("<a>").attr("href", "#")
                            .attr("class", "list-group-item")
                            .attr("onclick", "load_product_buttons(" + image_id + ")")
                            .append(
                                $("<img>").attr("style", "width : 230px; height : 300px; margin: 10px; ")
                                .attr("src", image_path)
                                .attr("class", "front")
                                .attr("id", "image_" + image_id)
                            )
                        );
                            
                        
                         
                    }
                    
                    load_product_back_images();
                }
                
                
            };
            
            var site_url = "<?php echo site_url('admin/get_product_base_images/front') ?>";

            site_url = site_url.concat("/").concat(id);

            xmlhttp.open("GET", site_url, true);

            xmlhttp.send();
        }
        
        function load_product_back_images(){
            
            var base_image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/product/back/");
            
            var xmlhttp = new XMLHttpRequest();
                       
            xmlhttp.onreadystatechange = function(){ 
            
                if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {
                                        

                    var image_styles =  JSON.parse(xmlhttp.responseText);
                                        
                    for(index in image_styles){
                                                    
                        var style_image = image_styles[index];

                        var image_path = base_image_dir + style_image["name"];

                        var image_id = style_image["id"];
                        
                        $("#images").append($("<h4>").text("Back Images"));
                        
                        $("#images")
                            .append(
                            $("<a>").attr("href", "#")
                            .attr("class", "list-group-item")
                            .attr("onclick", "load_product_buttons(" + image_id + ")")
                            .append(
                                $("<img>").attr("style", "width : 230px; height : 300px")
                                .attr("src", image_path)
                                .attr("id", "image_" + image_id)
                                .attr("class", "back")
                            )
                        );
                                                                           
                    }
                }
                
                
            };
            
            var site_url = "<?php echo site_url('admin/get_product_base_images/back') ?>";

            site_url = site_url.concat("/").concat(id);

            xmlhttp.open("GET", site_url, true);

            xmlhttp.send();
        }
        
        function create_new_button(){
            
            if(parseInt(selected_item_id) !== -1){
                
                var xmlhttp = new XMLHttpRequest();
                
                
                xmlhttp.onreadystatechange = function(){
                    
                    if(parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200){
                        
                        var button_create_response = JSON.parse(xmlhttp.responseText);
                        
                        selected_button_id = button_create_response["id"];
                        
                        var image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/buttons/') + button_create_response["image_name"];
                        
                        var x_pos = $("#editor_image").width() / 2;
            
                        var y_pos = $("#editor_image").height() / 2;
                        
                        button_x_pos = x_pos;
                        
                        button_y_pos = x_pos;
                        
                        add_button_to_editor(image_path, selected_button_id, x_pos, y_pos);
                    }
                };
                
                var request_url = "";
                
                if("style".toString() === editor_type.toString()){

                    request_url = "<?php echo site_url('admin/create_new_style_button'); ?>";
                    
                    request_url = request_url + "/" + selected_item_id;

                }

                if("product".toString() === editor_type.toString()){
                    
                    var side = $("#image_" + selected_item_id).attr("class").toString();
                    
                    request_url = "<?php echo site_url('admin/create_new_product_button'); ?>";
                    
                    request_url = request_url + "/" + selected_item_id;
                    
                    if(side === "front"){
                        
                        request_url = request_url + "/1"; 
                        
                    }
                    else{
                        
                        request_url = request_url + "/0";
                    }
                    
                    
                }

                xmlhttp.open("GET", request_url, true);
                
                xmlhttp.send();
                
                
            }
        }
        
        function add_button_to_editor(image_path, id, x_pos, y_pos){
            
            $('.buttons').css({ 
                    border: "none"
                });
                
            $("#buttons").append(
                            
                            $("<a>").attr("href", "#")
                            .attr("id", "list_button_" + id)
                            .attr("class", "list-group-item")
                            .attr("onclick", "select_button(" + id + ");")
                            .text("BUTTON " + id)
                        );    
                            
            $("#designer").append(
                    $("<img>").attr("style", "position : absolute; left : " + x_pos + "px; top : " + y_pos + "px; border:1px solid red")
                    .attr("src", image_path)
                    .attr("width", button_image_size + "px")
                    .attr("height", button_image_size + "px")
                    .attr("id", "button_" + id)
                    .attr("class", "buttons")
                    );
            
            
        
        }
            
        function load_style_buttons(option_id){
            
            selected_item_id = option_id;
                        
            load_editor_background(option_id);
            
            load_buttons();
            
        }
        
        function load_product_buttons(product_id){
            
            selected_item_id = product_id;
            
            load_editor_background(product_id);
            
            load_buttons();
            
            
            
        }
        
        function load_buttons(){
            
            var xmlhttp = new XMLHttpRequest();
            
            $("#buttons").empty();
            
            $( ".buttons" ).remove();
            
            xmlhttp.onreadystatechange = function(){
                
                if(parseInt(xmlhttp.status) === 200 && parseInt(xmlhttp.readyState) === 4){
                    
                    var json_buttons = JSON.parse(xmlhttp.responseText);
                    
                    for(var index in json_buttons){
                        
                        var button = json_buttons[index];
                        
                        
                
                        var image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/buttons/') + button["image_name"];
                        
                        add_button_to_editor(image_path, button["id"], button["pos_x"], button["pos_y"]);
                        
                    }
                }
                
                $('.buttons').css({ 
                    border: "none"
                });
            };
            
            var request_url = "";
                
            if("style".toString() === editor_type.toString()){

                request_url = "<?php echo site_url('admin/load_style_buttons'); ?>";

                request_url = request_url + "/" + selected_item_id;

            }

            if("product".toString() === editor_type.toString()){

                var side = $("#image_" + selected_item_id).attr("class").toString();

                request_url = "<?php echo site_url('admin/load_product_buttons'); ?>";

                request_url = request_url + "/" + selected_item_id;

                if(side === "front"){

                    request_url = request_url + "/1"; 

                }
                else{

                    request_url = request_url + "/0";
                }


            }               
                
            xmlhttp.open("GET", request_url, true);

            xmlhttp.send();
            
            
        }
        
        function select_button(button_id){
                        
            button_picked = false;
            
            selected_button_id = button_id;
            
            $('.buttons').css({ 
                    border: "none"
                });
                            
            
           var position = $('#button_' + selected_button_id).position();
           
           button_x_pos = position.left;
           
           button_y_pos = position.top;
                       
           $('#button_' + selected_button_id).css({ 
                border: "1px solid red"
            }); 
            
            
        }
        
        function load_editor_background(id){
            
            var element_id = "#image_" + id;
            
            $("#editor_image").prop("src", $(element_id).prop("src"));
            $("#editor_image").prop("class", "preview-image");           
            
        }
        
        function delete_button(){
            
            var xmlhttp = new XMLHttpRequest();
            
            xmlhttp.onreadystatechange = function(){
                
                if(parseInt(xmlhttp.status) === 200 && parseInt(xmlhttp.readyState) === 4){
                
                    var json_response = JSON.parse(xmlhttp.responseText);
                    
                    if(json_response){
                        
                       alert("Button Deleted"); 
                    }
                    
                    $("#list_button_" + selected_button_id).remove();
                    $("#button_" + selected_button_id).remove();
                    
                    
                    selected_button_id = -1;
                }
                
            };
            
            var request_url = "";
                            
            if("style".toString() === editor_type.toString()){

                request_url = "<?php echo site_url('admin/delete_style_button'); ?>";

                request_url = request_url + "/" + selected_button_id;

            }

            if("product".toString() === editor_type.toString()){

                var side = $("#image_" + selected_item_id).attr("class").toString();

                request_url = "<?php echo site_url('admin/delete_product_button'); ?>";

                request_url = request_url + "/" + selected_button_id;

                if(side === "front"){

                    request_url = request_url + "/1"; 

                }
                else{

                    request_url = request_url + "/0";
                }


            }               
                
            xmlhttp.open("GET", request_url, true);

            xmlhttp.send();
        }
        
        function save_button(){
        
            var xmlhttp = new XMLHttpRequest();
            
            xmlhttp.onreadystatechange = function(){
                
                if(parseInt(xmlhttp.status) === 200 && parseInt(xmlhttp.readyState) === 4){
                
                    var json_response = JSON.parse(xmlhttp.responseText);
                    
                    if(json_response){
                        
                       alert("Button Saved !!!"); 
                    }
                    
                }
                
            };
            
            var request_url = "";
                            
            if("style".toString() === editor_type.toString()){

                request_url = "<?php echo site_url('admin/save_style_button'); ?>";

                request_url = request_url + "/" + selected_button_id;

            }

            if("product".toString() === editor_type.toString()){

                var side = $("#image_" + selected_item_id).attr("class").toString();

                request_url = "<?php echo site_url('admin/save_product_button'); ?>";

                request_url = request_url + "/" + selected_button_id;

                if(side === "front"){

                    request_url = request_url + "/1"; 

                }
                else{

                    request_url = request_url + "/0";
                }


            }   
            
            request_url = request_url + "/" + button_x_pos + "/" + button_y_pos;
                
            xmlhttp.open("GET", request_url, true);

            xmlhttp.send();
        }
        
        $(document).ready(function(){
            
            id = parseInt(<?php echo $id; ?>);
            
            editor_type = <?php echo $editor_type; ?>;
                        
            if("style".toString() === editor_type.toString()){
                
                load_style_images();
            }
            
            if("product".toString() === editor_type.toString()){
                
                load_product_images();
            }
            
            $( "#editor_images" ).click(function(e) {
                  
                                  
                if(button_picked){
                    
                    button_picked = false;
                    
                    return;
                }      
                button_picked = true;
                
                var parentOffset = $(this).parent().offset();
                
                var relX = e.pageX - parentOffset.left - (button_image_size / 2);
                var relY = e.pageY - parentOffset.top - (button_image_size / 2);
                
                button_x_pos = relX;
           
                button_y_pos = relY;
                
                $('#button_' + selected_button_id).css({ 
                    position: "absolute",
                    top: relY, left: relX   
                });
                
            });
                       
            
            $( "#editor_image" ).mousemove(function(e) {
                                                
                if(button_picked){
                    
                    var parentOffset = $(this).parent().offset();
                
                    var relX = e.pageX - parentOffset.left - (button_image_size / 2);
                    var relY = e.pageY - parentOffset.top - (button_image_size / 2);
                    
                    button_x_pos = relX;
           
                    button_y_pos = relY;

                    $('#button_' + selected_button_id).css({ 
                        position: "absolute",
                        top: relY, left: relX
                    });
                } 
                                               
            });
            
            
            
            
        });
        
        $(document).keydown(function(e) {
                                          
            if(parseInt(selected_button_id) === -1)
                return;

            switch(e.which) {
                case 37: // left
                    button_x_pos -= step;
                break;

                case 38: // up
                    button_y_pos -= step;
                break;

                case 39: // right
                    button_x_pos += step;
                break;

                case 40: // down
                    button_y_pos += step;
                break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)

            $('#button_' + selected_button_id).css({ 
                position: "absolute",
                top: button_y_pos, left: button_x_pos
            });
        });

    </script>
    
    </head>

    <body>
       
        <div class="container" style="margin-top: 30px;">
            
            <div class="row">
                
                <!-- Image List -->
                <div class="col-sm-2">
                    <div class="list-group" id="images">
                        
                    </div>
                </div>
                
                <!-- Button List -->
                <div class="col-sm-4">
                    <div style="margin-bottom: 10px;">
                        <button type="button" class="" onclick="create_new_button()">New Button</button>
                        <button type="button" class="" onclick="delete_button()">Delete Button</button>
                        <button type="button" class="" onclick="save_button()">Save Button</button>
                    </div>
                    <div class="list-group" id="buttons">
                        <a href="#" class="list-group-item active">First item</a>
                        <a href="#" class="list-group-item">Second item</a>
                        <a href="#" class="list-group-item">Third item</a>
                    </div>
                </div>
                
                <!-- Editor -->
                <div class="col-sm-3" id="editor">
                   
                    <div style="width : 230px; height: 300px;" id="editor_images">
                        <div style="position : absolute">
                            <div style="position: relative" id="designer">
                                <img id="editor_image" src="" style="position : absolute;" >
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            
        </div>

    </body>
</html>