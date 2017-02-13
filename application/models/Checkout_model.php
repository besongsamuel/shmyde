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
	public function checkout($user_id, $quantity, $price, $design_data) 
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

            return $this->db->insert('shmyde_orders', $data);
		
	}
	
	
}
