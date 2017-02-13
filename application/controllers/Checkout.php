<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Checkout
 *
 * @author beson
 */
class Checkout extends CI_Controller 
{
    //put your code here
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data['title'] = "Product Checkout";
        $user_design = null;
          
        if($this->session->userdata('user_id') !== null)
        {
            $user_id = $this->session->userdata('user_id');
            $user_design = json_decode($this->GetTmpUserDesign($user_id), true);
            
            $productManager = new DesignProduct();
            $productManager->LoadProduct(
                    $this->design_model, 
                    $user_design['product_id']);
            $productManager->LoadUserDesign($user_design);
            $data['productManager'] = json_encode($productManager);
        }

        $data['cssLinks'] = array('product-checkout');
        
        $data['user'] = json_encode($this->userObject);
            
        $this->template->load('shmyde', 'checkout/checkout', $data);
               
    }
    
    public function checkout()
    {        
        // Grab client data
        $design_data = $this->input->post('design_data');       
        $quantity = $this->input->post('quantity');        
        $price = $this->input->post('price');
        
        // Check that user is still 
        if($this->userObject->id > -1)
        {
            // Perform Checkout
            if($this->checkout_model->checkout($this->userObject->id, $quantity, $price, $design_data))
            {
                echo json_encode(true);
            }
            else
            {
                echo json_encode(false);
            }
        }
    }


    /**
     * Given the user ID, this function grabs the design the user
     * is currently working on if any
     * @param type $user_id
     * @return type
     */
    public function GetTmpUserDesign($user_id)
    {          
        return $this->admin_model->GetTmpUserDesign($user_id);
    }
}
