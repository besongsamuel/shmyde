<?php
include dirname(__DIR__).'\helpers\shmyde_objects.php';
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
    
    public function checkout()
    {
        $data['title'] = "Product Checkout";
        $user_design = null;
        
        $user = new UserObject(null);
  
        if($this->session->userdata('user_id') !== null)
        {
            $user_id = $this->session->userdata('user_id');
            $user_design = json_decode($this->GetTmpUserDesign($user_id), true);  
            
            $user = $this->user_model->get_user_data($user_id); 
        }
        
        $user_object = new UserObject($user);
        $data['user'] = json_encode($user_object);
        
        if(isset($user_design))
        {
            $productManager = new DesignProduct();
            $productManager->LoadProduct(
                    $this->design_model, 
                    $user_design['product_id']);
            $productManager->LoadUserDesign($user_design);
            $data['productManager'] = json_encode($productManager);
        }
        
        $data['cssLinks'] = array('product-checkout');
        
        $this->load->view("pages/header.php",$data);

        $this->load->view('checkout/checkout',$data);

        $this->load->view("pages/footer.php");
               
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
