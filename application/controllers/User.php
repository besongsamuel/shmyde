<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User class.
 * 
 * @extends CI_Controller
 */
class User extends CI_Controller {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
            parent::__construct();
            $this->load->library(array('session'));
		
	}
	
	/**
         * The default page
         */
	public function index() {
		

		
	}
        
        public function choose_password() 
        {
            $data = array();
            
            $data['cssLinks'] = array('forgot_password');
            
            if ($this->input->server('REQUEST_METHOD') == 'POST')
            {
                $password = $this->input->post("new_password");
            
                $password_reset_code = $this->input->get("confirmation_code");
            
                $user_id = $this->input->get("id");
                
                $password_reset = $this->user_model->reset_password($user_id, $password_reset_code, $password);
                
                if(!$password_reset)
                {
                    
                }
                else
                {
                    return;
                }
                
                return;
            }

            $data["title"] = "Choose Password";
            
            $this->load->view('pages/header', $data);
            $this->load->view('user/choose_password', $data);
            $this->load->view('pages/footer', $data);
        }
        
        /**
         * This is a page that displays a user message
         */
        public function message()
        {
            $data = array();
            
            $data['title'] = "Message";
            
            $data['cssLinks'] = array('message');
            
            $data['message'] = '';
            
            $this->load->view('pages/header', $data);
            $this->load->view('user/message', $data);
            $this->load->view('pages/footer', $data);
        }

        /**
         * 
         */
        public function forgot_password() 
        {
            $data = array();
            
            $data['title'] = "Forgot Password";
            
            $data['cssLinks'] = array('forgot_password');
            
            $result['success'] = false;
            
            $email = $this->input->post('email');
            
            if ($this->input->server('REQUEST_METHOD') == 'POST')
            {
                $result['success'] = true;
                
                $user_id = $this->user_model->get_user_id_from_email($email);
                
                if($user_id)
                {
                    $password_reset_token = $this->user_model->create_reset_password_code($email);
                    $this->send_reset_password($user_id, $password_reset_token, $email);
                    $result['message'] = 'We sent you you an email with an activation link.';
                }
                else
                {
                    $result['message'] = 'Email doesn\'t exist in our database.';
                }
                
                echo json_encode($result);
                
                return;
            }
            
            
            $this->load->view('pages/header', $data);
            $this->load->view('user/forgot_password', $data);
            $this->load->view('pages/footer', $data);
        }
	
	/**
	 * register function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register() {
		
            $result = array();
            
            $result['success'] = false;
		
            // load form helper and validation library
            $this->load->helper('form');
            $this->load->library('form_validation');
		
            // set variables from the form
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->user_model->create_user($email, $password)) 
            {
                $result['success'] = true;

                $user_id = $this->user_model->get_user_id_from_email($email);
                $user    = $this->user_model->get_user($user_id);

                // set session user datas
                $_SESSION['user_id']      = (int)$user->id;
                $_SESSION['email']     = (string)$user->email;
                $_SESSION['logged_in']    = (bool)true;
                $_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
                $_SESSION['is_admin']     = (bool)$user->is_admin;

                $result['success'] = true;
                $result['user_id']      = (int)$user->id;
                $result['email']     = (string)$user->email;
                $result['logged_in']    = (bool)true;
                $result['is_confirmed'] = (bool)$user->is_confirmed;
                $result['is_admin']     = (bool)$user->is_admin;
                $this->send_resitration_confirmation($user_id, $user->confirm_id, $email);
                $result['message'] = 'We sent you you an email with an activation link.';
                echo json_encode($result);
            } 
            else
            {
                // user creation failed, this should never happen
                $result['error_heading'] = 'A user with that email already exists in our database.';
                echo json_encode($result);
            }
            
	}
        
        function send_resitration_confirmation($id,$rand,$email)
        {
            $subject = "Thanks for joining Shmyde";
            $headers = "From: admin@shmyde.com \r\n";
            $headers .= "Reply-To: no-reply@shmyde.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message = '<html><body>';
            $message.='<div style="width:550px; background-color:#CC6600; padding:15px; font-weight:bold;">';
            $message.='Email Verification';
            $message.='</div>';
            $message.='<div style="font-family: Arial;">Confiramtion mail have been sent to your email id<br/>';
            $message.='click on the below link in your verification mail id to verify your account ';
            $message.="<a href='".site_url('user/activate')."?id=$id&email=$email&confirmation_code=$rand'>Activate</a>";
            $message.='</div>';
            $message.='</body></html>';

            mail($email,$subject,$message,$headers);
        }
        
        /**
         * 
         * @param type $id
         * @param type $rand
         * @param type $email
         */
        function send_reset_password($user_id, $password_reset_token, $email)
        {
            $subject = "Shmyde Account Password Reset";
            $headers = "From: admin@shmyde.com \r\n";
            $headers .= "Reply-To: no-reply@shmyde.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message = '<html><body>';
            $message .=  '<div>';
            $message .=  '<p><b>Hi, </b></p>';
            $message .=  '<p>You resently requested to reset your email for your SHMYDE</p>';
            $message .=  '<p> Corporation account, Click the link below to reset it. </p>';
            $message .=  '<a href=\''.site_url('user/choose_password').'?id='.$user_id.'&email='.$email.'&password_reset_code='.$password_reset_token.'\'>';
            $message .=  site_url('user/choose_password').'?id='.$user_id.'&email='.$email.'&password_reset_code='.$password_reset_token;
            $message .=  '</a>';            
            $message .=  '<p style="margin-top: 10px;">If you did not request a password request, please ignore this email or</p>';
            $message .=  '<p>reply to let us know. The password reset is only valid for the next 30</p>';
            $message .=  '<p>minutes. </p>';
            $message .=  '<p style="margin-top: 10px;">Thanks,</p>';
            $message .=  '<p>SHMYDE Corporation Team</p>';
            $message .=  '</div>';
            $message .='</body></html>';

            mail($email,$subject,$message,$headers);
        }
		
	/**
	 * login function.
	 * 
	 * @access public
	 * @return void
	 */
	public function login() {
            
            $result = array();
            
            $result['success'] = false;
            
            // set variables from the form
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->user_model->resolve_user_login($email, $password)) 
            {
                if($this->input->post('remember_me') == 'on')
                {
                    $this->rememberme->setCookie($this->input->post('email'));
                }
                
                $user_id = $this->user_model->get_user_id_from_email($email);
                $user    = $this->user_model->get_user($user_id);

                // set session user datas
                $_SESSION['user_id']      = (int)$user->id;
                $_SESSION['email']     = (string)$user->email;
                $_SESSION['logged_in']    = (bool)true;
                $_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
                $_SESSION['is_admin']     = (bool)$user->is_admin;

                $result['success'] = true;
                $result['user_id']      = (int)$user->id;
                $result['email']     = (string)$user->email;
                $result['logged_in']    = (bool)true;
                $result['is_confirmed'] = (bool)$user->is_confirmed;
                $result['is_admin']     = (bool)$user->is_admin;

                echo json_encode($result);

            } 
            else 
            {

                $result['success'] = false;
                $result['error_heading'] = 'Wrong username or password.';

                echo json_encode($result);
            }
	}
	
	/**
	 * logout function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logout() {
		
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

                // remove session datas
                foreach ($_SESSION as $key => $value) 
                {
                    unset($_SESSION[$key]);
                }
                
                $this->rememberme->deleteCookie();

            } 
		
	}
        
        public function activate()
        {
            if($this->input->get('id') != null && $this->input->get('confirmation_code') != null && $this->input->get('email') != null)
            {
                $id= $this->input->get('id');
                $code= $this->input->get('confirmation_code');
                $email= $this->input->get('email');

                $activate = $this->user_model->activate_user($id, $code, $email);
                
                if($activate)
                {
                    
                }
                else
                {
                    
                }
            
            }
            
        }
        
        public function update_user_data()
        {
            $user = json_decode($this->input->post('user'));
            
            var_dump($user);
                        
            if($this->session->userdata('user_id') !== null)
            {
                $user_id = $this->session->userdata('user_id');
                
                $user_data = array();
                
                $user_data['address_line_1'] = $user->address_line_01 == null ? '' : $user->address_line_01;
                
                $user_data['address_line_2'] = $user->address_line_02 == null ? '' : $user->address_line_02;
                
                $user_data['city'] = $user->city == null ? '' : $user->city;
                
                $user_data['country'] = $user->country == null ? '' : $user->country;
                
                $user_data['postal_code'] = $user->postal_code == null ? '' : $user->postal_code;
                
                $user_data['phone_number'] = $user->phone_number == null ? '' : $user->phone_number;
                
                $this->user_model->update_user_data($user_id, $user_data);
            }
        }

}
