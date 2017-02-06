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
    
    public function checkout()
    {
        $data['title'] = "Product Checkout";

        $data['cssLinks'] = array('product-checkout');
        
        $this->load->view("pages/header.php",$data);

        $this->load->view('checkout/checkout');

        $this->load->view("pages/footer.php");
               
    }
}
