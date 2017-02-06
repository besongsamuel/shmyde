<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

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
	 * create_user function.
	 * 
	 * @access public
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function create_user($email, $password) 
        {
		
            $query = $this->db->query("select * from users where email = ".$this->db->escape($email));

            if($query->num_rows() > 0)
            {
                return false;
            }


            $data = array(
                    'email'      => $email,
                    'confirm_id'      => rand(100000,100000000),
                    'password'   => $this->hash_password($password),
                    'created_at' => date('Y-m-j H:i:s'),
            );

            return $this->db->insert('users', $data);
		
	}
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($email, $password) 
        {
		
            $this->db->select('password');
            $this->db->from('users');
            $this->db->where('email', $email);
            $hash = $this->db->get()->row('password');

            return $this->verify_password_hash($password, $hash);
		
	}
	
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param mixed $email
	 * @return int the user id
	 */
	public function get_user_id_from_email($email) 
        {
		
            $this->db->select('id');
            $this->db->from('users');
            $this->db->where('email', $email);

            return $this->db->get()->row('id');
		
	}
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id) 
        {		
            $this->db->from('users');
            $this->db->where('id', $user_id);
            return $this->db->get()->row();
		
	}
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) 
        {
            return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) 
        {
            return password_verify($password, $hash);
		
	}
        
        public function activate_user($id, $code, $email)
        {
            
            $users = $this->db->query("select * from users where id='$id' AND email='$email' AND confirm_id='$code' ");
            
            if($users->num_rows() > 0)
            {
                $activate = $this->db->query("update users set is_confirmed='1' where id='$id' AND  email='$email' AND confirm_id='$code'");
                
                if($activate)
                {
                    return true;
                }
                
                return false;
            }
        }
        
        /**
         * This function generates a token that shall be used to reset the user's password
         * @param type $email
         */
        public function create_reset_password_code($email) 
        {
            $password_forgotten_token = rand(100000,100000000);
            
            $data = array
            (
                'password_token' => $password_forgotten_token,
                'updated_at' => date('Y-m-j H:i:s'),
            );
            
            $this->db->where("email", $email);
            $this->db->update("users", $data);
            
            return $password_forgotten_token;
        }
        
        /**
         * THis function resets the users password and resets the password token to null
         * @param type $user_id the user id of the user
         * @param type $password_reset_code the pasword token
         * @param type $password the new passeord
         * @return boolean
         */
        public function reset_password($user_id, $password_reset_code, $password) 
        {
            $user = $this->get_user($user_id);
            
            if(isset($user) && isset($user->password_token))
            {
                $valid_reset_code = $password_reset_code == $user->password_token;
                
                if($valid_reset_code)
                {
                    $data = array
                    (
                        'password' => $this->hash_password($password),
                        'password_token' => NULL,
                        'updated_at' => date('Y-m-j H:i:s'),
                    );

                    $this->db->where("id", $user_id);
                    $this->db->update("users", $data);
                    
                    return true;
                }
                else
                {
                    return FALSE;
                }
            }
            else
            {
                return false;
            }
        }
        
        public function get_table_next_id($table_name)
        {
    	
            $count_sql = "SELECT max(id) as max_id FROM ".$table_name;	

            $count = $this->db->query($count_sql)->row()->max_id + 1;

            return $count;
        }
        
        public function update_user_data($user_id, $data) 
        {
            $get_user_data_sql = "SELECT id FROM shmyde_user_data where user_id = ".$user_id;
            
            if($this->db->query($get_user_data_sql)->row() !== null)
            {
                $data = array
                (
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone_number' => $data['phone_number'],
                    'address_line_1' => $data['address_line_1'],
                    'address_line_2' => $data['address_line_2'],
                    'country' => $data['country'],
                    'city' => $data['city'],
                    'postcode' => $data['postal_code'],
                    'last_modified' => date('Y-m-j H:i:s'),
                );
                
                $this->db->where("user_id", $user_id);
                $this->db->update("shmyde_user_data", $data);
            }
            else
            {
                               
                $insert_id = $this->get_table_next_id('shmyde_user_data');
                
                $data = array
                (
                    'id' => $insert_id,
                    'user_id' => $user_id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone_number' => $data['phone_number'],
                    'address_line_1' => $data['address_line_1'],
                    'address_line_2' => $data['address_line_2'],
                    'country' => $data['country'],
                    'city' => $data['city'],
                    'postcode' => $data['postal_code'],
                    'last_modified' => date('Y-m-j H:i:s'),
                );
                
                return $this->db->insert('shmyde_user_data', $data);
                
                
            }
        }
        
        /**
         * This function returns the user's data
         * @param type $user_id
         * @return type
         */
        public function get_user_data($user_id)
        {
            $this->db->select('shmyde_user_data.*, email, avatar');
            $this->db->from('users');
            $this->db->join('shmyde_user_data', 'shmyde_user_data.user_id = users.id');
            $this->db->where('user_id', $user_id, 'left');
            
            return $this->db->get()->row();
        }
}
