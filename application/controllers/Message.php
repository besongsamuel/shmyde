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
class Message extends CI_Controller 
{
    //put your code here
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * A type of 0 means there were no errors
     * A type of 1 means some errors did occur
     * @param type $type
     */
    public function index()
    {
                
        $this->data['title'] = $this->input->post('page_title');
        
        $this->data['message_title'] = $this->input->post('message_title');
        
        $this->data['message_type'] = $this->input->post('message_type');
        
        $this->data['message'] = $this->input->post('message');
        
        $this->data['user'] = json_encode($this->userObject);
        
        $this->data['cssLinks'] = array('product-checkout');
                
        $this->template->load('shmyde', 'message/message', $this->data);
    }

}
