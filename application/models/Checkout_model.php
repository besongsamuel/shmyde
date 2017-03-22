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
         * @param type $quantity
         * @param type $price
         * @param type $design_data
         * @return boolean
         */
	public function checkout($user_id, $quantity, $price, $design_data, $base64Image) 
        {
		
            $data = array(
                    'user_id'       => $user_id,
                    'price'    => $price,
                    'quantity'    => $quantity,
                    'status'      => 0,
                    'date_created'    => date('Y-m-j H:i:s'),
                    'date_modified'    => date('Y-m-j H:i:s'),
                    'user_design'    => $design_data,
            );

            $result =  $this->db->insert('shmyde_orders', $data);
            
            $insert_id = $this->db->insert_id();
            
            $decoded_base64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            
            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'.png', $decoded_base64Image);
            
            return $result;
		
	}
	
	
}
