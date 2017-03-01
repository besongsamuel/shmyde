<?php
class Home extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('home_model');
        }
        
        public function index()
        {
            $this->data['title'] = 'Home';
            $this->data['cssLinks'] = array('home');
            /**
             * Get all Shmyde products, organised by male, female or unisex 
             * designs. 
             */
            $this->data['products'] = json_encode($this->home_model->get_products());
                        
            $this->template->load('shmyde', 'home/index', $this->data);
        }	
}

