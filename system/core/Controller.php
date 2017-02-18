<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include dirname(__DIR__).'\..\application\helpers\shmyde_objects.php';

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;
        
        protected $userObject;
	
	/**
	*
	* This represents the data object
	* that is passed unto the view
	*/
	protected $data;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		$this->data = array();

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		
                $lang = get_browser_language();
                
                switch ($lang)
                {
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
		
		log_message('info', 'Controller Class Initialized');
		
		// Always record our current page. 
		// This will enable us to redirect here after login if needed
		$this->rememberme->recordOrigPage();
                
                $user_email = $this->rememberme->verifyCookie();
                
                if ($user_email && ($this->session->userdata('email') !== $user_email)) 
                {
                    $user_id = $this->user_model->get_user_id_from_email($user_email);
                    $user    = $this->user_model->get_user($user_id);
                    

                    $user_data = $this->user_model->get_user_data($user_id);
                    $this->userObject = new UserObject($user_data);
		    $this->data['user'] = $this->userObject;	
                    
                    // find user id of cookie_user stored in application database
                    // set session if necessary
                    if (!$this->session->userdata('user_id')) 
                    {
			 $newdata = array(
				'user_id'       => (int)$user->id,
				'email'     	=> (string)$user->email,
				'logged_in' 	=> TRUE,
				'is_confirmed' 	=> (bool)$user->is_confirmed,
				'is_admin' 	=>(bool)$user->is_admin
			); 
			
		    	// set session user datas
			$this->session->set_userdata($newdata);    
                        
                    }
                    
                }
		else
		{
			$this->userObject = new UserObject(null);
		}
		
		$this->data['user'] = $this->userObject;
                
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

}
