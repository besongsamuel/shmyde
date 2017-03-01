<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Checkout_model class.
 * 
 * @extends CI_Model
 */
class Home_model extends CI_Model {

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
         * Get's all the products
         */
	public function get_products() 
        {
            $this->db->select('*');
            $this->db->from(PRODUCT_TABLE);
            $products = $this->db->get()->result();
            $this->db->reset_query();

            if(isset($products))
            {
                return $products;
            }

            return null;
            
		
	}
	
	
}
