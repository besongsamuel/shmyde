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
	
	
	public function index()
        {
            
        }

	public function product()
	{
         
            $this->data['categories'] = $this->admin_model->get_categories();
            
            $ordered_categories = array();
            $invert_category = null;
            
            foreach ($this->data['categories']->result() as $category) 
            {
                if($category->id != 4)
                {
                    $ordered_categories[$category->id] = $category;
                }
                else
                {
                    $invert_category = $category;
                }
                
            }
            // Hack to have the "Invert Category" at the end of the array
            array_push($ordered_categories, $invert_category);
            
            $this->data['ordered_categories'] = json_encode($ordered_categories);
            
            $this->data['title'] = "DESIGN-SHIRT-SHMYDE";

            $this->data['cssLinks'] = array('design');
            
            $user_design = null;
            // User returning after login, go back to design
            if($this->session->userdata('user_id') !== null)
            {
                $user_id = $this->session->userdata('user_id');
                $user_design = json_decode($this->GetTmpUserDesign($user_id), true);
            }
                        
            if($user_design != null)
            {
                $productManager = new DesignProduct();
                $productManager->LoadProduct($this->design_model, $user_design['product_id'], true);
                $productManager->LoadUserDesign($user_design);
                $this->data['product'] = json_encode($productManager);
            }
            else
            {
                $product_id = $this->input->get('product_id');
                $my_product = new DesignProduct();
                $my_product->LoadProduct($this->design_model, $product_id);
                $this->data['product'] = json_encode($my_product);
            }
                        
            $this->template->load('shmyde', 'design/main', $this->data);

	}
        
        
        /**
         * Saves the current front end design to the currently logged user
         * This will be used in the checkout page
         */
        public function SaveTmpUserDesign()
        {            
            $designParameters = $this->input->post('designParameters');
            // Save the design using the current session ID
            
            if (session_id() === "") 
            {
                session_start();
            }
            
            $this->admin_model->SaveTmpUserDesign(session_id(), $designParameters);            
        }
        
        /**
        * Given the user ID, this function grabs the design the user
        * is currently working on if any
        * @param type $user_id
        * @return type
        */
        public function GetTmpUserDesign()
        {   
            // Get the design with the current Session ID
            
            if (session_id() === "") 
            {
                session_start();
            }
            return $this->admin_model->GetTmpUserDesign(session_id());
        }
        
}
