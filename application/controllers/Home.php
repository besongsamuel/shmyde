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
        
        /// <summary>
        /// This method is called when the user clicks on 
        /// The send in the contact us form in the client
        /// </summary>
        public function ContactUs()
        {
            $contactName = $this->input->post('contactName');
            $contactEmail = $this->input->post('contactEmail');
            $contactComment = $this->input->post('contactComment');
            set_error_handler(function(){ });
            $this->send_contactus_mail($contactName, $contactEmail, $contactComment);
            restore_error_handler();
        }
        
        /// <summary>
        /// Sends an email to the shmyde team containing the user's comment
        /// </summary>
        /// <param name="$contactName">The name of the contact</param>
        /// <param name="$contactEmail">The email of the contact</param>
        /// <param name="$contactComment">The comment of the contact</param>
        private function send_contactus_mail($contactName, $contactEmail, $contactComment)
        {
                $subject = "Comment From Customer - ";
                $headers = "From: ".$contactEmail." \r\n";
                $headers .= "Reply-To: ".$contactEmail." \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $message = '<html><body>';
                $message.='<div style="width:550px; background-color:#CC6600; padding:15px; font-weight:bold;">';
                $message.='Dear Shmyde Team, ';
                $message.='</div>';
                $message.='
                <div style="font-family: Arial;">
                <p>Find below my comments </p>
                <p></p>
                <p></p>
                <p align="center">'.$contactComment.'</p>
                <p></p>
                <br/>
                <p>Best Regards</p>
                <p>'.$contactName.'</p>
                ';
                $message.='</div>';
                $message.='</body></html>';
                mail(SHMYDE_CONTACT,$subject,$message,$headers);
        }
}

