<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Checkout_model class.
 * 
 * @extends CI_Model
 */
class Checkout_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	/**
         * 
         * @param type $user_id
	 * @param type $order_id
         * @param type $quantity
         * @param type $price
         * @param type $type the type of the design
         * @param type $design_data
         * @return boolean
         */
	public function checkout($user_id, $order_id, $quantity, $price, $type, $design_data, $frontBase64Image, $backBase64Image) 
        {
		
	    $data = array(
		    'user_id'           => $user_id,
		    'price'             => $price,
		    'quantity'          => $quantity,
		    'type'              => $type,
		    'status'            => 0,
		    'date_created'      => date('Y-m-j H:i:s'),
		    'date_modified'     => date('Y-m-j H:i:s'),
		    'user_design'       => $design_data,
	    );
            
            
            $insert_id = -1;

            if($order_id == -1)
            { 
                $insert_id = $this->get_next_id(ORDERS_TABLE);
                $data['id'] = $insert_id;
                $result =  $this->db->insert(ORDERS_TABLE, $data);
            }
            else
            {
                $insert_id = $order_id;
                $this->db->where('id', $insert_id);
                $result = $this->db->update(ORDERS_TABLE, $data);
            }
            
            $decoded_frontBase64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $frontBase64Image));
            $decoded_backBase64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $backBase64Image));

            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'_front.png', $decoded_frontBase64Image);
            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'_back.png', $decoded_backBase64Image);

            return $result;
		
	}
        
        
	
	
}
