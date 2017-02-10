<?php
defined('BASEPATH') or die('No direct script access allowed');
include dirname(__DIR__).'\helpers\shmyde_objects.php';
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
            
            $data['product'] = json_encode($my_product);
            
            $user = new UserObject(null);
            
            if($this->session->userdata('user_id') !== null)
            {
                $user_id = $this->session->userdata('user_id');
                $user = $this->user_model->get_user_data($user_id);  
            }
            
            $user_object = new UserObject($user);
            $data['user'] = json_encode($user_object);

            $this->load->view("pages/header.php",$data);

            $this->load->view('design/main');

            $this->load->view("pages/footer.php");
	}
        
        
        /**
         * Saves the current front end design to the currently logged user
         * This will be used in the checkout page
         */
        public function SaveTmpUserDesign()
        {            
            $designParameters = $this->input->post('designParameters');
            
            $this->admin_model->SaveTmpUserDesign($this->session->userdata('user_id'), $designParameters);            
        }
        
}
