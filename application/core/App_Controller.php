<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Base Controller
 *
 */
class App_Controller extends CI_Controller
{

    /**
     * Site Title
     * 
     * @var string
     */
    public $site_title = '';
    
    /**
     * Page Title
     * 
     * @var string
     */
    public $page_title = '';
    
    /**
     * Page Meta Keywords
     * 
     * @var string
     */
    public $page_meta_keywords = '';
    
    /**
     * Page Meta Description
     * 
     * @var string
     */
    public $page_meta_description = '';
    
    /**
     * JS Calls on DOM Ready
     * 
     * @var array 
     */
    public $js_domready = array();
    
    /**
     * JS Calls on window load
     * 
     * @var array 
     */
    public $js_windowload = array();

    /**
     * Body classes
     * 
     * @var array 
     */
    public $body_class = array();

    /**
     * Current section
     * 
     * @var string
     */
    public $current_section = '';

    
    public $extra_css = array();


    public $extra_js = array();
    
    /**
     * Class Constructor
     */
    public function __construct()
    {
        // Call Parent Constructor
        parent::__construct();
    }
}