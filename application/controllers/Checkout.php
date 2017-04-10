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
        $this->data['title'] = "Product Checkout";
        $user_design = null;
          
        if($this->session->userdata('user_id') !== null)
        {
            $user_design = json_decode($this->GetTmpUserDesign(), true);
            
            $productManager = new DesignProduct();
            $productManager->LoadProduct(
                    $this->design_model, 
                    $user_design['product_id'], false);
            $productManager->LoadUserDesign($user_design);
            $this->data['productManager'] = json_encode($productManager);
            
        }

        $this->data['cssLinks'] = array('product-checkout');
                    
        $this->template->load('shmyde', 'checkout/checkout', $this->data);
               
    }
    
    public function checkout()
    {        
        // Grab client data
        $design_data = $this->input->post('design_data');       
        $quantity = $this->input->post('quantity');        
        $price = $this->input->post('price');
        $type = $this->input->post('type');
        $base64designImage = $this->input->post('frontDesignImage');
        $backBase64designImage = $this->input->post('backDesignImage');
        
        // Check that user is still 
        if($this->userObject->id > -1)
        {
            // Perform Checkout
            if($this->checkout_model->checkout($this->userObject->id, $quantity, $price, $type, $design_data, $base64designImage, $backBase64designImage))
            {
                //$this->send_order_confirmation();
                echo json_encode(true);
            }
            else
            {
                echo json_encode(false);
            }
        }
    }
    
    
    private function send_order_confirmation()
    {
        $subject = "Thanks for Placing an order on Shmyde";
        $headers = "From: admin@shmyde.com \r\n";
        $headers .= "Reply-To: no-reply@shmyde.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = '<html><body>';
        $message.='<div style="width:550px; background-color:#CC6600; padding:15px; font-weight:bold;">';
        $message.='Shmyde Order';
        $message.='</div>';
        $message.='
        <div style="font-family: Arial;">
        <p>Hi '.$this->userObject->first_name.', </p>
        <p>This mail is a confirmation that we have recieved your order. </p>
        <br/>
        <p>Your current order is pending payment. Please pay via Mobile Money using the following code for reference.</p>
        <p>Once We recieve payment, your order will be shipped to the tailor. Once your order is complete, we shall contact</p>
        <p>you you diliver the product that the address provided below. </p>
        <br/>
        <p>'.$this->userObject->address_line_1.'</p>
        <p>'.$this->userObject->address_line_2.'</p>
        <p>'.$this->userObject->city.'</p>
        <p>'.$this->userObject->country.'</p>
        <p>'.$this->userObject->phone_number.'</p>
        <br/>
        <p>You can check the status of your order by accessing your account page on Shmyde. </p>
        <br/>
        <br/>
        <p>Best Regards</p>
        <p>Shmyde</p>
        ';
        $message.='</div>';
        $message.='</body></html>';
        mail($this->userObject->email,$subject,$message,$headers);
    }
    
    /**
     * A type of 0 means there were no errors
     * A type of 1 means some errors did occur
     * @param type $type
     */
    public function message($type)
    {
        $this->data['title'] = "Checkout Confirmation";
        
        if($type == 0)
        {
            $this->data['message_title'] = 'Order Confirmation Sent';
            
            $this->data['message'] = array();
            
            array_push($this->data['message'], 'Your order has been submitted');
            array_push($this->data['message'], 'An email has been sent to '.$this->userObject->email.' with instructions');
            array_push($this->data['message'], 'on how to make a payment. ');
            array_push($this->data['message'], 'Your order will be in pending status till after the reciept of payment.');
            array_push($this->data['message'], 'Thank you.');
            array_push($this->data['message'], 'Best Regards ');
            array_push($this->data['message'], 'SHMYDE SARL');
            
            
        }
        else
        {
            $this->data['message_title'] = 'A network error occured';           
            $this->data['message'] = array();
        }
        
        $this->data['cssLinks'] = array('product-checkout');
        $this->data['user'] = json_encode($this->userObject);
        $this->data['message_title'] = json_encode($this->data['message_title']);
        $this->data['message'] = json_encode($this->data['message']);

        $this->template->load('shmyde', 'message/message', $this->data);
    }


    /**
     * Given the user ID, this function grabs the design the user
     * is currently working on if any
     * @param type $user_id
     * @return type
     */
    public function GetTmpUserDesign()
    {   
        
        if(session_id() !== "")
        {
            return $this->admin_model->GetTmpUserDesign(session_id());
        }
        else
        {
            return null;
        }
        
    }
}
