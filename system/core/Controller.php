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

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
                $this->load->library(array('session'));
                $this->load->helper(array('url'));
                $this->load->model('user_model');
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                
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
                
                $cookie_user = $this->rememberme->verifyCookie();
                
                if ($cookie_user) 
                {
                    $user_id = $this->user_model->get_user_id_from_email($cookie_user);
                    $user    = $this->user_model->get_user($user_id);

                    // find user id of cookie_user stored in application database
                    // set session if necessary
                    if (!$this->session->userdata('user_id')) 
                    {
                        // set session user datas
                        $_SESSION['user_id']      = (int)$user->id;
                        $_SESSION['email']     = (string)$user->email;
                        $_SESSION['logged_in']    = (bool)true;
                        $_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
                        $_SESSION['is_admin']     = (bool)$user->is_admin;
                    }
                    
                }
                
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
