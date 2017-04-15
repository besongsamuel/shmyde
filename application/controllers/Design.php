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
            
	}
	
	
	public function index()
        {
            
        }
        
        /**
         * This function starts a new order or product design
         */
	public function product()
	{
         
            $user_design = null;
            // User returning after login, go back to design
            if($this->session->userdata('user_id') !== null)
            {
                $user_id = $this->session->userdata('user_id');
                $user_design = json_decode($this->GetTmpUserDesign($user_id), true);
            }
            
            $this->data['order_id'] = json_encode("-1");
            
            $this->data['order_status'] = json_encode("-1");
            
            // User is returning from a previous design
            if($user_design != null)
            {
                $productManager = new DesignProduct();
                $productManager->LoadProduct($this->design_model, $user_design['product_id'], true);
                $productManager->LoadUserDesign($user_design);
                $this->data['product'] = json_encode($productManager);
            }
            else
            {
                // New Design
                $product_id = $this->input->get('product_id');
                $my_product = new DesignProduct();
                $my_product->LoadProduct($this->design_model, $product_id);
                $this->data['product'] = json_encode($my_product);
                
            }
                        
            $this->template->load('shmyde', 'design/main', $this->data);

	}
        
        /**
         * This function edits an order or product design
         * @param type $order_id The id of the order
         * @return type
         */
        public function edit($order_id)
        {
            // User is signed_in
            if($this->session->userdata('user_id') !== null)
            {
                
                $this->data['order_id'] = json_encode($order_id);
                // Get the order object
                $order = $this->GetUserOrder($order_id);
                
                $this->data['order_status'] = json_encode("-1");;
                
                if($order != null)
                {
                    // Get user design from order
                    $user_design = json_decode($order->user_design, true);
                    
                    $this->data['order_status'] = json_encode($order->status);
                    // Create design product
                    $productManager = new DesignProduct();
                    // Load the product recursively
                    $productManager->LoadProduct(
                        $this->design_model, 
                        $user_design['product_id'], true);
                    // Load the user design recursively
                    $productManager->LoadUserDesign($user_design);
                    $this->data['product'] = json_encode($productManager);
                    
                    // Load view
                    $this->template->load('shmyde', 'design/main', $this->data);
                    
                    return;
                }
                
            }
            
            
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
        
        /**
         * Saves the current user design to the database
         */
        public function SaveUserDesign() 
        {
            $designParameters = $this->input->post('designParameters');
            
            // Order ID
            $order_id = $this->input->post('order_id');
            
            $total_price = $this->input->post('price');
            
            $base64designImage = $this->input->post('frontDesignImage');
            $backBase64designImage = $this->input->post('backDesignImage');
            
            $user_id = $this->userObject->id;
            
            // Return the new order ID if it was -1
            echo json_encode($this->design_model->SaveUserDesign($user_id, $order_id, $total_price, $designParameters, $base64designImage, $backBase64designImage));
            
        }
        
        public function GetUserOrder($order_id) 
        {
            return $this->user_model->get_user_order($order_id);
        }
        
        public function DeleteUserDesign() 
        {
            $order_id = $this->input->post('order_id');
            
            $this->design_model->delete_design($order_id);
            
            echo json_encode($this->user_model->get_user_orders($this->userObject->id));
        }
        
}
