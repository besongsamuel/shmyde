<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DesignData {
    
    public $design = array();
    
    public $product_id;
    
    public $measurements = array();
    
    public $user;
    
    public $currentButton;
    
    public $currentThread;
    
    /**
     * This is a list of menu id's that have their inner fabric
     * as the mix fabric
     * @var type 
     */
    public $SelectedInnerMix = array();
    
    /**
     * This is a list of menu id's that have their outer fabric
     * as the mix fabric
     * @var type 
     */
    public $SelectedOuterMix = array();

    public $defaultFabric;
    
    public $mixFabric;

    public $inner_mix_Fabrics = array();
    
    public $outer_mix_Fabrics = array();
            
    function LoadParameters($product_id) {
        
        $this->product_id = $product_id;
        
        $CI =& get_instance();
        
        $CI->load->model('admin_model');
        
        $this->design = $CI->admin_model->get_option_design_data($product_id, 'front');
                
        $this->measurements = $CI->admin_model->get_measurements_data($product_id);
        
        $this->user = new UserData('Samuel', 'Besong', 'Rue L. O. David', 'Montreal', 'H2E1M1', '5147067120', 'samuel@samuel.com');
               
    }
    
}

class ImageData{
    
    public $name;
    
    public $depth;
    
    public $item_id;
    
    public $is_inner;
    
    public $is_back_image;
        
    public $fabric_id = -1;
    
    public $pos_x;
    
    public $pos_y;
    
    public $id;
    
    public $buttons = array();


    public function __construct($id, $item_id, $name, $is_inner, $is_back_image, $depth, $pos_x, $pos_y){
                
        $this->name = $name;
        
        $this->item_id = $item_id;
                
        $this->id = $id;
        
        $this->is_inner = $is_inner;
        
        $this->is_back_image = $is_back_image;
        
        $this->depth = $depth;
        
        $this->pos_x = $pos_x;
        
        $this->pos_y = $pos_y;
    }
    
}

class OptionData {
    
    public $id;
    
    public $name;
    
    public $description;
    
    public $images = array(); 

    function __construct($id, $name, $description) {
        
        $this->id = $id;
        
        $this->name = $name;
        
        $this->description = $description;
        
    }

}

class ButtonData{
    
    public $button_id;
    
    public $pos_x;
    
    public $pos_y;
    
    public $image_name;
    
    public $depth;
    
    /**
     *The option or product associated with this button. When the product is changed,
     * the corresponding buttons must disappear
     * @var type 
     */
    public $item_id;
    
    
    function __construct($button_id, $image_name, $pos_x, $pos_y, $depth, $item_id) {
        
        $this->id = $button_id;
        
        $this->image_name = $image_name;
        
        $this->pos_x = $pos_x;
        
        $this->pos_y = $pos_y;
        
        $this->depth = $depth;
        
        $this->item_id = $item_id;
        
    }
    
    
    
}

class MeasurementData{
    
    public $id;
    
    public $value;
    
    public $name;
    
    public $description;
    
    public $youtube_link;
    
    public $product_name;
    
    public function __construct($id, $name, $value, $description, $youtube_link, $product_name) {
        
        $this->id = $id;
        
        $this->name = $name;
        
        $this->description  = $description;
        
        $this->value = $value;
        
        $this->youtube_link = str_replace("watch?v=", "embed/", $youtube_link);
        
        $this->product_name = $product_name;
    }
}

class BaseImageData{
    
    public $name;
    
    public $depth;
    
    public $item_id;
    
    public $id;
    
    public $pos_x;
    
    public $pos_y;
    
    public $buttons = array();

    public function __construct($id, $item_id, $name, $depth, $pos_x, $pos_y){
        
        $this->id = $id;
        
        $this->item_id = $item_id;
        
        $this->name = $name;
        
        $this->depth = $depth;
        
        $this->pos_x = $pos_x;
        
        $this->pos_y = $pos_y;
        
    }
}

class UserData{
    
    public $first_name;
    
    public $last_name;
    
    public $address_line_01;
    
    public $address_line_02;
    
    public $postal_code;
    
    public $phone_number;
    
    public $city;
    
    public $country;
    
    public $email;


    public function __construct($first_name, $last_name, $address_line_01, $address_line_02, $postal_code, $phone_number, $email) {
       
        $this->first_name = $first_name;
        
        $this->last_name = $last_name;
        
        $this->address_line_01 = $address_line_01;
        
        $this->address_line_02 = $address_line_02;
        
        $this->postal_code = $postal_code;
        
        $this->phone_number = $phone_number;
        
        $this->email = $email;
        
    }

}

class MenuData{
    
    public $id;
    
    public $name;
        
    public $mixed_fabric_support;
    
    public $inner_contrast_support;

    public $option;
    
    public $option_id;
    
    public $is_back_menu;
            
    function __construct($id, $name, $mixed_fabric_support, $inner_contrast_support, $is_back_menu) {
        
        $this->id = $id;
        
        $this->name = $name;
                
        $this->mixed_fabric_support = $mixed_fabric_support;
        
        $this->inner_contrast_support = $inner_contrast_support;
        
        $this->is_back_menu = $is_back_menu;
    }
    
}
