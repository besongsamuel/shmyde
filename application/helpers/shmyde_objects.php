<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class DesignProduct
{
    public $id;
    
    public $name;

    public $url;
    
    public $product_target;
    
    public $price;
    
    public $buttons = array();
    
    public $fabrics = array();
    
    public $threads = array();
    
    public $measurements = array();

    public $default_button;
    
    public $default_fabric;
    
    public $mix_fabric;
    
    public $default_thread;
        
    public $product_menus = array();
    
    public function LoadUserDesign($user_design)
    {
        
        if((int)$user_design['fabric_id'] > -1)
        {
            $this->default_fabric = $this->fabrics[(int)$user_design['fabric_id']];
        }
        
        if((int)$user_design['mix_fabric_id'] > -1)
        {
            $this->mix_fabric = $this->fabrics[(int)$user_design['mix_fabric_id']];
        }

        if((int)$user_design['button_id'] > -1)
        {
            $this->default_button = $this->buttons[(int)$user_design['button_id']];
        }
        
        if((int)$user_design['thread_id'] > -1)
        {
            $this->default_thread = $this->threads[(int)$user_design['thread_id']];
        }
        
        foreach ($this->measurements as $key => $value) 
        {
            $this->measurements[$key]->default_value = (int)$user_design['measurements'][$key]['default_value'];
        }
                
        foreach ($this->product_menus as $key => $value) 
        {
            $this->product_menus[$key]->inner_mix_selected = (int)$user_design['mix_menus'][$key]['inner_mix_selected'];
            $this->product_menus[$key]->outer_mix_selected = (int)$user_design['mix_menus'][$key]['outer_mix_selected'];

            if($this->product_menus[$key]->category == 2)
            {
                foreach ($this->product_menus[$key]->design_options as $option_key => $value) 
                {
                    if($user_design['options'][$option_key] != null)
                    {
                        $this->product_menus[$key]->design_options[$option_key]->selected = (int)$user_design['options'][$option_key]['selected'];
                    }

                }
            }
            
            
        }        
    }

    public function LoadProduct($model, $product_id, $load_images = true) 
    {
        $product_object = $model->get_product($product_id);
        
        if($product_object != null)
        {
            $this->id = $product_object->id;
            $this->name = $product_object->name;
            $this->product_target = $product_object->target;
            $this->price = $product_object->base_price;
            
            $this->LoadMeasurements($model);
            $this->LoadFabrics($model, $load_images);
            $this->LoadButtons($model, $load_images);
            $this->LoadThreads($model, $load_images);
            $this->LoadProductMenus($model, $load_images);

            if(array_key_exists($product_object->default_button_id, $this->buttons))
            {
                 $this->default_button = $this->buttons[$product_object->default_button_id]; 
            }
            if(array_key_exists($product_object->default_thread_id, $this->threads))
            {
                 $this->default_thread = $this->threads[$product_object->default_thread_id]; 
            }
            if(array_key_exists($product_object->default_fabric_id, $this->fabrics))
            {
                 $this->default_fabric = $this->fabrics[$product_object->default_fabric_id]; 
            }
        }
    }
    
    private function LoadProductMenus($model, $load_images = true) 
    {
        $product_menus = $model->get_product_menus($this->id);
            
        if($product_menus != null)
        {
            foreach ($product_menus->result() as $product_menu) 
            {
                $design_menu = new DesignMenu();
                $design_menu->LoadMenu($model, $product_menu->id, $load_images);
                $this->product_menus[$product_menu->id] = $design_menu;
            }
        }
    }
    
    private function LoadFabrics($model, $load_images = true)
    {
        $fabrics = $model->get_fabrics();
        
        if($fabrics != null)
        {
            foreach ($fabrics->result() as $fabric) 
            {
                $this->fabrics[$fabric->fabric_id] = new DesignFabric($model, $fabric, $load_images);
            }
        }      
    }
    
    private function LoadButtons($model, $load_images = true)
    {
        $buttons = $model->get_buttons();
        
        if($buttons != null)
        {
            foreach ($buttons->result() as $button) 
            {
                $this->buttons[$button->id] = new DesignButtonImage($button, $load_images);
            }
        }      
    }
    
    private function LoadThreads($model, $load_images = true)
    {
        $threads = $model->get_threads();
        
        if($threads != null)
        {
            foreach ($threads->result() as $thread) 
            {
                $this->threads[$thread->id] = new DesignButtonThread($thread, $load_images);
            }
        }      
    }
    
    private function LoadMeasurements($model)
    {
        $measurements = $model->get_product_measurements($this->id);
        
        if($measurements != null)
        {
            foreach ($measurements->result() as $measurement) 
            {
                $this->measurements[$measurement->id] = new DesignMeasurement($measurement);
            }
        }
    }
      
}

/**
 * This class represents a design menu
 * A design menu contains all its different
 * design options
 */
class DesignMenu
{
    /*
     * The different design options of the menu
     */
    public $design_options = array();
    
    /*
     * The id of the menu
     */
    public $id;
    
    /*
     * The name of the menu
     */
    public $name;
    
    /**
     * When this is true, this menu shows up in the list
     * of mixed fabric menus as well
     * @var type 
     */
    public $is_mixed_fabric_menu;
    
    /**
     * If any of the option images has an inner image, 
     * then this value is set to true
     * @var type 
     */
    public $inner_contrast_support;
    
        /**
     * If this value is 0, then the mix fabric is applied to the 
     * inner images of this object
     * @var type 
     */
    public $inner_mix_selected = 0;
    
    /**
     * If this value is 1, then the mix fabric is applied to the 
     * outer images of this object
     * @var type 
     */
    public $outer_mix_selected = 0;
    
    /**
     * When this is true, this menu is only visible when 
     * the back view is selected
     * @var type 
     */
    public $is_back_option_menu;

    /**
     * When this is true, this menu is always visible
     * When this is false, this menu is only visible when a certain 
     * option is selected
     * @var type 
     */
    public $is_independent;
    
    /**
     * The category this menu belongs to 
     * 1 : Fabric
     * 2 : Design
     * @var type 
     */
    public $category;
    
    /**
     * The product the menu belongs to
     * @var type 
     */
    public $product_id;

    /**
     * This is the fabric used for the inner images
     * of this object
     * @var type 
     */
    public $inner_fabric;
    
    /**
     * This is the fabric used for the outer images
     * of this object
     * @var type 
     */
    public $outer_fabric;
    
    /**
     * This represents the menu option selected
     * @var type 
     */
    public $option_selected = -1;


    public function LoadMenu($model, $menu_id, $load_images = true) 
    {
        $menu_object = $model->get_menu($menu_id);
        
        if($menu_object != null)
        {
            $this->id = $menu_object->id;
            $this->name = $menu_object->name;
            $this->product_id = $menu_object->shmyde_product_id;
            $this->is_mixed_fabric_menu = $menu_object->mixed_fabric_support;
            $this->inner_contrast_support = $menu_object->inner_contrast_support;
            $this->is_back_option_menu = $menu_object->is_back_menu;
            $this->is_independent = $menu_object->is_independent; 
            $this->category = $menu_object->shmyde_design_category_id; 
            
            $this->LoadMenuOptions($model, $load_images);
        }
    }
    
    private function LoadMenuOptions($model, $load_images = true)
    {
        // Is a fabric Menu
        if($this->category == 1)
        {
            $menu_options = $model->get_menu_fabrics($this->id, $this->product_id);
            
            if($menu_options != null)
            {
                foreach ($menu_options->result() as $menu_option) 
                {
                    $design_option = new DesignFabric($model, $menu_option, $load_images);
                    $this->design_options[$menu_option->id] = $design_option;
                }
            }
        }
                
        // Is a design Menu
        if($this->category == 2)
        {
            $menu_options = $model->get_menu_options($this->id);
        
            if($menu_options != null)
            {
                foreach ($menu_options->result() as $menu_option) 
                {
                    $design_option = new DesignObject();
                    $design_option->outer_fabric = $this->outer_fabric;
                    $design_option->inner_fabric = $this->inner_fabric; 
                    $design_option->LoadObject($model, $menu_option->id, $load_images);

                    $this->design_options[$menu_option->id] = $design_option;
                }
            }
        }
        
    }
}

/**
 * A design object in SHMYDE context is any 
 * object that represents a part of a product
 */
class DesignObject
{
    /**
     * The ID of this object 
     * @var type 
     */
    public $id;
    
    /**
     * The name of this object
     * @var type 
     */
    public $name;
    
    /**
     * A short description of the object
     */
    public $description;
    
    /**
     * The cost associated with adding this object in a 
     * product design
     * @var type 
     */
    public $price;
    
    /**
     * This is true if the option is selected
     * @var type 
     */
    public $selected;
    
    /**
     * This is the fabric used for the inner images
     * of this object
     * @var type 
     */
    public $inner_fabric;
    
    /**
     * This is the fabric used for the outer images
     * of this object
     * @var type 
     */
    public $outer_fabric;
    
    /**
     * The Images for this object
     * @var type 
     */
    public $images = array();
    
    /**
     * A list of id's of menus that depend on this being selected
     * @var type 
     */
    public $dependent_menus = array();
    
    public $base_64_thumbnail;


    /**
     * Loads the objects parameters
     * @param type $model The Design Model
     * @param type $object_id The id of the object
     */
    public function LoadObject($model, $object_id, $load_images = true) 
    {
        $shmyde_object = $model->get_object($object_id);
  
        if($shmyde_object != null)
        {
            $this->id = $shmyde_object->id;
            $this->name = $shmyde_object->name;
            $this->price = $shmyde_object->price;     
            $this->description = $shmyde_object->description;
            $this->selected = $shmyde_object->is_default;

            $this->loadObjectImages($model, $load_images);           
            $this->LoadDependentMenuIDs($model);   
            $this->loadObjectThumbnail($model, $load_images);
                
        }
    }
    
    private function loadObjectImages($model, $load_images = true)
    {
        
        $object_images = $model->get_object_images($this->id);
        
        if($object_images != null)
        {
            foreach ($object_images->result() as $object_image)
            {
                $design_object_image = new DesignImage($model, $object_image, $load_images);
                $design_object_image->outer_fabric = $this->outer_fabric;
                $design_object_image->inner_fabric = $this->inner_fabric;   
                
                array_push($this->images, $design_object_image);
            }
        }
    }
    
    private function loadObjectThumbnail($model, $load_images = true) 
    {
        $object_thumbnail = $model->get_object_thumbnail($this->id);
        
        if($object_thumbnail != null)
        {
            if($load_images)
            {
                $path = ASSETS_DIR_PATH.'images/design/thumbnail/'.$object_thumbnail->name;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $this->base_64_thumbnail = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            
        }
    }
    
    private function LoadDependentMenuIDs($model)
    {
        $option_dependent_menu_ids = $model->get_option_dependent_menu_ids($this->id);
        
        if($option_dependent_menu_ids != null)
        {
            foreach ($option_dependent_menu_ids->result() as $dependent_option_menu)
            {                
                array_push($this->dependent_menus, $dependent_option_menu->shmyde_design_main_menu_id);
            }
        }
    }
    
}

/**
 * Design Object Images
 */
class DesignImage
{

    public $id;
    
    public $name;
    
    public $object_id;
    
    public $zindex;
    
    public $x_pos;
    
    public $y_pos;
    
    public $is_inner;
    
    public $is_back;
    
    protected $dir_prefix = "images/design/";

    public $server_path;
    
    public $client_path;
    
    public $base_64_image;
    
    public $original_base_64_image;


    /**
     * This is the fabric used for the inner images
     * of this object
     * @var type 
     */
    public $inner_fabric;
    
    /**
     * This is the fabric used for the outer images
     * of this object
     * @var type 
     */
    public $outer_fabric;
    
    public $buttons = array();

    public function __construct($model, $image_object, $load_images = true)
    {
        $this->id = $image_object->id;
        $this->name = $image_object->name;
        $this->object_id = $image_object->item_id;
        $this->zindex = $image_object->depth;
        $this->x_pos = $image_object->pos_x;
        $this->y_pos = $image_object->pos_y;
        $this->is_inner = $image_object->is_inner;
        $this->is_back = $image_object->is_back_image;
        $this->server_path = ASSETS_DIR_PATH.$this->dir_prefix.$this->name;
        $this->client_path = ASSETS_PATH.$this->dir_prefix.$this->name;
        
        if($load_images)
        {
            $type = pathinfo($this->server_path, PATHINFO_EXTENSION);
            $data = file_get_contents($this->server_path);
            $this->original_base_64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        
        
        $this->LoadImageButtons($model);
    }
    
    private function LoadImageButtons($model) 
    {
        $image_buttons = $model->get_image_buttons($this->id);
               
        if($image_buttons != null)
        {
            foreach ($image_buttons->result() as $image_button)
            {
                array_push($this->buttons, new DesignButton($image_button));
            }
        }
    }
}

class DesignButton
{
    
    public $id;
    
    public $type;
    
    public $x_pos;
    
    public $y_pos;
    
    public $zindex = 100;

    public function __construct($button_object)
    {
        $this->id = $button_object->id;
        $this->x_pos = $button_object->pos_x;
        $this->y_pos = $button_object->pos_y;
        $this->type = $button_object->button_type;       
    }
}

class DesignButtonImage
{
    public $id;
    
    public $name;

    public $server_path;
    
    public $client_path;
    
    public $base_64_image;
    
    public $original_base_64_image;
    
    public $dir_prefix = "images/buttons/";

    public function __construct($button_image_object, $load_images = true)
    {
        $this->id = $button_image_object->id;
        $this->name = $button_image_object->name;
        
        $this->server_path = ASSETS_DIR_PATH.$this->dir_prefix.$button_image_object->design_image_name;
        $this->client_path = ASSETS_PATH.$this->dir_prefix.$button_image_object->design_image_name;
        
        if($load_images)
        {
            $type = pathinfo($this->server_path, PATHINFO_EXTENSION);
            $data = file_get_contents($this->server_path);
            $this->base_64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $this->original_base_64_image = $this->base_64_image;
        }
        
              
    }
}

class DesignButtonThread
{
    public $id;
    
    public $name;
    
    public $server_path;
    
    public $client_path;
    
    public $base_64_image;
    
    public $original_base_64_image;
    
    public $color;
    
    public function __construct($thread_object, $load_images = true)
    {
        $this->id = $thread_object->id;
        $this->name = $thread_object->image_name;
        $this->color =  $thread_object->color;
        
        $this->server_path = ASSETS_DIR_PATH."images/threads/".$this->name;
        $this->client_path = ASSETS_PATH."images/threads/".$this->name;
        
        if($load_images)
        {
            $type = pathinfo($this->server_path, PATHINFO_EXTENSION);
            $data = file_get_contents($this->server_path);
            $this->original_base_64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }
}

class DesignFabric extends DesignImage
{
    public $fabric_id;
    
    public function __construct($model, $fabric_object, $load_images = true)
    {
        $this->fabric_id = $fabric_object->fabric_id;
        $this->dir_prefix = "images/product/fabric/";            
        parent::__construct($model, $fabric_object, $load_images);
        
    }
}

class DesignMeasurement
{
    public $id;
    
    public $product_id;
    
    public $name;
    
    public $default_value;
    
    public $description;
    
    public $youtube_link;

    public function __construct($measurement_object)
    {
        $this->id = $measurement_object->id;
        $this->name = $measurement_object->name;
        $this->default_value = $measurement_object->default_value;
        $this->description = $measurement_object->description;
        $this->youtube_link = $measurement_object->youtube_video_link;
    }
}

class UserObject
{
    public $id;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $address_line_1;
    public $address_line_2;
    public $country;
    public $city;
    public $postcode;
    public $email;
    public $avatar;
    public $dob;
    public $gender;


    public function __construct($user_object)
    {        
        $this->id = $user_object != null ? $user_object->id : -1;
        $this->email = $user_object != null ? $user_object->email : "";
        $this->first_name = $user_object != null ? $user_object->first_name : "";
        $this->last_name = $user_object != null ? $user_object->last_name : "";
        $this->phone_number = $user_object != null ? $user_object->phone_number : "";
        $this->address_line_1 = $user_object != null ? $user_object->address_line_1 : "";
        $this->address_line_2 = $user_object != null ? $user_object->address_line_2 : "";
        $this->country = $user_object != null ? $user_object->country : "";
        $this->city = $user_object != null ? $user_object->city : "";
        $this->postcode = $user_object != null ? $user_object->postcode : "";
        $this->dob = $user_object != null ? $user_object->dob : "1999-1-1";
        $this->gender = $user_object != null ? $user_object->gender : "none";
        $this->avatar = $user_object != null ? $user_object->avatar : "default.png";
    }

}
