/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
    
    /**
     * The ID of the option's list
     * @type String
     */
    var options_list_id = "option-list";
    
    var sub_menu_list_id = "sub_menu_list";
    
    var design_object = null;
    
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
    
    var checking_out = false;
    
    var frontImageConvas;
    
    var base_design_url;
    
    var base_url;
    
    /**
     * This is the DIV used to preview the product design
     * @type String
     */
    var previewDiv = "#design-preview";
    
    
    /**
     * Loads the menus for a selected design category
     * This is called whenever a category is Selected. 
     * @param {type} selected_category The category selected
     * @param {type} design_object The associated design object
     * @param {type} design_parent The parent design object
     * @returns {undefined}
     */
    function OnDesignCategoryChanged(selected_category, design_object, design_parent) 
    {
	this.design_object = design_object;
        
        this.category_index = selected_category;
        
        InvertFabric(selected_category, design_parent);
        
        LoadMeasurementMenus(selected_category);
        
        LoadDesignMenus(selected_category, true);
        
        LoadButtonOptions(selected_category);
        
        LoadFabricMenus(selected_category);
        
        LoadMixFabricMenus(selected_category);

    }
    
    /**
     * Inverts the design if the category selected is "Invert"
     * @param {type} selected_category
     * @param {type} design_parent The parent element to which the design object is previewed
     * @returns {undefined}
     */
    function InvertFabric(selected_category, design_parent)
    {
         
        if(parseInt(selected_category) !== 6)
            return;
                 
        var temp_fabric = design_object.design_data.defaultFabric;

        design_object.design_data.defaultFabric = design_object.design_data.mixFabric;

        design_object.design_data.mixFabric = temp_fabric;

        design_object.PreviewDesign(true, design_parent, this.design_object.current_side);

        return;
        
    }
    
    /**
     * This metod is called when the user clicks on Measurments
     * It presents the user with two options
     * 1) Request a Tailor
     * 2) Enter measurments himself
     * @param {type} selected_category
     * @returns {undefined}
     */
    function LoadMeasurementMenus(selected_category){
        
        if(parseInt(selected_category) !== 4)
            return;
        
        document.getElementById(options_list_id).innerHTML = "";

        $('#' + sub_menu_list_id).empty();

        $('#' + sub_menu_list_id).append(
            $('<a>').attr("data-toggle", "modal").attr("data-target", "#myMeasurementModal").attr('href', '#').attr('class', 'list-group-item').append(
                $('<span>').attr('class', 'tab').append("Enter Measurements")
        )); 

        $('#' + sub_menu_list_id).append(
            $('<a>').attr("data-toggle", "modal").attr("data-target", "#userDataModal").attr('href', '#').attr('class', 'list-group-item').append(
                $('<span>').attr('class', 'tab').append("Request Tailor")
        ));

    }
    
    /**
     * 
     * @param {type} selected_category
     * @param {type} clearOptions
     * @returns {undefined}
     */
    function LoadDesignMenus(selected_category, clearOptions) 
    {
	                
        if (parseInt(selected_category) !== 2)
            return;
        
        if(clearOptions)
            document.getElementById(options_list_id).innerHTML = "";
                
        var site_url = design_object.base_design_url.concat("/get_product_design_menus"); 
        
        $.post(site_url,{ selected_option_list : JSON.stringify(GetSelectedOptionsList()), product_id : this.design_object.product_id  },function(json_result){

            var json_array =  JSON.parse(json_result);
            
            $('#' + sub_menu_list_id).empty();
                
                for (var key in json_array) {
                                               
                    var on_click = 'return LoadOptions('.concat(json_array[key]['id']).concat(');');

                    $('#' + sub_menu_list_id).append(
                        $('<a>').attr('href', '#').attr('value', json_array[key]['id']).attr('onclick', on_click).attr('class', 'list-group-item').append(
                            $('<span>').attr('class', 'tab').append(json_array[key]['name'])
                    ));
                                  
            }
            
            
                    
        });

    }
    
    /**
     * For the current design_object, get the id's of the different available options
     * and return it as a list
     * @returns {Array|GetSelectedOptionsList.selected_list}
     */
    function GetSelectedOptionsList()
    {
        var selected_list = [];

        for (var menu in design_object.current_design_data['design']) 
        {
            if(design_object.current_design_data['design'][menu].option !== null)
            {
                var design_option = design_object.current_design_data['design'][menu].option;
                selected_list.push(design_option.id);
            }
        }

        return selected_list;
    };
    
    /**
     * This method loads the main fabric menus
     * @param {type} selected_category
     * @returns {undefined}
     */
    function LoadFabricMenus(selected_category) {
	
        if(parseInt(selected_category) !== 1)
            return;
        
        document.getElementById(options_list_id).innerHTML = "";
        
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() 
        {		
            if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) 
            {
                var json_array =  JSON.parse(xmlhttp.responseText);
                
                $('#' + sub_menu_list_id).empty();
                
                for (var key in json_array) {
                                              
                    var on_click = 'return LoadOptions('.concat(json_array[key]['id']).concat(');');

                    $('#' + sub_menu_list_id).append(
                        $('<a>').attr('href', '#').attr('value', json_array[key]['id']).attr('onclick', on_click).attr('class', 'list-group-item').append(
                            $('<span>').attr('class', 'tab').append(json_array[key]['name'])
                    ));

                }
                
            }
	};
        
        var site_url = "";
        site_url = this.design_object.base_design_url.concat("/get_product_menus"); 	
        site_url = site_url.concat("/").concat(this.design_object.product_id).concat("/").concat(selected_category);       	
	xmlhttp.open("GET", site_url, true);	
	xmlhttp.send();
    
    }
    
    /**
     * 
     * @param {type} selected_category
     * @returns {undefined}
     */
    function LoadButtonOptions(selected_category){
	
        if(parseInt(selected_category) !== 5)
            return;
        
        document.getElementById(options_list_id).innerHTML = "";
        
	var xmlhttp = new XMLHttpRequest();
        
        var DesignObject = design_object;
	
	xmlhttp.onreadystatechange = function() {
		
            if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                var json_array =  JSON.parse(xmlhttp.responseText);
                
                $('#' + sub_menu_list_id).empty();
                
                for (var key in json_array) {
                                            
                    var button_data = json_array[key];

                    console.log(button_data);

                    var image_element = document.createElement("img");                 

                    var image_path = DesignObject.assets_dir.concat('images/buttons/').concat(button_data.image_name);                        

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
        
        var site_url = this.design_object.base_design_url.concat("/get_buttons"); 
	xmlhttp.open("GET", site_url, true);
	xmlhttp.send();
    
    }
        
    function LoadOptions(menu_id)
    {	        
        this.menu_index = parseInt(menu_id);
                
        if(parseInt(this.category_index) === 2)
        {           
            if(parseInt(this.design_object.current_design_data['design'][this.menu_index].is_back_menu) === 1 && this.current_side === 'front')
            {               
                this.current_side = 'back';
                this.design_object.PreviewDesign(true, $("#design-preview"), this.design_object.current_side);
            }
            
            if(parseInt(this.design_object.current_design_data['design'][this.menu_index].is_back_menu) === 0 && this.current_side === 'back')
            {
                this.current_side = 'front';
                this.design_object.PreviewDesign(true, $("#design-preview"), this.design_object.current_side);
            }
        }
                
	var xmlhttp = new XMLHttpRequest();
        
        var DesignObject = this.design_object;

	xmlhttp.onreadystatechange = function() 
        {		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200 && parseInt(this.category_index) !== 3) 
            {
                var json_array =  JSON.parse(xmlhttp.responseText);
                                
                document.getElementById(options_list_id).innerHTML = "";

                for (var key in json_array) 
                {                                                          
                    var image_element = document.createElement("img");                 

                    var image_path = '';
                                                         
                    if(parseInt(category_index) === 1)
                    {               
                        image_path = DesignObject.assets_dir.concat('images/product/fabric/').concat(json_array[key]['name']);                        
                    }
                    else
                    {
                         image_path = DesignObject.assets_dir.concat('images/design/thumbnail/').concat(json_array[key]['image_data']['thumbnail']['name']);
                    }

                    image_element.setAttribute("src", image_path);  

                    image_element.setAttribute("height", "100");
                    
                    image_element.setAttribute("width", "96");

                    var link_element = document.createElement("a");
                    
                    link_element.appendChild(image_element);

                    var list_element = document.createElement("li");
                    
                    var function_string = "";
                    
                    if(parseInt(this.category_index) === 1)
                    {
                        function_string = "option_selected(" + json_array[key]['item_id'] + ")";
                    }
                    
                    if(parseInt(this.category_index) === 2)
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
        
        var site_url = DesignObject.base_design_url.concat("/get_product_design_options"); 
        	
	site_url = site_url.toString() + "/" + this.menu_index.toString() + "/" + this.category_index + "/" + DesignObject.product_id;
		
	xmlhttp.open("GET", site_url, true);
	
	xmlhttp.send();
    }
    
    function LoadMixFabricMenus(selected_category) {
	
        if(parseInt(selected_category) !== 3)
            return;
        
        document.getElementById(options_list_id).innerHTML = "";
        
        var site_url = "";
        
        site_url = this.design_object.base_design_url.concat("/get_product_design_menus"); 
        
        var submenulistid = this.sub_menu_list_id;
        
        $.post(site_url,{ selected_option_list : JSON.stringify(GetSelectedOptionsList()), product_id : this.design_object.product_id  },function(json_result)
        {

            var json_array =  JSON.parse(json_result);
            
            $('#' + sub_menu_list_id).empty();
                
            for (var key in json_array) {
                                                 
                var outer_div = createCheckboxDiv(json_array[key]['id'], 'Outer ' + json_array[key]['name'], 'outer');  

                if(Boolean(parseInt(json_array[key]['mixed_fabric_support'])))
                {
                    $('#' + submenulistid).append(outer_div);

                    if(Boolean(parseInt(json_array[key]['inner_contrast_support']))){

                        var inner_div = createCheckboxDiv(json_array[key]['id'], 'Inner ' + json_array[key]['name'], 'inner'); 

                        $('#' + sub_menu_list_id).append(inner_div);
                    }
                }

            }

            LoadAllFabrics();   
                   
        });
        
    }
    
    /**
     * This function creates a checkbox list item
     * for mix design options
     * @param {type} id The id of the option
     * @param {type} name The name of the menu
     * @param {type} type The type (outer or inner design menu)
     * @returns {jQuery}
     */
    function createCheckboxDiv(id, name, type) {
        
        var container = $('<div>').prop('class', 'list-group-item' + ' ' + type).prop('id', id);
        
        var value = false;
        
        if(type.toString() === 'inner')
        {
            if($.inArray(parseInt(id), this.SelectedInnerMix) !== -1)
            {           
                value = true;            
            }  
        }
        else
        {
            if($.inArray(parseInt(id), this.SelectedOuterMix) !== -1)
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
     * This funtion loads all the fabrics for a given submenu
     * @param {type} submenu_id
     * @returns {undefined}
     */
    function LoadAllFabrics(submenu_id){
	        
        this.submenu_index = submenu_id;
                
	var xmlhttp = new XMLHttpRequest();
        
        var DesignObject = this.design_object;

	xmlhttp.onreadystatechange = function() 
        {		
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) 
            {                                             
                var json_array =  JSON.parse(xmlhttp.responseText);                                                
                document.getElementById(options_list_id).innerHTML = "";

                for (var key in json_array) {
                    
                    var fabric = json_array[key];
                    
                    console.log("Loading Fabric Option : ");
                    console.log(fabric);
                    
                    var image_element = document.createElement("img");                 

                    var image_path = '';
                    
                    image_path = DesignObject.assets_dir.concat('images/product/fabric/').concat(fabric.name);
                                        
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
        
        var site_url = this.design_object.base_design_url.concat("/get_all_product_fabrics"); 
               
        site_url = site_url + "/" + this.design_object.product_id.toString();
        
	xmlhttp.open("GET", site_url, true);
	
	xmlhttp.send();
    }
    
    function ImageObject(image_data)
    {
        this.image_data = image_data;
        
        this.AddImage = function(element)
        {
            var class_name = "option_class_" + this.image_data.associated_option_id;
            var img = $('<img >');
            img.attr("class", "preview-image " + class_name);
            if(this.image_data.is_button)
            {
                img.attr("id", "button_" + this.image_data.id);
            }
            else
            {
                // Is an option Image
                img.attr("id", "option_" + this.image_data.associated_option_id + "_" + this.image_data.id);
            }
            
            img.attr("src", this.image_data.blend_image === null ? this.image_data.image_src : this.image_data.blend_image);
            var size_string = "";
            if(this.image_data.width !== undefined && this.image_data.width !== null) 
            { 
                size_string = "width : " + this.image_data.width + "; height : " + this.image_data.height + ";";
            }
            img.attr("style", "position : absolute; left : " + this.image_data.pos_x + "px; top : " + this.image_data.pos_y + "px; z-Index : " + this.image_data.depth + "; " + size_string);
            element.append(img);

        };        
    }
    
    /**
     * This is the design object used to draw designs. 
     * @param {type} design_parameters
     * @returns {DesignObject}
     */
    function DesignObject(design_parameters)
    {
        this.design_data = design_parameters.design_data;
        
        this.Buttons = design_parameters.buttons;
        
        this.button_image = null;
        
        this.Threads = design_parameters.threads;
        
        this.assets_dir = design_parameters.assets_dir;
        
        this.product_id = design_parameters.product_id;
        
        this.base_url = design_parameters.base_url;
        
        this.base_design_url = this.base_url.concat("Design");
        
        this.checking_out = false;
        
        this.current_side = 'front';
        
        this.current_design_data = null;
        
        this.image_object_array = [];
        
        this.LoadInitialDesign = function()
        {
            if(!this.design_data.currentButton || !this.design_data.currentThread)
            {
                this.button_image = this.assets_dir.concat("images/buttons/") + this.design_data.currentButton.image_name;
            }
            else
            {
                var url = this.base_design_url.concat("/BlendButtonThread/");
                var Instance = this;
                $.post(url,{ currentButton : JSON.stringify(this.design_data.currentButton), threadColor : this.design_data.currentThread.color  },function(json_result){

                    var image_data = JSON.parse(json_result);
                    
                    // Set Selected Button
                    $("#design_button_image").attr("src", image_data.design_image_name);

                    Instance.button_image = image_data.image_name;

                });
            }
            
            /**
             * This is called when the modal dialog closes
             * @returns {undefined}
             */
            $('#userDataModal').on('hide.bs.modal', function() {

                if(this.checking_out)
                {
                    this.checking_out = false;
                    //Redirect to the checkout page
                    window.location.href = '<?= site_url("checkout/checkout"); ?>';
                }

            });

            this.current_design_data = this.design_data['design'];

            this.PreviewDesign(true, $("#design-preview"), 'front');

            

        };
        
        
        this.PreviewDesign = function(async, design_parent, current_side)
        {
            // An array containing all the images that are to be drawn
            this.image_object_array = [];
            
            design_parent.empty();
            design_parent.fadeOut( "slow" );
            
            var base_image_dir = this.assets_dir.concat("images/product/");
            var option_image_dir = this.assets_dir.concat("images/design/");
            var fabric_src = null;
            
            // Treat Base Images
            if("name" in this.design_data.defaultFabric)
            {
                fabric_src = base_image_dir + "fabric/" + this.design_data.defaultFabric['name'];
            }            
            for(var image_key in this.current_design_data[current_side + '_images'])
            {
                var base_image = this.current_design_data[current_side + '_images'][image_key];
                var image_parameters = 
                {
                    image_id : base_image.id,
                    associated_option_id : base_image.item_id,
                    pos_x : base_image.pos_x,
                    pos_y : base_image.pos_y,
                    depth : base_image.depth,
                    is_back_image : base_image.is_back_image,
                    is_inner_image : base_image.is_inner_image,
                    image_src : base_image_dir.concat(current_side + "/") + base_image.name,
                    fabric_src : fabric_src,
                    is_button : false,
                    current_side : current_side                    
                };
                var imageObject = new ImageObject(image_parameters);               
                this.image_object_array.push(imageObject);
                
                // Add Buttons
                for(var button_key in base_image.buttons){
                        
                    var button_data = base_image.buttons[button_key];
                    
                    var button_image_parameters = 
                    {
                        image_id : button_data.id,
                        associated_option_id : button_data.button_id,
                        pos_x : button_data.pos_x,
                        pos_y : button_data.pos_y,
                        depth : button_data.depth,
                        fabric_src : null,
                        image_src : this.assets_dir.concat("images/buttons/") + this.design_data.currentButton.image_name,
                        blend_image : this.button_image,
                        is_button : true,
                        current_side : current_side,
                        width : "10px",
                        height : "10px"
                    };

                    var imageObject = new ImageObject(button_image_parameters);               
                    this.image_object_array.push(imageObject);
                }
                                
            }
            
            // Treat Option Images
            for (var menu in this.current_design_data['design']) 
            {
                if(this.current_design_data['design'][menu].option !== null){

                    var design_option = this.current_design_data['design'][menu].option;

                    for(var image in design_option["images"]){

                        var design_image = design_option["images"][image];

                        if(current_side === 'front' && parseInt(design_image.is_back_image) === 1)
                        {
                            continue;
                        }

                        if(current_side === 'back' && parseInt(design_image.is_back_image) === 0)
                        {
                            continue;
                        }
                        
                        var use_fabric = this.choose_fabric(menu, design_image.id);
                        var option_fabric_src = null;
                        
                        if("name" in use_fabric)
                        {
                            design_image.fabric_id = use_fabric["id"];
                            option_fabric_src = base_image_dir + "fabric/" + use_fabric['name'];
                        }
                                               
                        var image_parameters = 
                        {
                            image_id : design_image.id,
                            associated_option_id : design_image.item_id,
                            pos_x : design_image.pos_x,
                            pos_y : design_image.pos_y,
                            depth : design_image.depth,
                            is_back_image : design_image.is_back_image,
                            is_inner_image : design_image.is_inner_image,
                            image_src : option_image_dir.concat(design_image.name),
                            fabric_src : option_fabric_src,
                            is_button : false,
                            current_side : current_side                    
                        };
                        
                        var imageObject = new ImageObject(image_parameters);
                        this.image_object_array.push(imageObject);
                        
                        for(var button_key in design_image.buttons){
                        
                            var button_data = design_image.buttons[button_key];

                            var button_image_parameters = 
                            {
                                image_id : button_data.id,
                                associated_option_id : button_data.button_id,
                                pos_x : button_data.pos_x,
                                pos_y : button_data.pos_y,
                                depth : button_data.depth,
                                fabric_src : null,
                                image_src : this.assets_dir.concat("images/buttons/") + this.design_data.currentButton.image_name,
                                blend_image : this.button_image,
                                current_side : current_side,
                                is_button : true,
                                width : "10px",
                                height : "10px"
                            };

                            var imageObject = new ImageObject(button_image_parameters);               
                            this.image_object_array.push(imageObject);
                        }
                    }
                }

            } 
            
            // Blends the images and adds them to the design parent 
            this.BlendImages(async, design_parent);
                                 
            

        };
        
        /**
         * Given a menu item, this function selects the appropriate fabruc
         * @param {type} menu_index the menu index
         * @param {type} image_id The id of the option image
         * @returns { a fabric object }
         */
        this.choose_fabric = function(menu_index, image_id)
        {
            var use_fabric = this.design_data.defaultFabric;
        
            var menu_data = this.current_design_data['design'][menu_index];

            if(menu_data.option !== null){

                var option_data = menu_data.option;

                if(parseInt(menu_data.mixed_fabric_support) === 1){

                    for(var image in option_data["images"]){

                        var design_image = option_data["images"][image];

                        if(parseInt(image) === parseInt(image_id)){

                            if(parseInt(design_image.is_inner) === 1){

                                if($.inArray(parseInt(menu_index), this.design_data.SelectedInnerMix) !== -1 && parseInt(menu_data.inner_contrast_support) === 1)
                                {
                                    use_fabric = this.design_data.mixFabric;                               
                                }
                            }
                            else{

                                if($.inArray(parseInt(menu_index), this.design_data.SelectedOuterMix) !== -1)
                                {                               
                                    use_fabric = this.design_data.mixFabric;
                                }
                            }
                        }
                    }
                }

            }

            return use_fabric;
        };
        
        /**
         * This function blends the image in the server
         * And adds an image element to "design_parent" following
         * a z-index
         * @param {type} async True if this operation is done aynchronously
         * @param {type} design_parent The parent to which this image will be appended after the blend
         * @returns {undefined}
         */
        this.BlendImages = function(async, design_parent)
        {
            if(async)
            {
                var Instance = this;
                
                $.post(this.base_design_url.concat("/BlendImage"),{ image_data : JSON.stringify(this.image_object_array) },function(json_image_object_array){
                    
                    Instance.image_object_array = JSON.parse(json_image_object_array);
                    
                    for(var key in Instance.image_object_array)
                    {                
                        var image_object = new ImageObject(Instance.image_object_array[key]["image_data"]);
                        
                        Instance.image_object_array[key] = image_object ;
                    }
                    
                    Instance.AddDesignImages(design_parent);
                    
                });
            }
            else
            {
                $.ajax({
                type: 'POST',
                url: server_url,
                data: { image_data : JSON.stringify(this.image_object_array) },
                success: function(json_image_object_array){

                    this.image_object_array = JSON.parse(json_image_object_array);
                    
                },
                async:false
              });
            }
        };
        
        /**
         * Add images in the image array to a design parent
         * @param {type} design_parent The design parent to which the images are added
         * @returns {void}
         */
        this.AddDesignImages = function(design_parent)
        {          
            for(var key in this.image_object_array)
            {
                var image_object = this.image_object_array[key];
                image_object.AddImage(design_parent);
            }

            design_parent.fadeIn( "slow" );
        };
                        
        /**
        * This function replaces an optionElement supplied with the 
        * current images in the canvas 
        * @returns {undefined}
        */       
        this.replaceOptionElementWithCanvasImages = function()
        {
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
        };
        
        /**
         * This function refreshes te design to apply any fabric mix
         * @returns {undefined}
         */
        this.ApplyMixFabric = function()
        {
            if(!("name" in this.design_data.mixFabric)){

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

                            if(((parseInt(design_image.is_inner) === 0 && $.inArray(parseInt(menu), this.design_data.SelectedOuterMix) !== -1) 
                                || (parseInt(design_image.is_inner) === 1 && parseInt(menu_data.inner_contrast_support)=== 1 && $.inArray(parseInt(menu), this.design_data.SelectedInnerMix) !== -1))
                                && parseInt(design_image.fabric_id) !== parseInt(this.design_data.mixFabric['id'])) 
                            {

                                design_image.fabric_id = parseInt(this.design_data.mixFabric['id']);

                                var blend_path = base_image_dir + "fabric/" + this.design_data.mixFabric['name'];

                                var curr_elem_id = "#option_".concat(option_id) + "_" + image_id;

                                console.log("Changing fabric for " + design_image + " : " + curr_elem_id);

                                $.post('<?= site_url("Design/BlendImage/"); ?>',{image_url :  image_path, blend_url : blend_path, id : option_id, image_id : image_id, element_id : curr_elem_id },function(dataurl){

                                    var data = JSON.parse(dataurl);

                                    replace_image_layer(data.image, data.id, data.image_id, data.element_id);

                                });
                            }

                            if(((parseInt(design_image.is_inner) === 0 && $.inArray(parseInt(menu), this.design_data.SelectedOuterMix) === -1) 
                                || (parseInt(design_image.is_inner) === 1 && parseInt(menu_data.inner_contrast_support) === 1 && $.inArray(parseInt(menu), this.design_data.SelectedInnerMix) === -1))
                                && parseInt(design_image.fabric_id) !== parseInt(this.design_data.defaultFabric['id']))
                            {

                                design_image.fabric_id = parseInt(this.design_data.defaultFabric['id']);

                                var blend_path = base_image_dir + "fabric/" + this.design_data.defaultFabric['name'];

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
        };
        
        /**
         * This function removes for a given option all associated buttons
         * @param {type} design_option The design option
         * @returns {void}
         */
        this.RemoveOptionButtons = function(design_option)
        {
            for(var image in design_option["images"]){
                                      
                var design_image = design_option["images"][image];

                if(this.current_side === 'front' && parseInt(design_image.is_back_image) === 1)
                {
                    continue;
                }

                if(this.current_side === 'back' && parseInt(design_image.is_back_image) === 0)
                {
                    continue;
                }

                for(var button_key in design_image.buttons){

                    var button_data = design_image.buttons[button_key];

                    $("#button_" + button_data.id).remove();

                }                     
            }
        };
                
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

                    if (!this.design_data.SelectedInnerMix)
                    {
                        this.design_data.SelectedInnerMix = [];
                    }
                    
                    this.design_data.SelectedInnerMix.push(parseInt(selected_menu));
                }
                else
                {                  
                    
                    if (!this.design_data.SelectedOuterMix)
                    {
                        this.design_data.SelectedOuterMix = [];
                    }
                    
                    this.design_data.SelectedOuterMix.push(parseInt(selected_menu));
                }
                
                console.log("Checked Menu");

            }
            else{
                
                if(type.toString() === 'inner')
                {
                    
                    
                    var index = this.design_data.SelectedInnerMix.indexOf(parseInt(selected_menu));
                    
                    if (index > -1) 
                    {
                        this.design_data.SelectedInnerMix.splice(index, 1);
                    }

                }
                else
                {
                    
                    var index = this.design_data.SelectedOuterMix.indexOf(parseInt(selected_menu));
                    
                    if (index > -1) 
                    {
                        this.design_data.SelectedOuterMix.splice(index, 1);
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

                this.design_data.mixFabric = newOption;

                ApplyMixFabric();

             }
         };

        
        var site_url =  base_design_url.concat("get_fabric");   

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

        var site_url = base_design_url.concat("get_option"); ;

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
                       
                this.design_data.defaultFabric = newOption;

                LoadParametersToPreview(true);

            }
	};

        var site_url = base_design_url.concat("get_fabric");   
             
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
        
    function load_measurement_data(id, youtube_link, description){
        
        if(parseInt($("#youtube_frame").val()) !== parseInt(id)){
                    
            $("#youtube_frame").prop("src", youtube_link);
            $("#youtube_frame").val(id);
            $("#measurement_description").text(description);
        }
    }
    
    function measurement_changed(element){
        
        var measurement_id = parseInt(element.id.replace("measurement_", ""));
        
        this.design_data['measurements'][measurement_id].value = element.value;
        
    }
        
    function LoadMeasurementsIntoModal(){
        
         $("#my_measurements").empty();
        
        for(var key in this.design_data['measurements']){
            
            var measurement = this.design_data['measurements'][key];
            
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
        
        var user = this.design_data['user'];
        
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
                
                this.design_data.currentButton = buttonData;
                
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
                
                this.design_data.currentThread = threadData;
                
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
        
        $.post('<?= site_url("Design/BlendButtonThread/"); ?>',{ currentButton : JSON.stringify(this.design_data.currentButton), threadColor : threadColor  },function(json_result){

            var image_data = JSON.parse(json_result);
            
            $("#design_button_image").attr("src", image_data.design_image_name);
            
            tmp_button_image = image_data.image_name;
                    
        });
    }
    
    function ApplyThread()
    {
        
        ClearButtons();
        
        this.design_data.currentThread = tmpThread;
        
        button_image = tmp_button_image;
        
        LoadButtons(button_image);  
    }
    
    function updateUserData()
    {
        console.log("User : " + JSON.stringify(this.design_data['user']));
        
        $.post('<?= site_url("user/update_user_data/"); ?>',{ user : JSON.stringify(this.design_data['user'])},function(json_result){
                         

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
            $.post('<?= site_url("Design/SaveUserDesign/"); ?>',{ userDesign : JSON.stringify(this.design_data)},function(json_result){

                var result = JSON.parse(json_result);
                
                var base46Images = [];
                
                checking_out = true;
                
                var side_before_save = current_side;
                
                // Get the current image of the preview DIV as a Base64 image data
                html2canvas($(previewDiv), {
                onrendered: function (canvas) {
                        theCanvas = canvas;
                        var imgageData = canvas.toDataURL("image/png");
                        base46Images[current_side] = imgageData;
                        alert(imgageData);
                        
                     }
                 });
                 
                 // Get the other side image of the preview DIV as a Base64 image data
                 previewDIv = '#design-preview-sim';
                 
                 if(current_side === 'front')
                 {
                    current_side = 'back'; 
                 }
                 else
                 {
                     current_side = 'front';
                 }
                 
                 // Load the parameters of the back image to the preview div
                LoadParametersToPreview(false);
                 
                html2canvas($(previewDiv), {
                onrendered: function (canvas) {
                    
                        var imgageData = canvas.toDataURL("image/png");
                        base46Images[current_side] = imgageData;
                        alert(imgageData);
                        
                     }
                 });
                 
                previewDIv = '#design-preview';
                
                current_side = side_before_save;
                
                LoadParametersToPreview(true);
                
                //$('#userDataModal').modal('show');

            });
        }

    }
    