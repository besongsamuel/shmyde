<?php
class Home extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
        }

        public function view($page = 'home')
        {
        	
            $data['title'] = ucfirst($page); // Capitalize the first letter

            $this->lang->load('shmyde', CURRENT_LANGUAGE);

            $this->load->view('header', $data);

            $this->load->view('index');

            $this->load->view('footer');

        }		
}

