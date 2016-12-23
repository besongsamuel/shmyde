
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
     * THis represents the images that will be added to the canvas. 
     * @type Array
     */
    var canvas_images = [];
    
    
    /****************************************************************************************************************************************/
    var design_data;
    
    var current_side = 'front';
    
    var current_design_data;
        
    var tmpThread = null;
    
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
    
    var button_image;
    
    var tmp_button_image;
               
    $(document).ready(function(){
         
        $('#myMeasurementModal').on('shown.bs.modal', function() {
            
            LoadMeasurementsIntoModal();
        });
        
        $('#userDataModal').on('shown.bs.modal', function() {
            
            LoadUserDataIntoModal();
        });
        
        $('#buttonsModal').on('shown.bs.modal', function() {
            
            LoadThreadsToSly();;
        });

        design_data = <?php echo $design_data;  ?>;
        
        current_design_data = design_data['design'];
                                                        
        product_id = <?php echo json_encode($product_id); ?>;
                
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

            if("name" in design_data.defaultFabric){

                var blend_path = base_image_dir + "fabric/" + design_data.defaultFabric['name'];

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
     * This functon gets an array list containing the id's of all 
     * currentlu selected options
     * @type Arguments
     */
    function GetSelectedOptionsList(){
        
        var selected_list = [];
        
        for (var menu in current_design_data['design']) {
                    
            if(current_design_data['design'][menu].option !== null){

                var design_option = current_design_data['design'][menu].option;

                selected_list.push(design_option.id);
            }

        }
        
        return selected_list;
        
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
                                                
                if(!design_data.currentButton || !design_data.currentThread ){
                    
                    button_image = "<?php echo ASSETS_PATH; ?>".concat("images/buttons/") + design_data.currentButton.image_name;
                
                    LoadButtons(button_image);
                }
                else{
                    
                    $.post('<?= site_url("Design/BlendButtonThread/"); ?>',{ currentButton : JSON.stringify(design_data.currentButton), threadColor : design_data.currentThread.color  },function(json_result){

                        var image_data = JSON.parse(json_result);

                        $("#design_button_image").attr("src", image_data.design_image_name);

                        button_image = image_data.image_name;

                        LoadButtons(button_image);

                    });
                }

            });
            
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
    function ApplyMixFabric(){
         
        if(!("name" in design_data.mixFabric)){
            
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
                                                   
                        if(((parseInt(design_image.is_inner) === 0 && $.inArray(parseInt(menu), design_data.SelectedOuterMix) !== -1) 
                            || (parseInt(design_image.is_inner) === 1 && parseInt(menu_data.inner_contrast_support)=== 1 && $.inArray(parseInt(menu), design_data.SelectedInnerMix) !== -1))
                            && parseInt(design_image.fabric_id) !== parseInt(design_data.mixFabric['id'])) 
                        {
                                          
                            design_image.fabric_id = parseInt(design_data.mixFabric['id']);

                            var blend_path = base_image_dir + "fabric/" + design_data.mixFabric['name'];

                            var curr_elem_id = "#option_".concat(option_id) + "_" + image_id;
                            
                            console.log("Changing fabric for " + design_image + " : " + curr_elem_id);

                            $.post('<?= site_url("Design/BlendImage/"); ?>',{image_url :  image_path, blend_url : blend_path, id : option_id, image_id : image_id, element_id : curr_elem_id },function(dataurl){

                                var data = JSON.parse(dataurl);
                                                              
                                replace_image_layer(data.image, data.id, data.image_id, data.element_id);

                            });
                        }

                        if(((parseInt(design_image.is_inner) === 0 && $.inArray(parseInt(menu), design_data.SelectedOuterMix) === -1) 
                            || (parseInt(design_image.is_inner) === 1 && parseInt(menu_data.inner_contrast_support) === 1 && $.inArray(parseInt(menu), design_data.SelectedInnerMix) === -1))
                            && parseInt(design_image.fabric_id) !== parseInt(design_data.defaultFabric['id']))
                        {

                            design_image.fabric_id = parseInt(design_data.defaultFabric['id']);

                            var blend_path = base_image_dir + "fabric/" + design_data.defaultFabric['name'];

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
        $(".design-preview").append($(elem));
        
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
        $('.design-preview').append($(elem));
        
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
        
        var use_fabric = design_data.defaultFabric;
        
        var menu_data = current_design_data['design'][menu_index];
                  
        if(menu_data.option !== null){

            var option_data = menu_data.option;
            
            if(parseInt(menu_data.mixed_fabric_support) === 1){
                
                for(var image in option_data["images"]){
                    
                    console.log('Selecting fabric for menu : ' + menu_index);
                    
                    var design_image = option_data["images"][image];
                    
                    if(parseInt(image) === parseInt(image_id)){
                        
                        if(parseInt(design_image.is_inner) === 1){
                            
                            if($.inArray(parseInt(menu_index), design_data.SelectedInnerMix) !== -1 && parseInt(menu_data.inner_contrast_support) === 1)
                            {
                                use_fabric = design_data.mixFabric;                               
                                console.log('Mix fabric chosen for inner mix');
                            }
                        }
                        else{
                                                        
                            if($.inArray(parseInt(menu_index), design_data.SelectedOuterMix) !== -1)
                            {                               
                                use_fabric = design_data.mixFabric;
                                console.log('Mix fabric chosen for outer mix');
                            }
                        }
                    }
                }
            }
  
        }
        
        return use_fabric;
    }
    
    /**
     * 
     * @param {type} option_id
     * @param {type} type
     * @returns {undefined}
     */
    function option_selected(option_id, type){
       
       MixDesignOptionSelected(option_id, type);
       
       DesignOptionSelected(option_id);
       
       FabricSelected(option_id);
                           
    }
    
    function MixDesignOptionSelected(option_id, type){
    
        if(parseInt(category_index) !== 3)
            return;
        
        var design_mix_checked_val;
        
        
        // This section is called when a mix is checked or unchecked
        if(typeof type !== 'undefined'){

            var selected_menu = option_id;

            design_mix_checked_val = $("#design_mix_" + type + "_" + selected_menu).prop('checked');
            
            if(design_mix_checked_val){
                
                if(type.toString() === 'inner')
                {

                    if (!design_data.SelectedInnerMix)
                    {
                        design_data.SelectedInnerMix = [];
                    }
                    
                    design_data.SelectedInnerMix.push(parseInt(selected_menu));
                }
                else
                {                  
                    
                    if (!design_data.SelectedOuterMix)
                    {
                        design_data.SelectedOuterMix = [];
                    }
                    
                    design_data.SelectedOuterMix.push(parseInt(selected_menu));
                }
                
                console.log("Checked Menu");

            }
            else{
                
                if(type.toString() === 'inner')
                {
                    
                    
                    var index = design_data.SelectedInnerMix.indexOf(parseInt(selected_menu));
                    
                    if (index > -1) 
                    {
                        design_data.SelectedInnerMix.splice(index, 1);
                    }

                }
                else
                {
                    
                    var index = design_data.SelectedOuterMix.indexOf(parseInt(selected_menu));
                    
                    if (index > -1) 
                    {
                        design_data.SelectedOuterMix.splice(index, 1);
                    }
                }
                
                console.log("Unchecked Menu");
            }

            ApplyMixFabric();

            return;           
        }
        
        var xmlhttp = new XMLHttpRequest();
              
        xmlhttp.onreadystatechange = function() {

            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) 
            {
                var newOption =  JSON.parse(xmlhttp.responseText);

                design_data.mixFabric = newOption;

                ApplyMixFabric();

             }
         };

        
        var site_url = "<?php echo site_url("Design/get_fabric"); ?>";   

        site_url = site_url.concat("/").concat(option_id);
              
        xmlhttp.open("GET", site_url, true);

        xmlhttp.send();

    }
    
    function DesignOptionSelected(option_id){
        
        if(parseInt(category_index) !== 2)
            return;
        
        var xmlhttp = new XMLHttpRequest();
              
        xmlhttp.onreadystatechange = function() {
		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                                
                var newOption =  JSON.parse(xmlhttp.responseText);
                
                var previousOption = current_design_data['design'][menu_index];

                if(current_design_data['design'][menu_index].option === null)
                    return;

                if(current_design_data['design'][menu_index].option !== null && parseInt(newOption["id"]) === parseInt(current_design_data['design'][menu_index].option.id))
                {
                    return;
                }

                ReplaceDesignOption(previousOption, newOption);                                                                                                                                                             

            }
	};
        
        console.log("Fetching option : " + option_id);

        var site_url = "<?php echo site_url("Design/get_option"); ?>";

        site_url = site_url.concat("/").concat(option_id);

        xmlhttp.open("GET", site_url, true);

        xmlhttp.send();
    }
    
    function FabricSelected(option_id){
        
        if(parseInt(category_index) !== 1)
            return;
        
        var xmlhttp = new XMLHttpRequest();
              
        xmlhttp.onreadystatechange = function() {
		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                                
                var newOption =  JSON.parse(xmlhttp.responseText);
                       
                design_data.defaultFabric = newOption;

                LoadParametersToPreview();

            }
	};

        var site_url = "<?php echo site_url("Design/get_fabric"); ?>";   
             
        site_url = site_url.concat("/").concat(option_id);

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
        
        // Reload Menus only after the new option has been set
        LoadDesignMenus(category_index, false);

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
                        function_string = "option_selected(" + json_array[key]['item_id'] + ")";
                    }
                    
                    if(parseInt(category_index) === 2)
                    {
                        function_string = "option_selected(" + json_array[key]['id'] + ")";
                    }
                                        
                    list_element.setAttribute("onclick", function_string);
                    
                    list_element.appendChild(link_element);

                    document.getElementById("option-list").appendChild(list_element);

                    sly.reload();                

                }


            }
	};
        
        var site_url = "<?php echo site_url("Design/get_product_design_options"); ?>";
        	
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

                    var function_string = "option_selected(" + fabric.item_id + ")";
                    
                    list_element.setAttribute("onclick", function_string);
                    
                    list_element.appendChild(link_element);

                    document.getElementById("option-list").appendChild(list_element);

                    sly.reload();                 

                }


            }
	};
        
        var site_url = "<?php echo site_url("Design/get_all_product_fabrics"); ?>";
               
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
        
        var value = false;
        
        if(type.toString() === 'inner')
        {
            if($.inArray(parseInt(id), design_data.SelectedInnerMix) !== -1)
            {           
                value = true;            
            }  
        }
        else
        {
            if($.inArray(parseInt(id), design_data.SelectedOuterMix) !== -1)
            {           
                value = true;           
            }
        }
        
        
        
        $('<input />', 
        { checked : value, 
            type: 'checkbox', 
            id: "design_mix_" + type + "_" + id, 
            value: name, 
            style : 'margin-right : 5px;', 
            onchange : 'option_selected(' + id + ', "' + type + '")' }).appendTo(container);
        
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
	
        category_index = selected_category;
        
        InvertFabric(selected_category);
        
        LoadMeasurementMenus(selected_category);
        
        LoadDesignMenus(selected_category, true);
        
        LoadButtonOptions(selected_category);
        
        LoadFabricMenus(selected_category);
        
        LoadMixFabricMenus(selected_category);

    }
    
    function LoadMixFabricMenus(selected_category) {
	
        if(parseInt(selected_category) !== 3)
            return;
        
        document.getElementById('option-list').innerHTML = "";
        
        var site_url = "";
        
        site_url = "<?php echo site_url("Design/get_product_design_menus"); ?>";
        
        $.post(site_url,{ selected_option_list : JSON.stringify(GetSelectedOptionsList()), product_id : <?php  echo $product_id;  ?>  },function(json_result){

            var json_array =  JSON.parse(json_result);
            
            $('#sub_menu_list').empty();
                
            for (var key in json_array) {
                                                 
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

            LoadAllFabrics();   
                   
        });
        
    }
    
    function LoadDesignMenus(selected_category, clearOptions) {
	                
        if (parseInt(selected_category) !== 2)
            return;
        
        if(clearOptions)
            document.getElementById('option-list').innerHTML = "";
                
        var site_url = "<?php echo site_url("Design/get_product_design_menus/"); ?>";
        
        $.post(site_url,{ selected_option_list : JSON.stringify(GetSelectedOptionsList()), product_id : <?php  echo $product_id;  ?>  },function(json_result){

            var json_array =  JSON.parse(json_result);
            
            $('#sub_menu_list').empty();
                
                for (var key in json_array) {
                                               
                    var on_click = 'return LoadOptions('.concat(json_array[key]['id']).concat(');');

                    $('#sub_menu_list').append(
                        $('<a>').attr('href', '#').attr('value', json_array[key]['id']).attr('onclick', on_click).attr('class', 'list-group-item').append(
                            $('<span>').attr('class', 'tab').append(json_array[key]['name'])
                    ));
                                  
            }
            
            
                    
        });

    }
    
    function LoadMeasurementMenus(selected_category){
        
        if(parseInt(selected_category) !== 4)
            return;
        
        document.getElementById('option-list').innerHTML = "";

        $('#sub_menu_list').empty();

        $('#sub_menu_list').append(
            $('<a>').attr("data-toggle", "modal").attr("data-target", "#myMeasurementModal").attr('href', '#').attr('class', 'list-group-item').append(
                $('<span>').attr('class', 'tab').append("Enter Measurements")
        )); 

        $('#sub_menu_list').append(
            $('<a>').attr("data-toggle", "modal").attr("data-target", "#userDataModal").attr('href', '#').attr('class', 'list-group-item').append(
                $('<span>').attr('class', 'tab').append("Request Tailor")
        ));

    }
    
    function InvertFabric(selected_category){
        
        if(parseInt(selected_category) !== 6)
            return;
                 
        var temp_fabric = design_data.defaultFabric;

        design_data.defaultFabric = design_data.mixFabric;

        design_data.mixFabric = temp_fabric;

        LoadParametersToPreview();

        return;
        
    }
    
    function LoadButtonOptions(selected_category){
	
        if(parseInt(selected_category) !== 5)
            return;
        
        document.getElementById('option-list').innerHTML = "";
        
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() {
		
            if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                var json_array =  JSON.parse(xmlhttp.responseText);
                
                $('#sub_menu_list').empty();
                
                for (var key in json_array) {
                                            
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
	};
        
        var site_url = "<?php echo site_url("Design/get_buttons"); ?>";
        
	xmlhttp.open("GET", site_url, true);
	
	xmlhttp.send();
    
    }
    
    function LoadFabricMenus(selected_category) {
	
        if(parseInt(selected_category) !== 1)
            return;
        
        document.getElementById('option-list').innerHTML = "";
        
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() {
		
            if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                var json_array =  JSON.parse(xmlhttp.responseText);
                
                $('#sub_menu_list').empty();
                
                for (var key in json_array) {
                                              
                    var on_click = 'return LoadOptions('.concat(json_array[key]['id']).concat(');');

                    $('#sub_menu_list').append(
                        $('<a>').attr('href', '#').attr('value', json_array[key]['id']).attr('onclick', on_click).attr('class', 'list-group-item').append(
                            $('<span>').attr('class', 'tab').append(json_array[key]['name'])
                    ));

                }
                
            }
	};
        
        var site_url = "";

        site_url = "<?php echo site_url("Design/get_product_menus"); ?>";
		
        site_url = site_url.concat("/").concat(<?php  echo $product_id;  ?>).concat("/").concat(selected_category);
        	
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
       
       $("#last_name").val(user.last_name);
       
       $("#first_name").val(user.first_name);
       
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
        
        user.first_name = $("#first_name").val();
        
        user.last_name = $("#last_name").val();
        
        user.phone_number = $("#contact_phone").val();
        
        user.address_line_01 = $("#address_line_01").val();
        
        user.address_line_02 = $("#address_line_02").val();
        
        user.city = $("#city").val();
        
        user.country = $("#country").val();
        
        user.postal_code = $("#postal_code").val();
        
        user.email = $("#email").val();
    }
    
    /**
    * Load buttons to product using the 
    * currentButton and CurrentThread
    * @returns void     */
    function LoadButtons(button_image){
                                                            
        for(var image_key in current_design_data[current_side + '_images']){
            
            var base_image = current_design_data[current_side + '_images'][image_key];
            
            for(var button_key in base_image.buttons){
                        
                var button_data = base_image.buttons[button_key];

                add_button_layer(button_image, button_data.id, button_data.pos_x, button_data.pos_y);
            }

        }
        
        LoadDesignOptionButtons(button_image);
                     
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
                
        var threadData = GetThread(thread_id);
        
        tmpThread = threadData;
        
        var threadColor = threadData.color;
                
        ApplyThreadToButton(threadColor);
        
        
                
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
                
                design_data.currentButton = buttonData;
                
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
                
                design_data.currentThread = threadData;
                
                return threadData;
            }
                
        }
    }
    
    /**
     * Loads buttons for different options
     * @returns {undefined}
     */
    function LoadDesignOptionButtons(button_image){

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
                        
                        add_button_layer(button_image, button_data.id, button_data.pos_x, button_data.pos_y);
                    }

                }
            }

        }
        
    }
    
    /**
    * Removes all current buttons
    * @returns {undefined}     */
    function ClearButtons(){
        
        $("img").remove(".buttons");
    }
    
    function ApplyThreadToButton(threadColor){
        
        $.post('<?= site_url("Design/BlendButtonThread/"); ?>',{ currentButton : JSON.stringify(design_data.currentButton), threadColor : threadColor  },function(json_result){

            var image_data = JSON.parse(json_result);
            
            $("#design_button_image").attr("src", image_data.design_image_name);
            
            tmp_button_image = image_data.image_name;
                    
        });
    }
    
    function ApplyThread()
    {
        
        ClearButtons();
        
        design_data.currentThread = tmpThread;
        
        button_image = tmp_button_image;
        
        LoadButtons(button_image);
    }
    
    function updateUserData()
    {
        console.log("User : " + JSON.stringify(design_data['user']));
        
        $.post('<?= site_url("user/update_user_data/"); ?>',{ user : JSON.stringify(design_data['user'])},function(json_result){
                         

        });
        
        return false;
    }
    
    function CheckOut()
    {
        if(!user_logged)
        {
            login_callbacks = $.Callbacks();
            login_callbacks.add(CheckOut);
            open_login();
        }
        else
        {
            $.post('<?= site_url("Design/SaveUserDesign/"); ?>',{ userDesign : JSON.stringify(design_data)},function(json_result){

                var result = JSON.parse(json_result);
                
                $('#userDataModal').modal('show');

            });
        }

    }
    

</script>

<!-- Design Page -->
<div class='design-page'>
    <div id='' class='container'>

        <div id='' class="row">

            <!-- MAIN MENUS  -->

            <div id='' class=' design-menu col-sm-2'>
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
            
            <!-- SUB MENUS  -->
            <div id='sub_menu_list' class="col-sm-2">

            </div>

            <div id='design-preview' class='col-sm-4 design-preview'>

            </div>
            
            <div class='col-sm-4' style="float:right;">
                <button onclick="CheckOut()" class="btn-primary">Checkout</button>
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

<div id="userDataModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirm User Details</h4>
      </div>
      <div class="modal-body">
          <div style="width: 100%">
              <div class="form-group">
                  <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" onchange="user_data_changed()">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" onchange="user_data_changed()">
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
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" onchange="user_data_changed()">
              </div>
              <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Country" onchange="user_data_changed()">
              </div>
              <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" class="form-control" id="postal_code" onchange="user_data_changed()">
              </div>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="updateUserData()">Update</button>
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
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="ApplyThread()">Apply</button>
            </div>
            
        </div>
    </div>
</div>

</html>
