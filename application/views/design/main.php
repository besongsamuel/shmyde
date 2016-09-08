
<html lang="en">

<script>
    
    
    ///Displays the list of option thumbnail images
    var sly;
    
    /**
     * Displays the list of threads
     * @type Sly
     */
    var threadsSly;
    /**
     * This value stores the current selected category
     * @type selected_category
     */
    var category_index;
    
    /**
     * This value stores the index of the current selected menu item
     * @type menu_id
     */
    var menu_index;
    
    /**
     * This value stores the id of the product being designed
     * @type undefined
     */
    var product_id;
    
    /**
     * This value stores the current default or primary fabric
     * @type undefined|option_object
     */
    var default_fabric;
    
    /**
     * This value stores the current mix or secondary fabric
     * @type undefined|option_object
     */
    var mix_fabric;
    
    /**
     * This variable stores the status of the checked menus in the 
     * mixed design menu
     * @type type
     */
    var mix_fabric_check_status = {};
        
    /**
     * THis represents the images that will be added to the canvas. 
     * @type Array
     */
    var canvas_images = [];
    
    
    /****************************************************************************************************************************************/
    var design_data;
    
    var current_side = 'front';
    
    var current_design_data;
    
    /**
     * The current design button
     * @type type
     */
    var currentButton = null;
    
    /**
     * The current design thread used with the button
     * @type type
     */
    var currentThread = null;
    
    /**
     * Al system buttons
     * @type type
     */
    var Buttons = null;
    
    /**
     * All system threads
     * @type type
     */
    var Threads = null;
    
    var button_image_size = 10;
               
    $(document).ready(function(){
         
        $('#myMeasurementModal').on('shown.bs.modal', function() {
            
            LoadMeasurementsIntoModal();
        });
        
        $('#requestMeasurementModal').on('shown.bs.modal', function() {
            
            LoadUserDataIntoModal();
        });
        
        $('#buttonsModal').on('shown.bs.modal', function() {
            
            LoadThreadsToSly();;
        });

        design_data = <?php echo $design_data;  ?>;
        
        current_design_data = design_data['design'];
        
        console.log(design_data);
                        
        default_fabric = <?php echo $default_fabric; ?>
        
        mix_fabric = <?php echo $mix_fabric; ?>
                
        product_id = <?php echo json_encode($product_id); ?>;
        
        currentButton = <?php echo json_encode($currentButton); ?>;
        
        currentThread = <?php echo json_encode($currentThread); ?>;
        
        Buttons = JSON.parse(<?php echo json_encode($Buttons); ?>);
        
        Threads = JSON.parse(<?php echo json_encode($Threads); ?>);
                  
        LoadParametersToPreview();
        
        var buttonsFrame = $('#button-design-threads');
        
        var buttonsWrap  = buttonsFrame.parent();
        
        threadsSly = new Sly( buttonsFrame, {
                    horizontal: true,
                    itemNav: 'centered',
                    smart: true,
                    activateOn: 'click',
                    mouseDragging: 1,
                    touchDragging: 1,
                    releaseSwing: 1,
                    startAt: 0,
                    scrollBar: buttonsWrap.find('.scrollbar'),
                    scrollBy: 1,
                    speed: 300,
                    elasticBounds: 1,
                    easing: 'easeOutExpo',
                    dragHandle: 1,
                    dynamicHandle: 1,
                    clickBar: 1

		}, {                         
        }).init();
        
        var frame = $('#design_options');
        
        var wrap  = frame.parent();
        
        sly = new Sly( frame, {
                    horizontal: true,
                    itemNav: 'centered',
                    smart: true,
                    activateOn: 'click',
                    mouseDragging: 1,
                    touchDragging: 1,
                    releaseSwing: 1,
                    startAt: 0,
                    scrollBar: wrap.find('.scrollbar'),
                    scrollBy: 1,
                    speed: 300,
                    elasticBounds: 1,
                    easing: 'easeOutExpo',
                    dragHandle: 1,
                    dynamicHandle: 1,
                    clickBar: 1

		}, {   
        }).init();
        
    });
    
     
    /**
     * This function loads the parameters from the controller
     * to the image preview in the view
     * @returns {undefined}
     */ 
    function LoadParametersToPreview(){
        
        console.log('LoadParametersToPreview()');
        
        canvas_images = [];
                
        var base_image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/product/");
                                    
        for(var image_key in current_design_data[current_side + '_images']){
            
            var base_image = current_design_data[current_side + '_images'][image_key];
            
            var image_path = base_image_dir.concat(current_side + "/").concat(base_image.name);

            var base_image_id = base_image.id;

            var depth = base_image.depth;

            if("name" in default_fabric){

                var blend_path = base_image_dir + "fabric/" + default_fabric['name'];

                var data = {

                    image_url: image_path,
                    blend_url : blend_path,
                    id : "front_base",
                    image_id : base_image_id,
                    depth : depth,
                    pos_x : base_image.pos_x,
                    pos_y : base_image.pos_y
                };

                canvas_images.push(data);

            }
            else{

                var data = {

                    image_url: image_path,
                    id : "front_base",
                    image_id : base_image_id,
                    depth : depth,
                    pos_x : base_image.pos_x,
                    pos_y : base_image.pos_y
                };

                canvas_images.push(data);
                
                                  

            }           
        }
        
        LoadDesignOptionsToCanvas(); 
                    
        BlendDesignImages($( "#design-preview" ));
        
        
                
    }
    
    /**
     * This function loads the default design options
     * from the controller to the image preview
     * in the view
     * @returns {undefined}
     */
    function LoadDesignOptionsToCanvas(){
        
        console.log('LoadDesignOptionsToCanvas()');
        
        var base_image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/product/");
        
        var image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/design/");
                
        for (var menu in current_design_data['design']) {
                    
            if(current_design_data['design'][menu].option !== null){

                var design_option = current_design_data['design'][menu].option;

                for(var image in design_option["images"]){
                                      
                    var design_image = design_option["images"][image];
                    
                    if(current_side === 'front' && parseInt(design_image.is_back_image) === 1){
                        
                        continue;
                    }
                    
                    if(current_side === 'back' && parseInt(design_image.is_back_image) === 0){
                        
                        continue;
                    }

                    var option_id = design_image.item_id;

                    var image_path = image_dir.concat(design_image.name);

                    var image_id = design_image.id;

                    var depth = design_image.depth;

                    var use_fabric = choose_fabric(menu, image_id);

                    if("name" in use_fabric){

                        design_image.fabric_id = use_fabric["id"];

                        var blend_path = base_image_dir + "fabric/" + use_fabric['name'];

                        var data = {

                            image_url: image_path,
                            blend_url : blend_path,
                            id : option_id,
                            image_id : image_id,
                            depth : depth,
                            pos_x : design_image.pos_x,
                            pos_y : design_image.pos_y
                        };

                        canvas_images.push(data);


                    }
                    else{

                        var data = {

                            image_url: image_path,
                            id : option_id,
                            image_id : image_id,
                            depth : depth,
                            pos_x : design_image.pos_x,
                            pos_y : design_image.pos_y
                        };

                        canvas_images.push(data);

                    }                     
                }
            }

        }
    }
    
    /**
     * This function blends all images in the canvas
     * with the proper fabrics, add them to the image preview and later adds all required
     * buttons to the image preview in the view
     * @returns {undefined}
     */
    function BlendDesignImages(element){
        
        $.post('<?= site_url("Design/BlendImages/"); ?>',{ images : JSON.stringify(canvas_images) },function(json_result){

            var images = JSON.parse(json_result);
                                  
            element.fadeOut( "300", function() {
                               
                element.empty();
                
                for(var key in images){
                
                    var image = images[key];
                    
                    if(parseInt(image.image_id) !== -1){
                        
                        add_image_layer(image.image, image.id, image.image_id);
                    }
                    else{
                        
                        add_button_layer(image.image, image.id, image.pos_x, image.pos_y);
                    }

                }
                
                element.fadeIn("300");
                
                LoadButtons();
                
            });
            
        });
    }
    
    /**
     * This function blends the buttons with its thread
     * then displays it on the product
     * @returns {undefined}
     */
    function BlendButtonImages(){
        
        $.post('<?= site_url("Design/BlendImages/"); ?>',{ images : JSON.stringify(canvas_images) },function(json_result){

            var images = JSON.parse(json_result);

            for(var key in images){

                var image = images[key];

                if(parseInt(image.image_id) === -1){

                    add_button_layer(image.image, image.id, image.pos_x, image.pos_y);
                }

            }
                                        
        });
    }
    
    /**
     * This function replaces an optionElement supplied with the 
     * current images in the canvas 
     * @returns {undefined}
     */
    function replaceOptionElementWithCanvasImages(previousOptionElement){
        
        $.post('<?= site_url("Design/BlendImages/"); ?>',{ images : JSON.stringify(canvas_images) },function(json_result){

            var images = JSON.parse(json_result);
                                  
            previousOptionElement.fadeOut( "300", function() {
                               
                previousOptionElement.remove();
                
                for(var key in images){
                
                    var image = images[key];
                                        
                    if(parseInt(image.image_id) !== -1){
                        
                        add_image_layer(image.image, image.id, image.image_id);
                    }
                    else{
                        
                        add_button_layer(image.image, image.id, image.pos_x, image.pos_y);
                    }
                
                }
                
            });
            
        });
    }
    
    /**
    * This function is called when a fabric is selected from the mixed design menu. 
    * It is then applied to the appropriate menu options that are checked
    * @param {type} fabric_id
    * @returns {undefined}     */
    function apply_mix_fabrics(){
         
        if(!("name" in mix_fabric)){
            
            return;
        } 
                       
        var base_image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/product/");
        
        var image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/design/");
              
        for (var menu in current_design_data['design']) {
            
            var menu_data = current_design_data['design'][menu];
            
            if(menu_data.mixed_fabric_support){
                
                if(current_design_data['design'][menu].option !== null){
                
                    var option_data = current_design_data['design'][menu].option;
                 
                    for(var image in option_data['images']){
                        
                        var design_image = option_data['images'][image];
                        
                        if(current_side === 'front' && parseInt(design_image.is_back_image) === 1){
                        
                            continue;
                        }

                        if(current_side === 'back' && parseInt(design_image.is_back_image) === 0){

                            continue;
                        }
                        
                        var option_id = design_image.item_id;

                        var image_path = image_dir.concat(design_image.name);

                        var image_id = design_image.id;
                           
                        if(((parseInt(design_image.is_inner) === 0 && mix_fabric_check_status['mix_design_outer_' + menu]) 
                            || (parseInt(design_image.is_inner) === 1 && parseInt(menu_data.inner_contrast_support)=== 1 && mix_fabric_check_status['mix_design_inner_' + menu]))
                            && parseInt(design_image.fabric_id) !== parseInt(mix_fabric['id'])) {
                                       
                                       
                            design_image.fabric_id = parseInt(mix_fabric['id']);

                            var blend_path = base_image_dir + "fabric/" + mix_fabric['name'];

                            var curr_elem_id = "#option_".concat(option_id) + "_" + image_id;
                            
                            console.log("Changing fabric for " + design_image + " : " + curr_elem_id);

                            $.post('<?= site_url("Design/BlendImage/"); ?>',{image_url :  image_path, blend_url : blend_path, id : option_id, image_id : image_id, element_id : curr_elem_id },function(dataurl){

                                var data = JSON.parse(dataurl);
                                                              
                                replace_image_layer(data.image, data.id, data.image_id, data.element_id);

                            });
                        }

                        if(((parseInt(design_image.is_inner) === 0 && !mix_fabric_check_status['mix_design_outer_' + menu]) 
                            || (parseInt(design_image.is_inner) === 1 && parseInt(menu_data.inner_contrast_support) === 1 && !mix_fabric_check_status['mix_design_inner_' + menu] ))
                            && parseInt(design_image.fabric_id) !== parseInt(default_fabric['id'])){

                            design_image.fabric_id = parseInt(default_fabric['id']);

                            var blend_path = base_image_dir + "fabric/" + default_fabric['name'];

                            var curr_elem_id = "#option_".concat(option_id) + "_" + image_id;
                            
                            console.log("Changing fabric for " + design_image + " : " + curr_elem_id);

                            $.post('<?= site_url("Design/BlendImage/"); ?>',{image_url :  image_path, blend_url : blend_path, id : option_id, image_id : image_id, element_id : curr_elem_id },function(dataurl){

                                var data = JSON.parse(dataurl);

                                replace_image_layer(data.image, data.id, data.image_id, data.element_id);

                            });
                        }
                                                                              
                    }

                }
            }
                                      
        }      
                
    }
               
    /**
     * This function adds a layer of image unto the image preview element
     * @param {type} option_id This is used to track the element to which the image was added. 
     * This element can later be tracked and used for replacements
     * @returns {undefined}
     */
    function add_image_layer(image, item_id, image_id){
        
        var class_name = "option_class_" + item_id;
        
        var elem = document.createElement("img");
        elem.setAttribute("class", "preview-image " + class_name);
        elem.setAttribute("id", "option_" + item_id + "_" + image_id);
        elem.setAttribute("src", image);
        document.getElementById("design-preview").appendChild(elem);
        
        console.log("Added Image : " +  "option_" + item_id + "_" + image_id);
        
    }
    
    function add_button_layer(image, button_id, pos_x, pos_y){
                
        $("#design-preview").append(
                    $("<img>").attr("style", "position : absolute; left : " + pos_x + "px; top : " + pos_y + "px;")
                    .attr("src", image)
                    .attr("width", button_image_size + "px")
                    .attr("height", button_image_size + "px")
                    .attr("id", "button_" + button_id)
                    .attr("class", "buttons")
                    );
        
        console.log("Added Button : " +"button_" + button_id);
        
    }
    
    function RemoveOptionButtons(designOption){
        
        for(var image in designOption["images"]){
                                      
            var design_image = designOption["images"][image];

            if(current_side === 'front' && parseInt(design_image.is_back_image) === 1){

                continue;
            }

            if(current_side === 'back' && parseInt(design_image.is_back_image) === 0){

                continue;
            }

            for(var button_key in design_image.buttons){
                        
                var button_data = design_image.buttons[button_key];
                
                $("#button_" + button_data.id).remove();

            }                     
        }
    }
    
    function create_image_layer(item_id){
        
        var class_name = "option_class_" + item_id;
        
        var elem = document.createElement("img");
        elem.setAttribute("class", "preview-image " + class_name);
        document.getElementById("design-preview").appendChild(elem);
        
        return elem;
    }
    
    function replace_image_layer(image, item_id, image_id, previous_elem_id){
        
        
        var new_id = "option_" + item_id + "_" + image_id;
                        
        $(previous_elem_id).fadeOut(300, function(){
            
            $(this).attr('src', image).bind('onreadystatechange load', function(){
               if (this.complete){
                   
                   $(this).fadeIn(300);
                   console.log('Fabric Changed');
               }
            });
            
            $(this).attr("id", new_id);
        });
    }
        
    /**
    * This function chooses the fabric that should be used for a menu
    * design image
    * @param {type} option_id
    * @param {type} image_id
    * @param {type} type
    * @returns {undefined}     */    
    function choose_fabric(menu_index, image_id){
        
        var use_fabric = default_fabric;
        
        var menu_data = current_design_data['design'][menu_index];
                  
        if(menu_data.option !== null){

            var option_data = menu_data.option;
            
            if(parseInt(menu_data.mixed_fabric_support) === 1){
                
                for(var image in option_data["images"]){
                    
                    console.log('Selecting fabric for menu : ' + menu_index);
                    console.log(mix_fabric_check_status);
                    
                    var design_image = option_data["images"][image];
                    
                    if(parseInt(image) === parseInt(image_id)){
                        
                        
                        if(parseInt(design_image.is_inner) === 1){
                            
                            if(mix_fabric_check_status['mix_design_inner_' + menu_index] && parseInt(menu_data.inner_contrast_support) === 1){
                                
                                use_fabric = mix_fabric;
                                
                                console.log('Mix fabric chosen for inner mix');
                            }
                        }
                        else{
                                                        
                            if(mix_fabric_check_status['mix_design_outer_' + menu_index]){
                                                                
                                use_fabric = mix_fabric;
                                
                                console.log('Mix fabric chosen for outer mix');
                            }
                        }
                    }
                }
            }
  
        }
        
        return use_fabric;
    }
    
    function option_selected(option_id, image_id, type){
                
       var design_mix_checked_val;
           
       if(parseInt(category_index) === 3 && typeof type !== 'undefined'){
                      
           var selected_menu = option_id;
           
           var id = 'mix_design_' + type + '_' + selected_menu;
           
           design_mix_checked_val = $("#" + id).prop('checked');
                                                                                               
           if(design_mix_checked_val){
               
               mix_fabric_check_status[id] = true;
               
               console.log("Checked Menu");
                                                                          
           }
           else{
               
               mix_fabric_check_status[id] = false;
               
               console.log("Unchecked Menu");
                                             
           }
           
           apply_mix_fabrics();
           
           return;           
       }
                            
       var xmlhttp = new XMLHttpRequest();
              
       xmlhttp.onreadystatechange = function() {
		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                                
                var newOption =  JSON.parse(xmlhttp.responseText);
                
                var previousOption = current_design_data['design'][menu_index];
                                                
                if(parseInt(category_index) === 2){
                    
                    if(current_design_data['design'][menu_index].option === null)
                        return;

                    if(current_design_data['design'][menu_index].option !== null && parseInt(newOption["id"]) === parseInt(current_design_data['design'][menu_index].option.id)){

                        return;
                    }

                    ReplaceDesignOption(previousOption, newOption);                                                                                                                                                             

                }   
                else if(parseInt(category_index) === 1){
                                        
                    default_fabric = newOption;
                                       
                    LoadParametersToPreview();
                    
                }
                else if(parseInt(category_index) === 3){
                                        
                    mix_fabric = newOption;
                                        
                    apply_mix_fabrics();
                                        
                }
                

            }
	};
        
        var site_url = '';

        if(parseInt(category_index) === 1 || parseInt(category_index) === 3){
        
             site_url = "<?php echo base_url("index.php/admin/get_fabric"); ?>";   
             
             site_url = site_url.concat("/").concat(option_id);
        }
        
        if(parseInt(category_index) === 2){
            
            console.log("Fetching option : " + option_id);
            
             site_url = "<?php echo base_url("index.php/admin/get_option"); ?>";
             
             site_url = site_url.concat("/").concat(option_id);

        }
        
        xmlhttp.open("GET", site_url, true);

        xmlhttp.send();
       
    }
    
    function ReplaceDesignOption(previousOption, newOption){
        
        var buttons_dir = "<?php echo ASSETS_PATH; ?>".concat("images/buttons/");
        
        var image_dir = "<?php echo ASSETS_PATH; ?>".concat("images/design/");
        
        // Clear Canvas Buffer
        canvas_images = [];
        
        RemoveOptionButtons(previousOption);
                                                    
        var prev_option_id = previousOption.option.id;

        // Set new option in design data
        current_design_data['design'][menu_index].option = newOption;

        var previousOptionElement = $(".preview-image.option_class_".concat(prev_option_id));
        
        for(var image in newOption["images"]){
                        
            var image_data = newOption["images"][image];

            if(current_side === 'front' && parseInt(image_data.is_back_image) === 1){

                continue;
            }

            if(current_side === 'back' && parseInt(image_data.is_back_image) === 0){

                continue;
            }

            var image_path = image_dir.concat(image_data.name);

            var new_image_id = image_data.id;

            var depth = image_data.depth;

            var use_fabric = choose_fabric(menu_index, new_image_id);

            if("name" in use_fabric){

                image_data.fabric_id = use_fabric["id"];

                var blend_path = "<?php echo ASSETS_PATH; ?>".concat("images/product/fabric/") +  use_fabric["name"];

                var data = {

                    image_url: image_path,
                    blend_url : blend_path,
                    id : current_design_data['design'][menu_index].option.id,
                    image_id : new_image_id,
                    depth : depth,
                    pos_x : image_data.pos_x,
                    pos_y : image_data.pos_y
                };

            }
            else{

                var data = {

                    image_url: image_path,
                    id : current_design_data['design'][menu_index].option.id,
                    image_id : new_image_id,
                    depth : depth,
                    pos_x : image_data.pos_x,
                    pos_y : image_data.pos_y
                };

            }

            canvas_images.push(data);

            for(var button_key in image_data.buttons){

                var button_data = image_data.buttons[button_key];

                var button_path = buttons_dir  + button_data.image_name;

                var bdata = {

                    image_url: button_path,
                    id : button_data.id,
                    button_item_id : button_data.item_id,
                    image_id : -1,
                    depth : button_data.depth,
                    pos_x : button_data.pos_x,
                    pos_y : button_data.pos_y
                };

                canvas_images.push(bdata);
            }

        }

        replaceOptionElementWithCanvasImages(previousOptionElement);
    
    }
        
    /**
    * This function loads options when a menu is selected. 
    * This is only done when the category selected is not the Design Mix catefory
    * @param {type} submenu_id
    * @returns {undefined}     */
    function LoadOptions(menu_id){
	        
        menu_index = parseInt(menu_id);
                
        if(parseInt(category_index) === 2){
            
            if(parseInt(current_design_data['design'][menu_index].is_back_menu) === 1 && current_side === 'front'){
                
                current_side = 'back';
                
                LoadParametersToPreview();
            }
            
            if(parseInt(current_design_data['design'][menu_index].is_back_menu) === 0 && current_side === 'back'){
                
                current_side = 'front';
                
                LoadParametersToPreview();
            }
        }
                
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200 && parseInt(category_index) !== 3) {

                var json_array =  JSON.parse(xmlhttp.responseText);
                                
                document.getElementById('option-list').innerHTML = "";

                for (var key in json_array) {
                                                           
                    var image_element = document.createElement("img");                 

                    var image_path = '';
                                                         
                    if(parseInt(category_index) === 1){
                        
                         image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/product/fabric/').concat(json_array[key]['name']);                        
                    }
                    else{

                         image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/design/thumbnail/').concat(json_array[key]['image_data']['thumbnail']['name']);

                    }

                    image_element.setAttribute("src", image_path);  

                    image_element.setAttribute("height", "100");
                    
                    image_element.setAttribute("width", "96");

                    var link_element = document.createElement("a");
                    
                    link_element.appendChild(image_element);

                    var list_element = document.createElement("li");
                    
                    var function_string = "";
                    
                    if(parseInt(category_index) === 1)
                    {
                        function_string = "option_selected(" + json_array[key]['item_id'] + ", 0)";
                    }
                    
                    if(parseInt(category_index) === 2)
                    {
                        function_string = "option_selected(" + json_array[key]['id'] + ", 0)";
                    }
                                        
                    list_element.setAttribute("onclick", function_string);
                    
                    list_element.appendChild(link_element);

                    document.getElementById("option-list").appendChild(list_element);

                    sly.reload();                

                }


            }
	};
        
        var site_url = "<?php echo base_url("index.php/admin/get_product_design_options"); ?>";
        	
	site_url = site_url.toString() + "/" + menu_id.toString() + "/" + category_index + "/" + product_id ;
		
	xmlhttp.open("GET", site_url, true);
	
	xmlhttp.send();
    }
    
    /**
     * This function is called when the user clicks on the Design Mix Category. 
     * It loads all the fabrics and the user can choose from any of them
     * @param {type} selected_category
     * @returns {undefined}
     */
    function LoadAllFabrics(submenu_id){
	        
        submenu_index = submenu_id;
                
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                                              
                var json_array =  JSON.parse(xmlhttp.responseText);
                                                
                document.getElementById('option-list').innerHTML = "";

                for (var key in json_array) {
                    
                    var fabric = json_array[key];
                    
                    console.log("Loading Fabric Option : ");
                    console.log(fabric);
                    
                    var image_element = document.createElement("img");                 

                    var image_path = '';
                    
                    image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/product/fabric/').concat(fabric.name);
                                        
                    image_element.setAttribute("src", image_path);  
                     
                    image_element.setAttribute("height", "100");
                    
                    image_element.setAttribute("width", "96");

                    var link_element = document.createElement("a");
                    link_element.appendChild(image_element);

                    var list_element = document.createElement("li");

                    var function_string = "option_selected(" + fabric.item_id + ", " + fabric.id + ")";
                    
                    list_element.setAttribute("onclick", function_string);
                    
                    list_element.appendChild(link_element);

                    document.getElementById("option-list").appendChild(list_element);

                    sly.reload();                 

                }


            }
	};
        
        var site_url = "<?php echo base_url("index.php/admin/get_all_product_fabrics"); ?>";
               
        site_url = site_url + "/" + product_id.toString();
        
	xmlhttp.open("GET", site_url, true);
	
	xmlhttp.send();
    }
    
    /**
     * This function helps in the creation of the checkbox options list
     * when the user selects the design mix menu
     * @param {type} selected_category
     * @returns {undefined}
     */
    function createCheckboxDiv(id, name, type) {
        
        var container = $('<div>').prop('class', 'list-group-item' + ' ' + type).prop('id', id);
        
        if(!mix_fabric_check_status.hasOwnProperty('mix_design_' + type + '_' + id)){
            
            mix_fabric_check_status['mix_design_' + type + '_' + id] = false;
            
        }
        
        $('<input />', 
        { checked : mix_fabric_check_status['mix_design_' + type + '_' + id], 
            type: 'checkbox', 
            id: 'mix_design_' + type + '_' + id, 
            value: name, 
            style : 'margin-right : 5px;', 
            onchange : 'option_selected(' + id + ', 0, "' + type + '")' }).appendTo(container);
        
        $('<label />', { for: id, text: name }).appendTo(container);
        
        return container;
    }
    
    /**
     * WHen a category is selected, this function is called to load 
     * its menus appropriately
     * @param {type} id
     * @param {type} name
     * @returns {undefined}
     */
    function LoadMenus(selected_category) {
	
        // Here we shall load the two different options for measurement
        if(parseInt(selected_category) === 4){
              
            document.getElementById('option-list').innerHTML = "";
            
            $('#sub_menu_list').empty();
                                    
            $('#sub_menu_list').append(
                $('<a>').attr("data-toggle", "modal").attr("data-target", "#myMeasurementModal").attr('href', '#').attr('class', 'list-group-item').append(
                    $('<span>').attr('class', 'tab').append("Enter Measurements")
            )); 
    
            $('#sub_menu_list').append(
                $('<a>').attr("data-toggle", "modal").attr("data-target", "#requestMeasurementModal").attr('href', '#').attr('class', 'list-group-item').append(
                    $('<span>').attr('class', 'tab').append("Request Tailor")
            ));
            
            return;
        }
        
        if(parseInt(selected_category) === 6){
            
            var temp_fabric = default_fabric;
            
            default_fabric = mix_fabric;
            
            mix_fabric = temp_fabric;
            
            LoadParametersToPreview();
            
            return;
        }
        
        category_index = selected_category;
        
        document.getElementById('option-list').innerHTML = "";
        
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() {
		
            if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                var json_array =  JSON.parse(xmlhttp.responseText);
                
                $('#sub_menu_list').empty();
                
                for (var key in json_array) {
                     
                    if(parseInt(category_index) === 3){
                                                  
                        var outer_div = createCheckboxDiv(json_array[key]['id'], 'Outer ' + json_array[key]['name'], 'outer');                      
                        if(Boolean(parseInt(json_array[key]['mixed_fabric_support']))){
                            
                            $('#sub_menu_list').append(
                                    
                                outer_div    
                            );
                            
                                                        
                            if(Boolean(parseInt(json_array[key]['inner_contrast_support']))){
                                
                                var inner_div = createCheckboxDiv(json_array[key]['id'], 'Inner ' + json_array[key]['name'], 'inner'); 
                                
                                $('#sub_menu_list').append(
                                    
                                    inner_div    
                                );
                            }
                        }
                    }
                    else if (parseInt(category_index) === 1 || parseInt(category_index) === 2){
                                                
                        var on_click = 'return LoadOptions('.concat(json_array[key]['id']).concat(');');
                        
                        $('#sub_menu_list').append(
                            $('<a>').attr('href', '#').attr('value', json_array[key]['id']).attr('onclick', on_click).attr('class', 'list-group-item').append(
                                $('<span>').attr('class', 'tab').append(json_array[key]['name'])
                        ));
                    }
                    else if(parseInt(category_index) === 5){
                        
                        var button_data = json_array[key];
                        
                        console.log(button_data);
                                                
                        var image_element = document.createElement("img");                 

                        var image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/buttons/').concat(button_data.image_name);                        

                        image_element.setAttribute("src", image_path);  

                        image_element.setAttribute("height", "100");

                        image_element.setAttribute("width", "96");

                        var link_element = document.createElement("a");
                        
                        // Handles opening the Modal dialog for buttons
                        link_element.setAttribute("data-toggle", "modal");
                        link_element.setAttribute("data-target", "#buttonsModal");
                        
                        link_element.appendChild(image_element);

                        var list_element = document.createElement("li");

                        list_element.appendChild(link_element);
                        
                        var function_string = "ButtonSelected(" + parseInt(button_data.id) + ")";
                   
                        list_element.setAttribute("onclick", function_string);

                        document.getElementById("option-list").appendChild(list_element);

                        sly.reload();
                        
                        
                        
                    }
                                        
                }
                
                if(parseInt(category_index) === 3){
                    
                    
                    LoadAllFabrics();
                
                }

            }
	};
        
        var site_url = "";
        
        
        if(parseInt(category_index) === 5){
        
            site_url = "<?php echo base_url("index.php/admin/get_buttons"); ?>";
		
        }
        else{
        
            site_url = "<?php echo base_url("index.php/admin/get_product_design_menus"); ?>";
		
            site_url = site_url.concat("/").concat(<?php  echo $product_id;  ?>).concat("/").concat(category_index);
        }
        
		
	xmlhttp.open("GET", site_url, true);
	
	xmlhttp.send();
    
    }
    
    function load_measurement_data(id, youtube_link, description){
        
        if(parseInt($("#youtube_frame").val()) !== parseInt(id)){
                    
            $("#youtube_frame").prop("src", youtube_link);
            $("#youtube_frame").val(id);
            $("#measurement_description").text(description);
        }
    }
    
    function measurement_changed(element){
        
        var measurement_id = parseInt(element.id.replace("measurement_", ""));
        
        design_data['measurements'][measurement_id].value = element.value;
        
    }
        
    function LoadMeasurementsIntoModal(){
        
         $("#my_measurements").empty();
        
        for(var key in design_data['measurements']){
            
            var measurement = design_data['measurements'][key];
            
            $("#my_measurements").append(
                $('<tr>').append(
                    $('<td>').append(                       
                        $('<div>').attr('class', 'form-group')
                        .append(
                            $('<label>').attr('for', 'measurement_' + measurement.id).text(measurement.name)
                        )
                        .append(
                            $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control')
                            .attr('id', 'measurement_' + measurement.id)
                            .attr('onmousedown', 'load_measurement_data(' + measurement.id + ', "' + measurement.youtube_link + '", "' + measurement.description + '")' )
                            .attr('value', measurement.value)
                            .attr('onchange', 'measurement_changed(this)')
                        )
                    )
                )
            );
        }           
    }
    
    function LoadUserDataIntoModal(){
       
       var user = design_data['user'];
       
       $("#contact_name").val(user.last_name + " " + user.first_name);
       
       $("#contact_phone").val(user.phone_number);
       
       $("#address_line_01").val(user.address_line_01);
       
       $("#address_line_02").val(user.address_line_02);
       
       $("#postal_code").val(user.postal_code);
       
       $("#email").val(user.email);
       
    }
    
    /**
    * This function loads all available threads to the 
    * threads slider in the modal buttons dialog. 
    * @returns {undefined}     */
    function LoadThreadsToSly(){
                
        for (var key in Threads) {

            var ThreadData = Threads[key];
            
            var image_element = document.createElement("img");                 

            var image_path = '<?php echo ASSETS_PATH; ?>'.concat('images/threads/').concat(ThreadData.image_name);

            image_element.setAttribute("src", image_path);  

            image_element.setAttribute("class", "sly-list-item");

            var link_element = document.createElement("a");

            link_element.appendChild(image_element);

            var list_element = document.createElement("li");

            var function_string = "ThreadSelected(" + ThreadData.id + ")";

            list_element.setAttribute("onclick", function_string);

            list_element.appendChild(link_element);
            
            document.getElementById("button-design-threads-list").appendChild(list_element);
            
            console.log("Added Thread : " + ThreadData.image_name);                

        }
        
        threadsSly.reload();
    }
    
    function user_data_changed(){
        
        var user = design_data['user'];
        
        user.phone_number = $("#contact_phone").val();
        
        user.address_line_01 = $("#address_line_01").val();
        
        user.address_line_02 = $("#address_line_02").val();
        
        user.postal_code = $("#postal_code").val();
        
        user.email = $("#email").val();
    }
    
    /**
    * Load buttons to product using the 
    * currentButton and CurrentThread
    * @returns void     */
    function LoadButtons(){
        
        canvas_images = [];
                        
        var buttons_dir = "<?php echo ASSETS_PATH; ?>".concat("images/buttons/");
                            
        for(var image_key in current_design_data[current_side + '_images']){
            
            var base_image = current_design_data[current_side + '_images'][image_key];
            
            for(var button_key in base_image.buttons){
                        
                var button_data = base_image.buttons[button_key];

                var button_path = buttons_dir  + button_data.image_name;

                var bdata = {

                    image_url: button_path,
                    id : button_data.id,
                    image_id : -1,
                    button_item_id : button_data.item_id,
                    depth : button_data.depth,
                    pos_x : button_data.pos_x,
                    pos_y : button_data.pos_y
                };

                canvas_images.push(bdata);
            }

        }
        
        LoadDesignOptionButtons();
                     
    }
    
    /**
     * This function is called when a button is selected
     * @param int button_id
     * @returns void
     */
    function ButtonSelected(button_id){
        
        var buttons_dir = "<?php echo ASSETS_PATH; ?>".concat("images/buttons/");
        
        var buttonData = GetButton(button_id);
        
        var buttonImage = buttons_dir + buttonData.design_image_name;
        
        $("#design_button_image").attr("src", buttonImage);
        
        $("#selected-button-name").text(buttonData.name);
    }
    
    /**
     * THis function is called when a thread is selected and applies the 
     * thread to the current button
     * @type Threads|Array|Object|type
     */
    function ThreadSelected(thread_id){
        
        var threads_dir = "<?php echo ASSETS_PATH; ?>".concat("images/threads/");
        
        var threadData = GetThread(thread_id);
        
        var threadColor = threads_dir + threadData.color;
        
        ApplyThreadToButton(threadColor);
        
        // Apply thread here
        
    }
    
    /**
     * Given a button ID, this funtion searches the list of buttons 
     * and returns the button object
     * @returns ButtonData
     */
    function GetButton(button_id){
        
        console.log("GetButton()");

        for(var key in Buttons){
            
            var buttonData = Buttons[key];
            
            console.log(buttonData);
            
            if(parseInt(buttonData.id) === parseInt(button_id)){
                
                CurrentButton = buttonData;
                
                return buttonData;
            }
                
        }
    }
    
    /**
     * Given a thread ID, this funtion searches the list of treads 
     * and returns the thread object
     * @returns ButtonData
     */
    function GetThread(thread_id){
        
        console.log("GetThread()");

        for(var key in Threads){
            
            var threadData = Threads[key];
                        
            if(parseInt(threadData.id) === parseInt(thread_id)){
                
                CurrentThread = threadData;
                
                return threadData;
            }
                
        }
    }
    
    /**
     * Loads buttons for different options
     * @returns {undefined}
     */
    function LoadDesignOptionButtons(){
        
        var buttons_dir = "<?php echo ASSETS_PATH; ?>".concat("images/buttons/");
        
        for (var menu in current_design_data['design']) {
                    
            if(current_design_data['design'][menu].option !== null){

                var design_option = current_design_data['design'][menu].option;

                for(var image in design_option["images"]){
                                      
                    var design_image = design_option["images"][image];
                    
                    if(current_side === 'front' && parseInt(design_image.is_back_image) === 1){
                        
                        continue;
                    }
                    
                    if(current_side === 'back' && parseInt(design_image.is_back_image) === 0){
                        
                        continue;
                    }
                     
                    for(var button_key in design_image.buttons){
                        
                        var button_data = design_image.buttons[button_key];
                        
                        var button_path = buttons_dir  + button_data.image_name;

                        var bdata = {

                            image_url: button_path,
                            id : button_data.id,
                            button_item_id : button_data.item_id,
                            image_id : -1,
                            depth : button_data.depth,
                            pos_x : button_data.pos_x,
                            pos_y : button_data.pos_y
                        };
                                                                       
                        canvas_images.push(bdata);
                    }

                }
            }

        }
        
        BlendButtonImages();
    }
    
    /**
    * Removes all current buttons
    * @returns {undefined}     */
    function ClearButtons(){
        
        $("img").remove(".buttons");
    }
    
    function ApplyThreadToButton(threadColor){
        
        
    }
    

</script>

<!-- Design Page -->
<div class='design-page'>
    <div id='' class='container'>

        <div id='' class="row">

            <!-- MAIN MENUS  -->

            <div id='' class=' design-menu col-sm-4'>
                <div id='main_menu' class='design-menu-header'>Design Menu</div>
                <div id='main_menu_list' class="list-group">
                    <?php foreach ($categories->result() as $category) {?>
                    <?php if($category->id != 4){ ?>
                    <a  value="<?php echo $category->id; ?>" onclick="LoadMenus(<?php echo $category->id; ?>)" href="#" class="list-group-item"><?php echo $category->name; ?></a>
                    <?php } else { ?>
                    <a value="<?php echo $category->id; ?>" onclick="LoadMenus(<?php echo $category->id; ?>)" href="#" class="list-group-item"><?php echo $category->name; ?></a>                    
                    <?php } ?>
                    <?php }?>				
                </div>
            </div>

            <div id='sub_menu_list' class="col-sm-2">

            </div>

            <div id='design-preview' class='design-preview  col-sm-6'>

            </div>

        </div>

        <!-- END MAIN MENUS  -->

        <div class="wrap">  
                <div class="scrollbar">
                    <div class="handle">
                            <div class="mousearea"></div>
                    </div>
                </div>

                <div class="frame" id="design_options">
                    <ul class="clearfix" id="option-list">

                    </ul>
                </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div id="myMeasurementModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Measurements</h4>
      </div>
      <div class="modal-body">
          <div class="row">
              <!-- Contains a Scrollable list of all measurements  -->
              <div class="col-sm-4" style="height: 400px; overflow-y: auto;">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Set Measurement</th>
                      </tr>
                    </thead>
                    <tbody id="my_measurements">
                        
                    </tbody>
                  </table>    
              </div>
              <div class="col-sm-8">
                  <iframe id="youtube_frame" src="" value="-1"  style="width: 100%; height: 300px; padding: 2px; background-color: gray;">
                      
                  </iframe>
                  <div>
                      <p id="measurement_description">
                          
                      </p>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="requestMeasurementModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Request Tailor for Measurement</h4>
      </div>
      <div class="modal-body">
          <div style="width: 100%">
              <div class="form-group">
                <label for="contact_name">Contact Name:</label>
                <input type="text" class="form-control" id="contact_name" readonly>
              </div>
              <div class="form-group">
                <label for="contact_phone">Phone Number:</label>
                <input type="tel" class="form-control" id="contact_phone" onchange="user_data_changed()">
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" onchange="user_data_changed()">
              </div>
              <div class="form-group">
                <label for="address_line_01">Address:</label>
                <input type="text" class="form-control" id="address_line_01" onchange="user_data_changed()">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" id="address_line_02" onchange="user_data_changed()">
              </div>
              <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" class="form-control" id="postal_code" onchange="user_data_changed()">
              </div>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="buttonsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="selected-button-name">Button Selected Name Here</h4>
            </div>
            
            <div class="modal-body">
                
                <div id="button_selected">
                    <img id="design_button_image" class="button-design-image"/>
                </div>

                <div class="wrap">  

                    <div class="scrollbar">
                        <div class="handle">
                            <div class="mousearea">

                            </div>
                        </div>
                    </div>

                    <div class="frame" id="button-design-threads">
                        <ul class="clearfix" id="button-design-threads-list">

                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            
        </div>
    </div>
</div>

</html>
