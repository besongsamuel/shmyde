/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    /**
     * This function gets the free array position at which an item 
     * should be inserted
     * @param {type} array The array to be checked
     * @returns {Number}
     */
    function get_insert_id(array){

        for (var i = 0; i < array.length; i++) {

            if(array[i] === -1)
                return i;

        }


    }


    function My_Uploader(parameters)
    {
        /**
         * When this is true, there is no image added for upload in single 
         * mode. When an image is deleted in single mode, this value turns
         * to true
         */
        this.can_add = true;
        
        /**
         * This represents a pool of image id's from which new image items are 
         * added. A value of -1 means the position is free
         */
        this.image_ids = [-1,-1,-1,-1,-1,-1,-1,-1,-1,-1 ];
        
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
         * This function deletes an image by calling the Admin.delete_image function 
         * in the server
         * @param {type} image_id
         * @returns {undefined}
         */
        this.delete_image = function(image_id){
            
            console.log("Deleting Image with ID : " + image_id);
            
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
            
            
            if(this.mode === 'single'){


                if(typeof image_id === 'undefined'){

                    image_id = 0;
                }

                if(this.can_add === true){
                    
                    console.log("Adding a new Upload Item in Single Image Mode");
                    
                    var container = this.create_image_upload_element(
                            parseInt(image_id), 
                            this.image_name, 
                            this.file_name, 
                            parseInt(pos_x_value), 
                            parseInt(pos_y_value), 
                            parseInt(is_inner_value), 
                            parseInt(depth_value),
                            parseInt(applied_to_back_value));

                    this.root.append(container);

                    this.image_ids[image_id] = image_id;

                    this.can_add = false;


                }


            }

            if(this.mode === 'multiple'){

                console.log("Adding a new Upload Item in Multiple Image Mode");
                
                var insert_id = get_insert_id(this.image_ids);

                 if(typeof image_id !== 'undefined'){

                    insert_id = image_id;
                }

                var container;

                if(typeof image_id === 'undefined'){

                    container = this.create_image_upload_element(parseInt(insert_id), 
                    this.image_name, 
                    this.file_name, 
                    parseInt(pos_x_value), 
                    parseInt(pos_y_value), 
                    parseInt(is_inner_value), 
                    parseInt(depth_value),
                    parseInt(applied_to_back_value));
                }
                else{
                    container = this.create_image_upload_element(
                            parseInt(image_id), 
                    this.image_name, 
                    this.file_name, 
                    parseInt(pos_x_value), 
                    parseInt(pos_y_value), 
                    parseInt(is_inner_value), 
                    parseInt(depth_value),
                    parseInt(applied_to_back_value));
                }

                this.image_ids[insert_id] = insert_id;

                this.root.append(container);

            }

        };

        
        this.create_image_upload_element = function(curr_element, image_name, file_name, pos_x_value, pos_y_value, is_inner_value, depth_value, applied_to_back_value){
            
            
            var caller = this.get_caller();

            var container = document.createElement("DIV");
            container.setAttribute("class", "col-sm-5");
            container.setAttribute("id", curr_element);
            container.setAttribute("style", "margin : 5px;")
            
            var top_container = document.createElement("DIV");
            top_container.setAttribute("style", "width : 410px");
            
            var upload_button_container = document.createElement("DIV");
            upload_button_container.setAttribute("style", "width : 100%;");
            var upload_button = document.createElement("INPUT");
            upload_button.setAttribute("type", "file");
            upload_button.setAttribute("class", "form-control");
            upload_button.setAttribute("name", file_name.concat("_").concat(curr_element));
            upload_button.setAttribute("id", file_name.concat("_").concat(curr_element));
            upload_button.setAttribute("current_element", curr_element);
            upload_button.onchange = function() {
                
               var image_element = document.getElementById(caller.image_name.concat("_").concat(this.getAttribute('current_element')));

               if (this.files && this.files[0]) {

                        var reader = new FileReader();

                        reader.onload = function (e) {

                            image_element.setAttribute("src", e.target.result);
                        };


                        reader.readAsDataURL(this.files[0]);
                }

            };
            
            upload_button_container.appendChild(upload_button);
            
            top_container.appendChild(upload_button_container);
            
            
            
            var bottom_container = document.createElement("DIV");
            bottom_container.setAttribute("style", "width : 410px");
            
            
            var left_container = document.createElement("DIV");
            left_container.setAttribute("style", "float : left; margin-right : 10px;, width : 200px;");
            
            var right_container = document.createElement("DIV");
            right_container.setAttribute("style", "float : right; width : 200px;");
            
            
            var image_container = document.createElement("DIV");
            image_container.setAttribute("style", "margin-top : 5px; margin-bottom : 5px;");
            var image = document.createElement("img");
            image.setAttribute("style", "width : 200px; height : 250px;");
            image.setAttribute("id", image_name.concat("_").concat(curr_element)); 
            image_container.appendChild(image);
            
            left_container.appendChild(image_container);
            
            var delete_button_container = document.createElement("DIV");
            delete_button_container.setAttribute("style", "margin-top : 5px; margin-bottom : 5px; float : right;");
            var delete_button = document.createElement("BUTTON");
            delete_button.setAttribute("type", "button");
            delete_button.setAttribute("class", "btn btn-danger");
            delete_button.setAttribute("current_element", curr_element);

            var text = document.createTextNode("Delete");   
            delete_button.appendChild(text);
            delete_button.onclick = function(){
                
                
                var image_id = this.getAttribute("current_element");
                
                var parent = document.getElementById(image_id);
                
                var parent_container = parent.parentElement;

                caller.image_ids[image_id] = -1;

                caller.can_add = true;

                caller.delete_image(image_id);

                parent_container.removeChild(parent);

            };
            delete_button_container.appendChild(delete_button);

            var depth_container = document.createElement("DIV");
            depth_container.setAttribute("style", "margin-top : 5px; margin-bottom : 5px; ");
            depth_container.setAttribute("class", "form-group");

            var depth_label = document.createElement("label");
            depth_label.setAttribute("for", "depth");
            var depth_text = document.createTextNode("Depth"); 
            depth_label.appendChild(depth_text);

            var depth = document.createElement("input");
            depth.setAttribute("type", "number");
            depth.setAttribute("name", "depth[".concat(curr_element).concat("]"));
            depth.setAttribute("id", "depth");
            depth.setAttribute("class", "form-control");
            depth.setAttribute("style", "width : 100%; margin-left : 5px; margin-right : 5px;");
            depth.value = depth_value;

            depth_container.appendChild(depth_label);
            depth_container.appendChild(depth);

            var contrast_container = document.createElement("DIV");
            contrast_container.setAttribute("style", "margin-top : 5px; margin-bottom : 5px;");
            contrast_container.setAttribute("class", "form-group");

            var contrast_label = document.createElement("label");
            contrast_label.setAttribute("for", "contrast");
            var contrast_text = document.createTextNode("Is Inner Contrast"); 
            contrast_label.appendChild(contrast_text);
            
            var contrast = document.createElement("input");
            contrast.setAttribute("type", "checkbox");
            contrast.setAttribute("name", "is_inner[".concat(curr_element).concat("]"));
            contrast.setAttribute("id", "is_inner");
            contrast.setAttribute("class", "form-control");
            contrast.setAttribute("value", "1");
            contrast.setAttribute("style", "width : 100%;  margin-left : 5px; margin-right : 5px;");
            contrast.checked = Boolean(is_inner_value);

            contrast_container.appendChild(contrast_label);
            contrast_container.appendChild(contrast);
            
            var back_item_container = document.createElement("DIV");
            back_item_container.setAttribute("style", "margin-top : 5px; margin-bottom : 5px;");
            back_item_container.setAttribute("class", "form-group");

            var back_item_label = document.createElement("label");
            back_item_label.setAttribute("for", "back_item");
            var back_item_text = document.createTextNode("Applied to the back"); 
            back_item_label.appendChild(back_item_text);
            
            var back_item = document.createElement("input");
            back_item.setAttribute("type", "checkbox");
            back_item.setAttribute("name", "is_back_image[".concat(curr_element).concat("]"));
            back_item.setAttribute("id", "is_back_image");
            back_item.setAttribute("class", "form-control");
            back_item.setAttribute("value", "1");
            back_item.setAttribute("style", "width : 100%;  margin-left : 5px; margin-right : 5px;");
            back_item.checked = Boolean(applied_to_back_value);

            back_item_container.appendChild(back_item_label);
            back_item_container.appendChild(back_item);


            var position_container = document.createElement("DIV");
            position_container.setAttribute("style", "margin-top : 5px; margin-bottom : 5px;");
            position_container.setAttribute("class", "form-group");

            var xpos_label = document.createElement("label");
            xpos_label.setAttribute("for", "xpos");
            var xpos_text = document.createTextNode("Position X"); 
            xpos_label.appendChild(xpos_text);

            var xpos = document.createElement("input");
            xpos.setAttribute("type", "number");
            xpos.setAttribute("name", "pos_x[".concat(curr_element).concat("]"));
            xpos.setAttribute("id", "pos_y");
            xpos.setAttribute("class", "form-control");
            xpos.setAttribute("style", "width : 100%; margin-left : 5px; margin-right : 5px;");
            xpos.value = pos_x_value;

            var ypos_label = document.createElement("label");
            ypos_label.setAttribute("for", "ypos");
            var ypos_text = document.createTextNode("Position Y"); 
            ypos_label.appendChild(ypos_text);

            var ypos = document.createElement("input");
            ypos.setAttribute("type", "number");
            ypos.setAttribute("name", "pos_y[".concat(curr_element).concat("]"));
            ypos.setAttribute("id", "pos_y");
            ypos.setAttribute("class", "form-control");
            ypos.setAttribute("style", "width : 100%; margin-left : 5px; margin-right : 5px;");
            ypos.value = pos_y_value;

            position_container.appendChild(xpos_label);
            position_container.appendChild(xpos);

            position_container.appendChild(ypos_label);
            position_container.appendChild(ypos);

            right_container.appendChild(depth_container);
            right_container.appendChild(back_item_container);
            right_container.appendChild(contrast_container);
            right_container.appendChild(position_container);
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
    function submit_upload_form(uploader){
        
        console.log("Submitting Upload Form");
        console.log("******************** Parameters ********************");
        
        var form_id = uploader.form.attr('id');

        var parameters = new Object();

        parameters.file_name = uploader.file_name;
        parameters.image_name = uploader.image_name;
        parameters.image_dir = uploader.image_dir;
        parameters.table_name = uploader.table_name;
        parameters.item_id = uploader.item_id;
        
        console.log(parameters);
        
        $("#".concat(form_id).concat(" #parameters")).val(JSON.stringify(parameters));

        var mu_formData = new FormData();

        var other_data = uploader.form.serializeArray();

        $.each(other_data,function(key,input){

            mu_formData.append(input.name,input.value);

        });


         for (var i = 0; i < uploader.image_ids.length; i++) { 

            if(uploader.image_ids[i] === -1 || typeof uploader.image_ids[i] === 'undefined')
                continue;


            var id = "#".concat(uploader.file_name).concat("_").concat(uploader.image_ids[i]);
            
            var filename = (uploader.file_name).concat("_").concat(uploader.image_ids[i]);

            if($(id).length > 0 && $(id)[0].files.length > 0){

                mu_formData.append(filename, $(id)[0].files[0]);

            }

        }


        $.ajax({
               url : uploader.form.attr('action'),
               type : 'POST',
               data : mu_formData,
               processData: false,  
               contentType: false,  
               success : function(data) {

                   //alert(data);

               }
        });

    }

    




