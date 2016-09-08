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
            
            $data['design_data'] = json_encode($design_data);

            $default_product_fabric = $this->admin_model->get_product_default_fabric($product_id);
                                                                 
            $data['default_fabric'] = json_encode($default_product_fabric);
            
            $data['mix_fabric'] = json_encode(array());
            
            $data['product_id'] = $product_id;
            
            $data['Buttons'] = $this->GetButtons();
            
            $data['Threads'] = $this->GetThreads();
            
            $data['currentButton'] = $this->GetProductDefaultButton($product_id);
            
            $data['currentThread'] = $this->GetProductDefaultThread($product_id);
            
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
            
            return json_encode($this->admin_model->GetProductDefaultButton($productID));
        }
        
        /**
         * Gets the default thread associated with the product
         * @param type $productID
         */
        private function GetProductDefaultThread($productID){
            
            return json_encode($this->admin_model->GetProductDefaultThread($productID));
        }
        
        
}
?>
