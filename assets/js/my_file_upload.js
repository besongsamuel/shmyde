/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    function My_Uploader(parameters)
    {
        /**
         * When this is true, there is no image added for upload in single 
         * mode. When an image is deleted in single mode, this value turns
         * to true
         */
        this.can_add = true;
        
        /**
         * This represents the id of the item associated with the image. 
         */
        this.item_id = parameters.item_id;
        
        /**
         * This represents the link that is called when an image is deleted
         */
        this.delete_link =  parameters.delete_link;
        
        /**
         * This is the table name into which is stored the image when the object is 
         * saved or edited
         */
        this.table_name = parameters.table_name;
        
        /**
         * This is the image directory into which the image is stored or deleted
         */
        this.image_dir = parameters.image_dir;
        
        /**
         * The root parameter represents the HTML element unto which the the image 
         * elements are added
         */
        this.root = parameters.root;
        
        /**
         * This parameter stores the mode of the uploader. Either single or multiple 
         */
        this.mode = parameters.mode;
        
        /**
         * When this is true, the user can view the different paramaters
         * associated with the image. 
         */
        this.show_parameters = parameters.show_parameters;
        
        /**
         * This is a reference to the uploader form
         */
        this.form = parameters.form;
        
        /**
         * This is the base name that shall be used to store the image. Images in the 
         * directry are stored as this.image_name_this.item_id_this.image_id
         */
        this.image_name = parameters.image_name;
        
        /**
         * This is the file name that shall be used to upload the file. When more than
         * two uploaders are available on the same page, different names should be used
         */
        this.file_name = parameters.file_name;
        
        /**
         * This is a reference to the uploader
         * @returns {My_Uploader}
         */
        this.get_caller = function(){

            return this;

        };
        
        /**
         * This is a helper method to create guids for image ID's 
         */
        this.guid = function() 
        {
            function s4() 
            {
                return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
            }
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        }
        
        /**
         * This function deletes an image by calling the Admin.delete_image function 
         * in the server
         * @param {type} image_id
         * @returns {undefined}
         */
        this.delete_image = function(image_id){
            
            var xmlhttp = new XMLHttpRequest();

            var parameters = "item_id=".concat(this.item_id).concat("&image_id=").concat(image_id).concat("&table_name=").concat(this.table_name).concat("&image_dir=").concat(this.image_dir);

            var site_url = this.delete_link;

            xmlhttp.open("POST", site_url, true);

            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xmlhttp.send(parameters);
        };
        
        /**
         * This function adds an image uploader
         * @param {type} image_id The image ID of the uploader
         * @param {type} pos_x_value The X positition
         * @param {type} pos_y_value The Y position
         * @param {type} is_inner_value When this value is true, it means the image is used as an inner contrast
         * @param {type} depth_value The depth of the image
         * @returns {undefined}
         */
        this.add_upload_button = function(image_id, pos_x_value, pos_y_value, is_inner_value, depth_value, applied_to_back_value){
            
            // It is a new image being created, we create a new ID for the image
            if(typeof image_id === 'undefined')
            {
                image_id = this.guid();
            }
            
            if(this.mode === 'single')
            {
                if(this.can_add === true)
                {
                    var container = this.create_image_upload_element(
                            image_id.toString(), 
                            this.image_name, 
                            this.file_name, 
                            parseInt(pos_x_value), 
                            parseInt(pos_y_value), 
                            parseInt(is_inner_value), 
                            parseInt(depth_value),
                            parseInt(applied_to_back_value));

                    this.root.append(container);
                    this.can_add = false;
                }
            }

            if(this.mode === 'multiple')
            {
                var container = 
                    this.create_image_upload_element(
                        image_id.toString(), 
                        this.image_name, 
                        this.file_name, 
                        parseInt(pos_x_value), 
                        parseInt(pos_y_value), 
                        parseInt(is_inner_value), 
                        parseInt(depth_value),
                        parseInt(applied_to_back_value));

                this.root.append(container);
            }
        };

        
        this.create_image_upload_element = function(curr_element, image_name, file_name, pos_x_value, pos_y_value, is_inner_value, depth_value, applied_to_back_value)
        {
            var caller = this.get_caller();
            
            // Create the Upload Element Container
            var container = document.createElement("DIV");
            container.setAttribute("class", "col-sm-5");
            container.setAttribute("style", "margin : 5px;");
            
            // Create the Top Container that contains the Upload button
            var top_container = document.createElement("DIV");
            top_container.setAttribute("class", "row");
            
            // Create the upload button
            var upload_button_container = document.createElement("DIV");
            upload_button_container.setAttribute("class", "input-group col-sm-12");
            var upload_button = document.createElement("INPUT");
            upload_button.setAttribute("type", "file");
            upload_button.setAttribute("class", "form-control");
            upload_button.setAttribute("name", curr_element);
            upload_button.setAttribute('current_element', curr_element);
            upload_button.onchange = function() 
            {
               var image_element = document.getElementById(caller.image_name.concat("_").concat(this.getAttribute('current_element')));
               if (this.files && this.files[0]) 
               {
                    var reader = new FileReader();
                    reader.onload = function (e) 
                    {
                        image_element.setAttribute("src", e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }

            };
            
            upload_button_container.appendChild(upload_button);
            top_container.appendChild(upload_button_container);
            
            // Create bottom container. This will contain the image and 
            // it's asdsociated attributes
            var bottom_container = document.createElement("DIV");
            bottom_container.setAttribute("class", "row");
            
            var left_container = document.createElement("DIV");
            left_container.setAttribute("class", "col-sm-6");
            
            var right_container = document.createElement("DIV");
            right_container.setAttribute("class", "col-sm-6");
            
            var image_container = document.createElement("DIV");
            image_container.setAttribute("style", "padding: 10px; border-style: outset;");
            var image = document.createElement("img");
            image.setAttribute("style", "width : 100%; height: 100%;");
            image.setAttribute("id", image_name.concat("_").concat(curr_element)); 
            image_container.appendChild(image);
            left_container.appendChild(image_container);
            
            if(!caller.show_parameters)
            {
                left_container.setAttribute("class", "col-sm-12");
                bottom_container.appendChild(left_container);
                container.appendChild(top_container);
                return container;
            }
            
            // Create Delete Button
            var delete_button_container = document.createElement("DIV");
            delete_button_container.setAttribute("class", "input-group");
            var delete_button = document.createElement("BUTTON");
            delete_button.setAttribute("type", "button");
            delete_button.setAttribute("class", "btn btn-danger");
            delete_button.setAttribute("current_element", curr_element);
            var text = document.createTextNode("Delete");   
            delete_button.appendChild(text);
            
            delete_button.onclick = function()
            {
                
                var image_id = this.getAttribute("current_element");
                
                var parent = document.getElementById(image_id);
                
                var parent_container = parent.parentElement;

                caller.can_add = true;

                caller.delete_image(image_id);

                parent_container.removeChild(parent);

            };
            delete_button_container.appendChild(delete_button);
            
            // Create Depth Input Element
            var depth_container = document.createElement("DIV");
            depth_container.setAttribute("class", "input-group");
            var depth_label = document.createElement("label");
            var depth_text = document.createTextNode("Depth"); 
            depth_label.appendChild(depth_text);
            var depth = document.createElement("input");
            depth.setAttribute("type", "number");
            depth.setAttribute("name", "depth[".concat(curr_element).concat("]"));
            depth.setAttribute("class", "form-control");
            depth.value = depth_value;
            depth_container.appendChild(depth_label);
            depth_container.appendChild(depth);
            
            // Create Contrast Input Element
            var contrast_container = document.createElement("DIV");
            contrast_container.setAttribute("class", "checkbox input-group");
            var contrast_label = document.createElement("label");
            var contrast_text = document.createTextNode("Is Inner Contrast"); 
            var contrast = document.createElement("input");
            contrast.setAttribute("type", "checkbox");
            contrast.setAttribute("name", "is_inner[".concat(curr_element).concat("]"));
            contrast.setAttribute("value", "1");
            contrast.checked = Boolean(is_inner_value);
            contrast_label.appendChild(contrast);
            contrast_label.appendChild(contrast_text);
            contrast_container.appendChild(contrast_label);
            
            // Create Back Item Checkbox
            var back_item_container = document.createElement("DIV");
            back_item_container.setAttribute("class", "checkbox input-group");
            var back_item_label = document.createElement("label");
            var back_item_text = document.createTextNode("Applied to the back"); 
            var back_item = document.createElement("input");
            back_item.setAttribute("type", "checkbox");
            back_item.setAttribute("name", "is_back_image[".concat(curr_element).concat("]"));
            back_item.setAttribute("value", "1");
            back_item.checked = Boolean(applied_to_back_value);
            back_item_label.appendChild(back_item);
            back_item_label.appendChild(back_item_text);
            back_item_container.appendChild(back_item_label);

            var x_container = document.createElement("DIV");
            x_container.setAttribute("class", "input-group");
            var xpos_label = document.createElement("label");
            var xpos_text = document.createTextNode("Position X"); 
            xpos_label.appendChild(xpos_text);

            var xpos = document.createElement("input");
            xpos.setAttribute("type", "number");
            xpos.setAttribute("name", "pos_x[".concat(curr_element).concat("]"));
            xpos.setAttribute("id", "pos_y");
            xpos.setAttribute("class", "form-control");
            xpos.value = pos_x_value;

            var y_container = document.createElement("DIV");
            y_container.setAttribute("class", "input-group");
            var ypos_label = document.createElement("label");
            var ypos_text = document.createTextNode("Position Y"); 
            ypos_label.appendChild(ypos_text);

            var ypos = document.createElement("input");
            ypos.setAttribute("type", "number");
            ypos.setAttribute("name", "pos_y[".concat(curr_element).concat("]"));
            ypos.setAttribute("id", "pos_y");
            ypos.setAttribute("class", "form-control");
            ypos.value = pos_y_value;


            x_container.appendChild(xpos_label);
            x_container.appendChild(xpos);
            y_container.appendChild(ypos_label);
            y_container.appendChild(ypos);

            right_container.appendChild(depth_container);
            right_container.appendChild(back_item_container);
            right_container.appendChild(contrast_container);
            right_container.appendChild(x_container);
            right_container.appendChild(y_container);
            right_container.appendChild(delete_button_container);

            bottom_container.appendChild(left_container);
            
            bottom_container.appendChild(right_container);

            container.appendChild(top_container);
            container.appendChild(bottom_container); 

            return container;
        };

    }
    /**
     * This function deletes an image by calling a funtion on the server
     * @param {type} image_id The image id of the image being deleted
     * @returns {undefined}
     */
    function delete_image(image_id){
        
        console.log("Deleting Image with ID : " + image_id);
        
        var xmlhttp = new XMLHttpRequest();   

        var site_url = this.delete_link;

        site_url = site_url.concat("/").concat(this.item_id).concat("/").concat(image_id).concat("/").concat(this.table_name).concat("/").concat(this.image_dir);

        xmlhttp.open("GET", site_url, true);

        xmlhttp.send();
    }
    
    /**
     * This function submits the uploader form
     * @param {type} uploader The uploader to be submitted
     * @returns {undefined}
     */
    function submit_upload_form(uploader, dir_name, database_table_name){
        
        // Create Form Data
        var mu_formData = new FormData(document.getElementById(uploader.form.attr('id')));
        
        // Id of the associated item
        mu_formData.append("id", uploader.item_id);
        // Image directory where the images will be uploaded to
        mu_formData.append("dir", dir_name);
        // Database table where the data will be stored
        mu_formData.append("table", database_table_name);

        $.ajax({
               url : uploader.form.attr('action'),
               type : 'POST',
               data : mu_formData,
               processData: false,  
               contentType: false,  
               success : function(data) 
               {
                   //alert(data);
               }
        });

    }

    




