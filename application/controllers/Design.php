<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
* Design
* Loads appropriate design pages.
* @author Besong Moses Besong <mosbesong@gmail.com>
*/
class Design extends CI_Controller
{
	
	public function __construct()
	{
            parent::__construct();

            $this->load->helper('url');
            
            $this->load->helper('DesignData');

            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            switch ($lang){
                    case "fr":
                    $current_language = "english";
                    break;
                    case "en":
                    $current_language = "english";
                    break;
            default:
                    $current_language = "english";
                    break;
            }

            $this->lang->load('shmyde', $current_language);

	}
	
	
	public function index(){
            
        }

	public function product($target, $product)
	{
            
            $data['categories'] = $this->admin_model->get_categories();
            
            $data['title'] = "DESIGN-SHIRT-SHMYDE";

            $data['cssLinks'] = array('design-shirt');
            
            $product_id = $this->admin_model->get_product_id($target, $product);
            
            $my_product = new DesignProduct();
            $my_product->LoadProduct($this->design_model, $product_id);
            $data['my_product'] = json_encode($my_product);
            
            $design_data = new DesignData();
            
            $design_data->LoadParameters($product_id);
            
            $default_product_fabric = $this->admin_model->get_product_default_fabric($product_id);
                                                                 
            $design_data->defaultFabric = $default_product_fabric;
            
            $design_data->mixFabric = array();
            
            $data['product_id'] = $product_id;
            
            $data['Buttons'] = $this->GetButtons();
            
            $data['Threads'] = $this->GetThreads();
            
            $design_data->currentButton = $this->GetProductDefaultButton($product_id);
            
            $design_data->currentThread = $this->GetProductDefaultThread($product_id);
            
            if($this->session->userdata('user_id') !== null)
            {                
                $design_data->LoadUserData($this->user_model->get_user_data($this->session->userdata('user_id')));                
            }
            
            $data['design_data'] = json_encode($design_data);
            
            if($this->session->userdata('user_id') !== null)
            {                
                $design_data->LoadUserData($this->user_model->get_user_data($this->session->userdata('user_id')));
                
                $storedUserDesign = $this->GetUserDesign($this->session->userdata('user_id'));
                // Override default design data with last stored user design
                if($storedUserDesign != null)
                {
                    $data['design_data'] = $this->GetUserDesign($this->session->userdata('user_id'));                    
                }
            }
            
            // Override design data with session data
            if($this->session->userdata('userDesign') !== null)
            {
                $data['design_data'] = $this->session->userdata('userDesign');  
                unset($_SESSION['userDesign']);
            }
      
            $this->load->view("pages/header.php",$data);

            $this->load->view('design/main');

            $this->load->view("pages/footer.php");
	}
        
        public function BlendImage() 
        {
            $this->load->helper('SimpleImage');
            
            $image_objects = json_decode($this->input->post('image_data'), true);
            
            foreach ($image_objects as $key => $image_data) 
            {
                $image = new abeautifulsite\SimpleImage($image_data["image_data"]["image_src"]);
            
                if($image_data["image_data"]["fabric_src"] == null)
                {
                    continue;
                }
                    
                $new_image = $image->imagealphamask($image_data["image_data"]["fabric_src"]);
                        
                $new_image->brightness(0);
                
                $image_objects[$key]["image_data"]['blend_image'] = $new_image->output_base64('png');
            }
                       
            echo json_encode($image_objects);
        } 


        public function BlendImage2()
        {
            
            $this->load->helper('SimpleImage');
                                    
            $image_url = $this->input->post('image_url');
            
            $blend_url = $this->input->post('blend_url');
            
            
            $Image = new abeautifulsite\SimpleImage($image_url);
            
            $Image = $Image->imagealphamask($blend_url);
                        
            $Image->brightness(0);
            
            $result = array();
        
            $result['id'] = $this->input->post('id');
            
            $result['element_id'] = $this->input->post('element_id');
            
            $result['image_id'] = $this->input->post('image_id');
            
            $result['image'] = $Image->output_base64('png');
            
            $json_result = json_encode($result);
            
            echo $json_result;
            
        }
        
        public function BlendButtonThread(){
            
            $this->load->helper('SimpleImage');
                                    
            $currentButton = json_decode($this->input->post('currentButton'));
                        
            $threadColor = $this->input->post('threadColor');
                                    
            $ButtonImage = new abeautifulsite\SimpleImage(ASSETS_DIR_PATH.'images/buttons/'.$currentButton->image_name);
            
            $ButtonDesignImage = new abeautifulsite\SimpleImage(ASSETS_DIR_PATH.'images/buttons/'.$currentButton->design_image_name);
            
            $ButtonImage = $ButtonImage->apply_color($threadColor);
            
            $ButtonDesignImage = $ButtonDesignImage->apply_color($threadColor);
                        
            $ButtonImage->brightness(0);
            
            $ButtonDesignImage->brightness(0);
                    
            $buttonData = new stdClass();
            
            $buttonData->image_name = $ButtonImage->output_base64('png');
            
            $buttonData->design_image_name = $ButtonDesignImage->output_base64('png');
                                    
            echo json_encode($buttonData);
            
        }
            
        public function BlendImages(){
            
            $this->load->helper('SimpleImage');

            $images = json_decode($this->input->post('images'));
            
            $output = Array();
            
            foreach ($images as $image) {
                
                   
                $image_url = $image->image_url;
                
                if(isset($image->blend_url)){
                    $blend_url = $image->blend_url;
                }
                
                $Image = new abeautifulsite\SimpleImage($image_url);
                
                if(isset($blend_url)){
                    
                    $Image = $Image->imagealphamask($blend_url);
                }
                                
                $image_obj = array(
                    "id" => $image->id,
                    "image_id" => $image->image_id,
                    "depth" => $image->depth,
                    "image" => $Image->output_base64('png'),
                    "item_id" => $image->image_id == -1 ? $image->button_item_id : -1,
                    "pos_x" => $image->pos_x,
                    "pos_y" => $image->pos_y,
                );
                
                array_push($output, $image_obj);
                                
                
            }
            
            usort($output, array("Design", "cmp"));
            
            echo json_encode($output);
        }
        
        /**
         * Saves the current front end design to the currently logged user
         */
        public function SaveUserDesign()
        {
            
            $userDesign = $this->input->post('userDesign');
            
            $this->admin_model->SaveUserDesign($this->session->userdata('user_id'), $userDesign);
            
            echo json_encode("User Design Saved");
        }
        
        public function SessionStoreUserDesign(){
            

            $userDesign = $this->input->post('userDesign');
            
            $currentUrl = $this->input->post('currentURL');
            
            $this->session->set_userdata('last_page', $currentUrl);
            
            $this->session->set_userdata('userDesign', $userDesign);
            
        }


        public function GetUserDesign($user_id){
            
            return $this->admin_model->GetUserDesign($user_id);
        }


        function cmp($a, $b)
        {
            if ($a["depth"] == $b["depth"]) {
                return 0;
            }
            return ($a["depth"] < $b["depth"]) ? -1 : 1;
        }

        public function getColorImage()
	{
            $color = $_POST['color'];

            $this->load->helper('SimpleImage');

            $finalImage = new abeautifulsite\SimpleImage();

            $finalImage->create(100, 100, $color);

            echo $finalImage->output_base64('png');
		
	}
        
        /**
         * Gets all the threads currently in the system
         */
        private function GetThreads(){
            
            return json_encode($this->admin_model->get_threads());
        }
        
        /**
         * Gets all buttons ccurrently in the system
         */
        private function GetButtons(){
            
            return json_encode($this->admin_model->get_buttons());
        }
        
        /**
         * Gets the default button associated with the product
         * @param type $productID
         */
        private function GetProductDefaultButton($productID){
            
            return $this->admin_model->GetProductDefaultButton($productID);
        }
        
        /**
         * Gets the default thread associated with the product
         * @param type $productID
         */
        private function GetProductDefaultThread($productID){
            
            return $this->admin_model->GetProductDefaultThread($productID);
        }
        
        
        public function get_fabric($fabric_id){
              
            $result = json_encode($this->admin_model->get_product_fabric($fabric_id));

            if(isset($result)){

                echo $result;
            }
        }
        
        public function get_option($id){
			
            $result = $this->admin_model->get_json_option($id);

            if(isset($result)){

                echo $result;
            }
        }
        
        public function get_product_design_options($menu_id, $category_id, $product_id){

            $result = '';

            if($category_id == 1)
            {
                $result = json_encode($this->admin_model->get_product_submenu_fabric_images($product_id, $menu_id));
            }
            else
                {
                $result = $this->admin_model->get_json_menu_options($menu_id);
            }

            if(isset($result)){

                echo $result;
            }
        }

        public function get_all_product_fabrics($product_id){
        
            $result = json_encode($this->admin_model->get_all_design_product_fabrics($product_id));

            if(isset($result)){

                echo $result;
            }
        }
     
        public function get_product_design_menus(){

            $product_id = $this->input->post('product_id');

            $selected_option_list = json_decode($this->input->post('selected_option_list'));

            $menus = Array();

            $result = $this->admin_model->get_product_design_menus($product_id, $selected_option_list);

            if(isset($result)){

                foreach($result as $row){

                    $menus[$row->id]['name'] = $row->name;
                    $menus[$row->id]['id'] = $row->id;
                    $menus[$row->id]['mixed_fabric_support'] = $row->mixed_fabric_support;
                    $menus[$row->id]['inner_contrast_support'] = $row->inner_contrast_support;

                }

                echo json_encode($menus);
            }
            else{

                echo json_encode('');
            }
        }
        
        public function get_buttons()
        {

            echo json_encode($this->admin_model->get_buttons());
        }
        
        public function get_product_menus($product_id, $category_id){

            $menus = Array();

            $result = $this->admin_model->get_product_category_menus($product_id, $category_id);

            if(isset($result)){

                foreach($result->result() as $row){

                    $menus[$row->id]['name'] = $row->name;
                    $menus[$row->id]['id'] = $row->id;
                    $menus[$row->id]['mixed_fabric_support'] = $row->mixed_fabric_support;
                    $menus[$row->id]['inner_contrast_support'] = $row->inner_contrast_support;

                }

                echo json_encode($menus);
            }
            else{

                echo json_encode('');
            }

        }
}

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

    public $default_button;
    
    public $default_fabric;
    
    public $mix_fabric;
    
    public $default_thread;
        
    public $product_menus = array();

    public function LoadProduct($model, $product_id) 
    {
        $product_object = $model->get_product($product_id);
        
        $this->LoadFabrics($model);
        $this->LoadButtons($model);
        $this->LoadThreads($model);
        
        if($product_object != null)
        {
            $this->id = $product_object->id;
            $this->name = $product_object->name;
            $this->product_target = $product_object->target;
            $this->price = $product_object->base_price;

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
           
            $this->LoadProductMenus($model);
        }
    }
    
    private function LoadProductMenus($model) 
    {
        $product_menus = $model->get_product_menus($this->id);
            
        if($product_menus != null)
        {
            foreach ($product_menus->result() as $product_menu) 
            {
                $design_menu = new DesignMenu();
                $design_menu->LoadMenu($model, $product_menu->id);
                $this->product_menus[$product_menu->id] = $design_menu;
            }
        }
    }
    
    private function LoadFabrics($model)
    {
        $fabrics = $model->get_fabrics();
        
        if($fabrics != null)
        {
            foreach ($fabrics->result() as $fabric) 
            {
                $this->fabrics[$fabric->fabric_id] = new DesignFabric($model, $fabric);
            }
        }      
    }
    
    private function LoadButtons($model)
    {
        $buttons = $model->get_buttons();
        
        if($buttons != null)
        {
            foreach ($buttons->result() as $button) 
            {
                $this->buttons[$button->id] = new DesignButtonImage($button);
            }
        }      
    }
    
    private function LoadThreads($model)
    {
        $threads = $model->get_threads();
        
        if($threads != null)
        {
            foreach ($threads->result() as $thread) 
            {
                $this->threads[$thread->id] = new DesignButtonThread($thread);
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
    
    public function LoadMenu($model, $menu_id) 
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
            
            $this->LoadMenuOptions($model);
        }
    }
    
    private function LoadMenuOptions($model)
    {
        // Is a fabric Menu
        if($this->category == 1)
        {
            $menu_options = $model->get_menu_fabrics($this->id, $this->product_id);
            
            if($menu_options != null)
            {
                foreach ($menu_options->result() as $menu_option) 
                {
                    $design_option = new DesignFabric($model, $menu_option);
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
                    $design_option->LoadObject($model, $menu_option->id);

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
    public function LoadObject($model, $object_id) 
    {
        $shmyde_object = $model->get_object($object_id);
  
        if($shmyde_object != null)
        {
            $this->id = $shmyde_object->id;
            $this->name = $shmyde_object->name;
            $this->price = $shmyde_object->price;     
            $this->description = $shmyde_object->description;
            $this->selected = $shmyde_object->is_default;

            $this->loadObjectImages($model);           
            $this->LoadDependentMenuIDs($model);   
            $this->loadObjectThumbnail($model);
                
        }
    }
    
    private function loadObjectImages($model)
    {
        
        $object_images = $model->get_object_images($this->id);
        
        if($object_images != null)
        {
            foreach ($object_images->result() as $object_image)
            {
                $design_object_image = new DesignImage($model, $object_image);
                $design_object_image->outer_fabric = $this->outer_fabric;
                $design_object_image->inner_fabric = $this->inner_fabric;   
                
                array_push($this->images, $design_object_image);
            }
        }
    }
    
    private function loadObjectThumbnail($model) 
    {
        $object_thumbnail = $model->get_object_thumbnail($this->id);
        
        if($object_thumbnail != null)
        {
            $path = ASSETS_DIR_PATH.'images/design/thumbnail/'.$object_thumbnail->name;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $this->base_64_thumbnail = 'data:image/' . $type . ';base64,' . base64_encode($data);
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

    public function __construct($model, $image_object)
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
        
        $type = pathinfo($this->server_path, PATHINFO_EXTENSION);
        $data = file_get_contents($this->server_path);
        $this->base_64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        
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
    
    public $dir_prefix = "images/buttons/";


    public function __construct($button_image_object)
    {
        $this->id = $button_image_object->id;
        $this->name = $button_image_object->name;
        
        $this->server_path = ASSETS_DIR_PATH.$this->dir_prefix.$button_image_object->design_image_name;
        $this->client_path = ASSETS_PATH.$this->dir_prefix.$button_image_object->design_image_name;
        
        $type = pathinfo($this->server_path, PATHINFO_EXTENSION);
        $data = file_get_contents($this->server_path);
        $this->base_64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
              
    }
}

class DesignButtonThread
{
    public $id;
    
    public $name;
    
    public $server_path;
    
    public $client_path;
    
    public $base_64_image;
    
    public $color;
    
    public function __construct($thread_object)
    {
        $this->id = $thread_object->id;
        $this->name = $thread_object->image_name;
        $this->color =  $thread_object->color;
        
        $this->server_path = ASSETS_DIR_PATH."images/threads/".$this->name;
        $this->client_path = ASSETS_PATH."images/threads/".$this->name;
        
        $type = pathinfo($this->server_path, PATHINFO_EXTENSION);
        $data = file_get_contents($this->server_path);
        $this->base_64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}

class DesignFabric extends DesignImage
{
    public $fabric_id;
    
    public function __construct($model, $fabric_object)
    {
        $this->fabric_id = $fabric_object->fabric_id;
        $this->dir_prefix = "images/product/fabric/";            
        parent::__construct($model, $fabric_object);
        
    }
}



