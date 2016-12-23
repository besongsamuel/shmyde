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
        
        public function BlendImage(){
            
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
?>
