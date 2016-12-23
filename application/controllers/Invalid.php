<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invalid extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    
        function __construct()
        {
            parent::__construct(); 
            
        }
	public function index()
	{
            $this->load->view('welcome_message');
	}
        
        public function invalid_login_user()
        {
            $data = array();
            $data['cssLinks'] = array('invalid');
            $data['title'] = 'Invalid';
            $this->load->view('pages/header', $data);
            $this->load->view('invalid/invalid_login_user');
        }
}
