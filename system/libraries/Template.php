<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Template 
{
        /**
	 * Reference to the CodeIgniter singleton
	 *
	 * @var object
	 */
	protected $CI;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Initialize Profiler
	 *
	 * @param	array	$config	Parameters
	 */
	public function __construct($config = array())
	{
            $this->CI =& get_instance();		
	}
               
        function load($tpl_view, $body_view = null, $data = null) 
        {
            if ( ! is_null( $body_view ) ) 
            {
                if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view ) ) 
                {
                    $body_view_path = $tpl_view.'/'.$body_view;
                }
                else if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view.'.php' ) ) 
                {
                    $body_view_path = $tpl_view.'/'.$body_view.'.php';
                }
                else if ( file_exists( APPPATH.'views/'.$body_view ) ) 
                {
                    $body_view_path = $body_view;
                }
                else if ( file_exists( APPPATH.'views/'.$body_view.'.php' ) ) 
                {
                    $body_view_path = $body_view.'.php';
                }
                else
                {
                    show_error('Unable to load the requested file: ' . $tpl_view.'/'.$body_view.'.php');
                }

                $body = $this->CI->load->view($body_view_path, $data, TRUE);

                if ( is_null($data) ) 
                {
                    $data = array('body' => $body);
                }
                else if ( is_array($data) )
                {
                    $data['body'] = $body;
                }
                else if ( is_object($data) )
                {
                    $data->body = $body;
                }
            }

            $this->CI->load->view('templates/'.$tpl_view, $data);
        }
}
