<?php
class Pages extends CI_Controller {



		public function __construct()
		{
			parent::__construct();

			
        	$this->load->helper('url');
        	
        	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
					switch ($lang){
						case "fr":
						$current_language = "french";
						break;
						case "en":
						$current_language = "english";
						break;
					default:
						$current_language = "english";
						break;
					}

        	$this->lang->load('shmyde', $current_language);

		}

        public function view($page = 'home')
		{
        	if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
        	{
                // Whoops, we don't have a page for that!
                show_404();
        	}
        	
        	$data['title'] = ucfirst($page); // Capitalize the first letter

        	$this->lang->load('shmyde', CURRENT_LANGUAGE);

        	$this->load->view('pages/header');
        	
        	$this->load->view('pages/'.$page);
        	
        	$this->load->view('pages/footer', $data);

        	

		}


		public function about()
		{
			$data["application_path"] = base_url().'assets/';

        	$data['title'] = "About Us - Shmyde";

        	$this->load->view('pages/header', $data);
			$this->load->view('pages/about', $data);
        	$this->load->view('pages/footer', $data);

		}
		
}
?>
