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
	}
	
	
	public function index(){
            
        }

	public function product()
	{
         
            
            $this->data['categories'] = $this->admin_model->get_categories();
            
            $this->data['title'] = "DESIGN-SHIRT-SHMYDE";

            $this->data['cssLinks'] = array('design');
            
            $product_id = $this->input->post('product_id');
            
            $my_product = new DesignProduct();
            
            $my_product->LoadProduct($this->design_model, $product_id);
            
            $this->data['product'] = json_encode($my_product);
                        
            $this->template->load('shmyde', 'design/main', $this->data);

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
