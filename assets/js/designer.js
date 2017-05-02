
function Product(product_object)
{
    
    this.base_url = '';
    
    this.product = product_object;
    
    this.tmp_base_64_button_image;
    
    this.tmp_selected_thread;
    
    this.total_price = 0;
    
    this.image_count = 0;
    
    this.current_image_count = 0;
    
    /**
     * When this is true, the user choose to request a tailor 
     * instead of entering the product measurements himself
     */
    this.requestTailor = false;
    
    /**
     * An array of strings representing
     * the product details
     */
    this.product_details = [];
    
    /**
     * This is the ID of the menu list item. 
     * Menu items are appended here
     */
    this.sub_menu_list_id = "#sub_menu_list";
    
    this.options_list_id = "#option-list";
    
    this.measurment_modal_id = "#myMeasurementModal";
    
    this.user_modal_id = "#userDataModal";
    
    this.measurements_container_id = "#my_measurements";

   /**
    * This represents the menu selected
    * @type type
    */
   var menu_selected;
      
   /**
    * THis represents the category that is selected
    * @type type
    */
   var category_selected;
   
   /**
    * This is the container used to display
    * the different options
    * @type Sly
    */
   var optionsSly;
   
   /**
    * This is the container used to display
    * the different thread options for a button
    * @type type
    */
   var threadsSly;
   
   /**
    * The element the product is currently drawint to 
    * @type type
    */
   var designDomElementID;
   
   var designBackDomElementID;
    
    this.InitOptionsContainer = function(frame)
    {
        var wrap  = frame.parent();
        this.optionsSly = new Sly( frame, {
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
    };
    
    this.InitThreadsContainer = function(frame)
    {
        var wrap  = frame.parent();
        this.threadsSly = new Sly( frame, {
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
    };
    
    this.setProductDetails = function()
    {
        this.product_details = [];
                
        this.total_price = parseInt(this.product.price);
                
        for(var key in this.product.product_menus)
        {
            var menu = this.product.product_menus[key];

            if(parseInt(menu.category) === 2)
            {
                for(var option_key in menu.design_options)
                {
                    var option_object = menu.design_options[option_key];
                    
                    if(parseInt(option_object.selected) === 1)
                    {
                        if(parseInt(option_object.price) > 0)
                        {
                            this.product_details.push(" - " + option_object.name + " : " +  option_object.price + " FCFA");
                            this.total_price += parseInt(option_object.price);
                        }
                    }
                }
            }                 
        }
    };
    
    this.setContainers = function(domElementID, domBackElementID)
    {
        this.designDomElementID = domElementID;
        this.designBackDomElementID = domBackElementID;
    };
    
    this.draw = function(nofade)
    {
        
        var container = $("#" + this.designDomElementID);
        var backContainer = $("#" + this.designBackDomElementID);
        var Instance = this;
        
        if(nofade)
        {
            // Clear Contents
            container.empty();
            backContainer.empty();

            this.drawProduct('front');
            this.drawProduct('back');
        }
        else
        {
            container.fadeOut( "slow", function() 
            {
                // Clear Contents
                container.empty();
                Instance.drawProduct('front');
                container.fadeIn("slow");
            });
            
            backContainer.fadeOut( "slow", function() 
            {
                // Clear Contents
                backContainer.empty();
                Instance.drawProduct('back');
                backContainer.fadeIn("slow");
            });
        }
        
    };
    
    this.drawProduct = function(side)
    {
        var container;
        
        if(side.toString() === 'front')
        {
            container = $("#" + this.designDomElementID);
        }
        else
        {
            container = $("#" + this.designBackDomElementID);
        }
        
        // Loop Through design Menus
        for(var key in this.product.product_menus)
        {
            var menu = this.product.product_menus[key];

            // Its a design menu if the category is 2
            if(parseInt(menu.category) === 2)
            {
                $('<div/>', 
                {
                    id: side + '_menu_' + menu.id,
                    style: 'position : absolute', 
                    class : 'designer-menu menu_' + menu.id
                }).appendTo(container);

                this.drawMenu(menu, side);
            }
        } 
    };
    
    this.drawMenu = function(menu, side)
    {
        var Instance = this;
        
        // Get the element corresponsing to the side
        var menu_element = $("#" + side + "_menu_" + menu.id);
                
        for(var option_key in menu.design_options)
        {
            var design_option = menu.design_options[option_key];
            
            console.log("Drawing " + design_option.name);

            if(parseInt(design_option.selected) === 1)
            {
                menu.option_selected = design_option.id;
                Instance.menu_selected = menu.id;
            }
                       
            // Only show selected Options
            var visibility = parseInt(design_option.selected) === 1 ? 'visible' : 'hidden';

            // Add a div for the option
            var design_option_element = $('<div/>', 
            {
                id: side + '_option_' + design_option.id,
                style: 'position : relative', 
                class : 'designer-option option_' + design_option.id                      
            }).css("visibility", visibility).appendTo(menu_element);
            
            for(var image_key in design_option.images)
            {
                
                var design_option_image = design_option.images[image_key];
                
                design_option_image.base_64_image = design_option_image.original_base_64_image;
                
                if(parseInt(design_option_image.is_back) === 1 && side === 'front')
                {
                    continue;
                }

                if(parseInt(design_option_image.is_back) === 0 && side === 'back')
                {
                    continue;
                }
                
                var imageElement = $('<img />', 
                { 
                    id: 'image_' + design_option_image.id,
                    class : "preview-image"
                }).css({ zIndex : design_option_image.zindex, left: design_option_image.x_pos.toString().concat("px"), top: design_option_image.y_pos.toString().concat("px")});
                
                
                // Apply Inner Contrast if applicable
                if (parseInt(design_option_image.is_inner) === 1 
                        && parseInt(menu.inner_contrast_support) === 1
                        && parseInt(menu.inner_mix_selected) === 1)
                {
                    design_option_image.base_64_image 
                        = Instance.blendImage(design_option_image, Instance.product.mix_fabric, imageElement);
                }                           
                // Apply Outer Contrast if applicable
                else if (parseInt(design_option_image.is_inner) === 0 
                        && parseInt(menu.is_mixed_fabric_menu) === 1
                        && parseInt(menu.outer_mix_selected) === 1)
                {
                    design_option_image.base_64_image 
                        = Instance.blendImage(design_option_image, Instance.product.mix_fabric, imageElement);
                }
                else if(parseInt(design_option_image.is_inner) === 0)
                {
                    design_option_image.base_64_image 
                        = Instance.blendImage(design_option_image, Instance.product.default_fabric, imageElement);
                }
                else if(parseInt(design_option_image.is_inner) === 1)
                {
                    design_option_image.base_64_image 
                        = design_option_image.original_base_64_image;
                        
                    imageElement.attr("src", design_option_image.base_64_image);
                    this.imageLoaded();
                }
                                
                imageElement.appendTo(design_option_element);

                // Loop through image buttons and add them as well
                for(var button_key in design_option_image.buttons)
                {
                    var button = design_option_image.buttons[button_key];

                    $('<img />', 
                    { 
                        id: 'image_' + button.id,
                        src: Instance.product.default_button.base_64_image,
                        class : "button-image"
                    }).css({zIndex : button.zindex, left: button.x_pos.toString().concat("px"), top: button.y_pos.toString().concat("px")})
                    .appendTo(design_option_element);

                }

            }

        }
    };
    
    this.imageLoaded = function()
    {
        this.current_image_count++;
        
        if(this.current_image_count === this.image_count)
        {
            // Finished Loading
            $("#loading").fadeOut(500);
        }
    };
    
    this.setImageCount = function()
    {
        this.image_count = 0;
        
        this.current_image_count = 0;
        
        for(var key in this.product.product_menus)
        {
            var menu = this.product.product_menus[key];
            
            for(var option_key in menu.design_options)
            {
                var design_option = menu.design_options[option_key];

                for(var image_key in design_option.images)
                {
                    this.image_count++;
                }
            }
        }
    };
    
    /**
     * This method is called when a new option is selected
     * @param {type} option_selected The id of the option selected
     */     
    this.selectOption = function(option_selected)
    {
        var menu = this.product.product_menus[this.menu_selected];

        // Its a design menu if the category is 2
        if(parseInt(menu.category) === 2)
        {   
            // Backup previously selected menu
            var prev_option_selected = menu.option_selected;  
            // Set new selected menu
            menu.option_selected = option_selected;
            
            // Do nothing if the same menu is selected
            if(parseInt(prev_option_selected) ===  parseInt(menu.option_selected))
            {
                return;
            }
            
            // Update selected states for current and previous options
            menu.design_options[prev_option_selected].selected = 0;
            menu.design_options[menu.option_selected].selected = 1;
            
            var id_menu = "#menu_" + menu.id;
            var id_option = ".option_" + menu.option_selected;

            var prev_id_option = ".option_" + prev_option_selected;

            var selected_option_element = $(id_menu + ' ' + id_option);
            var prev_selected_option_element = $(id_menu + ' ' + prev_id_option);

            // Switch Options
            prev_selected_option_element.fadeOut( "slow", function() 
            {
                prev_selected_option_element.css("visibility", "hidden");
                selected_option_element.css("visibility", "visible");
                selected_option_element.fadeIn("slow");
            });            
        }    
    };
    
    this.create_canvas_for_image = function(imageElement, canvas_width, canvas_height)
    {
        var canvas = document.createElement('canvas');
        canvas.width = canvas_width;
        canvas.height = canvas_height;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(imageElement, 0, 0, imageElement.width, imageElement.height,     
               0, 0, canvas.width, canvas.height);
        
        return canvas;
    };
    
    this.blendImage = function(imageObject, fabric, imageElement)
    {
        var Instance = this;
        
        // Return the option image if there is no fabric set
        if(fabric === null)
        {
            imageElement.attr("src", imageObject.original_base_64_image);
            return imageObject.original_base_64_image;
        }
        
        fabric.base_64_image = fabric.original_base_64_image;
        
        var designImage = new Image();
        designImage.onload = function() 
        {
            
            var fabricImage = new Image();
            
            fabricImage.onload = function() 
            {
                var designImageCanvas = Instance.create_canvas_for_image(designImage, 230, 300);
                var designImageContext = designImageCanvas.getContext('2d');
                var designImageData = designImageContext.getImageData(0, 0, designImageCanvas.width, designImageCanvas.height);
                var designData = designImageData.data;

                var fabricImageCanvas = Instance.create_canvas_for_image(fabricImage, 230, 300);
                var fabricImageContext = fabricImageCanvas.getContext('2d');
                var fabricImageData = fabricImageContext.getImageData(0, 0, fabricImageCanvas.width, fabricImageCanvas.height);
                var fabricData = fabricImageData.data;

                for (var i = 0; i < designData.length; i += 4) 
                {

                    var avgColor = (designData[i] + designData[i + 1] + designData[i + 2]) / 3;

                    if(designData[i + 3] > 0 && avgColor > 150)
                    {
                        designData[i]     = (fabricData[i]);     
                        designData[i + 1] = (fabricData[i + 1]); 
                        designData[i + 2] = (fabricData[i + 2]); 
                    }
                    else if(designData[i + 3] > 0)
                    {
                        designData[i] = 44;
                        designData[i + 1] = 44;
                        designData[i + 2] = 44;
                    }
                }

                designImageContext.putImageData(designImageData, 0, 0);
                
                var finalImage = designImageCanvas.toDataURL();
                
                imageElement.attr("src", finalImage);
                Instance.imageLoaded();

            };
            
            fabricImage.setAttribute('class', 'preview-image');
            fabricImage.src = fabric.base_64_image;
            
        };
        
        designImage.setAttribute('class', 'preview-image');
        designImage.src = imageObject.original_base_64_image;

        
                
    };
    
    this.blendThreadToButton = function(buttonObject, threadObject)
    {
        if(threadObject === null)
        {
            return buttonObject.original_base_64_image;
        }
        
        var rbgaColor = this.hexToRgbA(threadObject.color);
        
        var designImage = document.createElement("img");
        designImage.setAttribute('src', buttonObject.original_base_64_image);
        designImage.setAttribute('class', 'preview-image');
                
        var designImageCanvas = this.create_canvas_for_image(designImage, 230, 300);
        var designImageContext = designImageCanvas.getContext('2d');
        var designImageData = designImageContext.getImageData(0, 0, designImageCanvas.width, designImageCanvas.height);
        var designData = designImageData.data;
                
        for (var i = 0; i < designData.length; i += 4) 
        {
            if(designData[i + 3] > 240 && designData[i] < 50 && designData[i + 1] < 50 && designData[i + 2] < 50)
            {              
                designData[i]     = rbgaColor[0];     
                designData[i + 1] = rbgaColor[1]; 
                designData[i + 2] = rbgaColor[2]; 
            }
        }
        
        designImageContext.putImageData(designImageData, 0, 0);
        return designImageCanvas.toDataURL();
                
    };
    
    this.hexToRgbA = function(hex)
    {
        hex = hex.replace('#','');
        r = parseInt(hex.substring(0,2), 16);
        g = parseInt(hex.substring(2,4), 16);
        b = parseInt(hex.substring(4,6), 16);
        var rgbaArr = [r, g, b, 255];
        return rgbaArr;
    };

    
    /**
     * Checks if the dependent menu supplied has been unlocked by
     * a selected option
     * @param {type} menu_id
     * @returns {true or false}
     */
    this.menuUnlocked = function(menu_id)
    {        
        for(var key in this.product.product_menus)
        {
            var menu = this.product.product_menus[key];
            
            for(var option_key in menu.design_options)
            {
                var option = menu.design_options[option_key];
                
                if(option.selected && option.dependent_menus.indexOf(menu_id) > -1)
                {
                    return true;
                }
            }
        }       
        return false;
    };
    
    this.loadMenus = function(parent_element_id)
    {
        var Instance = this;
        
        $('#' + parent_element_id).fadeOut("slow", function()
        {
            $('#' + parent_element_id).empty();
            
            for(var key in Instance.product.product_menus)
            {
                var menu = Instance.product.product_menus[key];

                if((parseInt(Instance.category_selected) === 1 || parseInt(Instance.category_selected) === 2))
                {
                    if((parseInt(menu.category) === parseInt(Instance.category_selected)))
                    {
                        if(parseInt(menu.is_independent) || Instance.menuUnlocked(menu.id))
                        {
                            var menu_element = $('<a>').attr('href', '#').attr('class', 'list-group-item').append(
                                $('<span>').attr('class', 'tab').append(menu.name));

                            menu_element.val(menu.id);
                            menu_element.click(function()
                            {
                                Instance.menu_selected = this.value;
                                Instance.loadOptions(this.value);
                            });

                            menu_element.appendTo($('#' + parent_element_id));
                        }
                    }
                }                 
            }
            
            $('#' + parent_element_id).fadeIn("slow");
        });
        
        
    };
    
    this.loadMixMenus = function(parent_element_id)
    {
        var Instance = this;
        
        this.loadAllFabrics();
        
        $('#' + parent_element_id).fadeOut("slow", function()
        {
            $('#' + parent_element_id).empty();
            
            for(var key in Instance.product.product_menus)
            {
                var menu = Instance.product.product_menus[key];

                if(parseInt(Instance.category_selected) === 3)
                {
                    // Mix menus are design menus
                    if(parseInt(menu.category) === 2)
                    {
                        if(parseInt(menu.is_mixed_fabric_menu) === 1)
                        {
                            var outer_mix_element = Instance.createMixElement(menu, 'outer');
                            outer_mix_element.appendTo($('#' + parent_element_id));
                        }
                        
                        if(parseInt(menu.inner_contrast_support) === 1)
                        {
                            var inner_mix_element = Instance.createMixElement(menu, 'inner');
                            inner_mix_element.appendTo($('#' + parent_element_id));
                        }
                    }
                }                 
            }
            
            $('#' + parent_element_id).fadeIn("slow");
        });
    };
    
    this.loadMeasurementMenus = function()
    {
        $(this.options_list_id).empty();

        $(this.sub_menu_list_id).empty();

        $(this.sub_menu_list_id).append(
            $('<a>').attr("data-toggle", "modal").attr("data-target", this.measurment_modal_id).attr('href', '#').attr('class', 'list-group-item').append(
                $('<span>').attr('class', 'tab').append("Enter Measurements")
        )); 

        $(this.sub_menu_list_id).append(
            $('<a>').attr("data-toggle", "modal").attr("data-target", this.user_modal_id).attr('href', '#').attr('class', 'list-group-item').append(
                $('<span>').attr('class', 'tab').append("Request Tailor")
        ));
    };
            
    this.createMixElement = function(menu, type)
    {
        var Instance = this;
        
        var selected = false;
        
        if((menu.inner_mix_selected && type === 'inner')|| (menu.outer_mix_selected && type === 'outer'))
        {
            selected = true;
        }
                       
        var checkbox_element = $('<input>').attr('type', 'checkbox').attr('checked', selected).attr('id', type + '_mixmenu_' + menu.id).attr('auto-complete', 'off');
        
        checkbox_element.click(function()
        {
            if(type === 'inner')
            {
                Instance.product.product_menus[menu.id].inner_mix_selected = this.checked ? 1 : 0;
            }
            
            if(type === 'outer')
            {
                Instance.product.product_menus[menu.id].outer_mix_selected = this.checked ? 1 : 0;               
            }
            
            Instance.refreshMenu(menu);
        });
        
        var mix_element = 
                        $('<div>').attr('class', 'list-group-item').append(
                            $('<div>').attr('class', 'form-group').append(
                                checkbox_element
                                ).append(
                                $('<div>').attr('class', 'btn-group').append(
                                    $('<label>').attr('for', type + '_mixmenu_' + menu.id).attr('class', 'btn btn-info').append(
                                        $('<span>').attr('class', 'glyphicon glyphicon-ok')).append(
                                        $('<span>').html('.'))).append(
                                    $('<label>').attr('for', type + '_mixmenu_' + menu.id).attr('class', 'btn btn-default active').html(this.capitalizeFirstLetter(type) + ' ' + menu.name))));

        return mix_element;
    };
    
    /**
     * THis function clears and redraws the menu. 
     * It is usally called when there is a change to the menu
     * @param {type} menu
     * @returns {undefined}
     */
    this.refreshMenu = function(menu)
    {
        var Instance = this;
        
        var menu_element = $("#front_menu_" + menu.id);
        
        menu_element.fadeOut("fast", function()
        {
            menu_element.empty();
            
            Instance.drawMenu(menu, 'front');
                        
            menu_element.fadeIn("fast");
        });
        
        var back_menu_element = $("#back_menu_" + menu.id);
        
        back_menu_element.fadeOut("fast", function()
        {
            back_menu_element.empty();
            
            Instance.drawMenu(menu, 'back');
                        
            back_menu_element.fadeIn("fast");
        });
        
        
    };
    

    
    /**
     * Gets the design parameters that will be saved
     * @returns {Product.getDesignParameters.designParameters}
     */
    this.getDesignParameters = function()
    {
        var designParameters = 
        {
            product_id : this.product.id,
            fabric_id : -1,
            mix_fabric_id : -1,
            button_id : -1,
            thread_id : -1,
            measurements : -1,
            mix_menus : [],
            options : [],
            design_image : '',
            request_tailor : false
        };
        
        designParameters.fabric_id = this.product.default_fabric !== null ? this.product.default_fabric.fabric_id : -1;
        designParameters.mix_fabric_id = this.product.mix_fabric !== null ? this.product.mix_fabric.fabric_id : -1;
        designParameters.button_id = this.product.default_button !== null ? this.product.default_button.id : -1;
        designParameters.thread_id = this.product.default_thread !== null ? this.product.default_thread.id : -1;
        designParameters.measurements = this.product.measurements;
        designParameters.request_tailor = this.product.request_tailor;
        
        for(var key in this.product.product_menus)
        {
            var menu = this.product.product_menus[key];
            
            var menu_object = 
            {
                id : menu.id,
                inner_mix_selected : menu.inner_mix_selected,
                outer_mix_selected : menu.outer_mix_selected                   
            };
            
            designParameters.mix_menus[menu_object.id] = menu_object;
            
            // Interested only in saving design options
            if(parseInt(menu.category) !== 2)
            {
                continue;
            }
            
            for(var option_key in menu.design_options)
            {
                var option = menu.design_options[option_key];
                
                var option_object = 
                {
                    id : option.id,
                    selected : option.selected                   
                };
                
                designParameters.options[option_object.id] = option_object;               
            }
        }
        
        return designParameters;
               
    };
    
    this.capitalizeFirstLetter = function(string) 
    {
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    this.selectFabric = function(fabric_selected)
    {
        this.product.default_fabric = this.product.fabrics[fabric_selected];
        
        this.draw(false);
    };
    
    this.loadAllFabrics = function()
    {
        $("#option-list").empty();
        
        var Instance = this;
        
        for(var key in this.product.fabrics)
        {
            var design_fabric = this.product.fabrics[key];
            design_fabric.base_64_image = design_fabric.original_base_64_image;    
            var link_element = $('<a>');

            $('<img>').attr("src", design_fabric.base_64_image)
                    .attr("height", "100")
                    .attr("width", "96").appendTo(link_element);

            var list_element = $('<li>').append(link_element);
            list_element.val(design_fabric.fabric_id);

            list_element.click(function()
            {
                Instance.product.mix_fabric = Instance.product.fabrics[this.value];
                Instance.draw(false);
            });

            $("#option-list").append(list_element);

            Instance.optionsSly.reload();
        }
    };
    
    this.invertFabric = function()
    {
        var tmp_fabric = this.product.default_fabric;
        this.product.default_fabric = this.product.mix_fabric;
        this.product.mix_fabric = tmp_fabric;
        this.draw(false);
    };
    
    this.loadOptions = function(menu_selected)
    {
        var Instance = this;
        
        var menu = this.product.product_menus[menu_selected];
        
        $("#option-list").empty();
        
        if(parseInt(menu.category) === 1)
        {
            
            for(var option_key in menu.design_options)
            {
                var design_option = menu.design_options[option_key];
                
                var link_element = $('<a>');
                
                $('<img>').attr("src", design_option.original_base_64_image)
                        .attr("height", "100")
                        .attr("width", "96").appendTo(link_element);
                
                var list_element = $('<li>').append(link_element);
                list_element.val(design_option.fabric_id);
                
                list_element.click(function()
                {
                    Instance.selectFabric(this.value);
                });
                
                $("#option-list").append(list_element);

                Instance.optionsSly.reload();
            }
        }
        
        if(parseInt(menu.category) === 2)
        {
            if(parseInt(menu.is_back_option_menu) === 1 && this.current_side === 'front')
            {
                this.draw(this.designDomElementID, 'back');
            }
            
            if(parseInt(menu.is_back_option_menu) === 0 && this.current_side === 'back')
            {
                this.draw(this.designDomElementID, 'front');
            }
            
            for(var option_key in menu.design_options)
            {
                var design_option = menu.design_options[option_key];
                
                var link_element = $('<a>');
                
                $('<img>').attr("src", design_option.base_64_thumbnail)
                        .attr("height", "100")
                        .attr("width", "96").appendTo(link_element);
                
                var list_element = $('<li>').append(link_element);
                list_element.val(design_option.id);
                
                list_element.click(function()
                {
                    Instance.selectOption(this.value);
                });
                
                $("#option-list").append(list_element); 
            }
            
            Instance.optionsSly.reload();
        }
    };
    
    this.LoadThreadsToSly = function()
    {
        var Instance = this;
        
        $("#button-design-threads-list").empty();
        
        for (var key in this.product.threads) 
        {

            var thread_object = this.product.threads[key];
            
            thread_object.base_64_image = thread_object.original_base_64_image;
            
            var link_element = $('<a>');
            
            $('<img>').attr("src", thread_object.base_64_image)
                    .attr("height", "100")
                    .attr("width", "96").appendTo(link_element);

            var list_element = $('<li>').append(link_element);
            list_element.val(thread_object.id);

            list_element.click(function()
            {
                Instance.selectThread(this.value);
            });

            $("#button-design-threads-list").append(list_element);
        }
        
        Instance.threadsSly.reload();
    };
    
    this.LoadButtonOptions = function()
    {
        var Instance = this;
        
        $("#option-list").empty();
        
        for(var key in this.product.buttons)
        {
            var button_object = this.product.buttons[key];
                       
            var link_element = $('<a>').attr("data-toggle", "modal").attr("data-target", "#buttonsModal");
            
            $('<img>').attr("src", button_object.base_64_image)
                    .attr("height", "100")
                    .attr("width", "96").appendTo(link_element);

            var list_element = $('<li>').append(link_element);
            list_element.val(button_object.id);

            list_element.click(function()
            {
                Instance.selectButton(this.value);
            });
            
            $("#option-list").append(list_element);   
        }
        
        Instance.optionsSly.reload();
    };
    
    this.selectButton = function(button_id)
    {
        
        var button_object = this.product.buttons[button_id];
        
        $("#design_button_image").attr("src", button_object.base_64_image);
        
        $("#selected-button-name").text(button_object.name);
    };
    
    this.selectThread = function(thread_id)
    {
        var thread_object = this.product.threads[thread_id];
        
        // These values are stored in the product after the user confirms his selection
        this.tmp_selected_thread = thread_object;       
        this.tmp_base_64_button_image = this.blendThreadToButton(this.product.default_button, thread_object);
        
        $("#design_button_image").attr("src", this.tmp_base_64_button_image);
        
    };
    
    this.applySelectedThread = function()
    {
        this.product.default_thread = this.tmp_selected_thread;
        this.product.default_button.base_64_image = this.tmp_base_64_button_image;
        this.product.buttons[this.product.default_button.id].base_64_image = this.tmp_base_64_button_image;
        // Redraw product with new buttons. 
        // Further optimization would require a separate
        // function to draw/refresh buttons
        this.draw(this.designDomElementID, this.current_side);
        
    };
}
