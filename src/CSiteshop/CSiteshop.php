<?php
/**
 * Main class for Siteshop, holds everything.
 *
 * @package SiteshopCore
 */
class CSiteshop implements ISingleton {

    private static $instance = null;
    
    public $config = array();
    public $request;
    public $data;
    public $db;
    public $views;
    public $session;
    public $timer = array(); 

  /**
   * Constructor
   */
  protected function __construct() {
      // time page generation
      $this->timer['first'] = microtime(true); 
      
    // include the site specific config.php and create a ref to $ss to be used by config.php
    $ss = &$this;
    require(SITESHOP_APPLICATION_PATH.'/config.php');
    
    // Start a named session
      session_name($this->config['session_name']);	// localhost	  
      session_start();
      $this->session = new CSession($this->config['session_key']);	// siteshop
      $this->session->PopulateFromSession();

    // Set default date/time-zone
    date_default_timezone_set($this->config['timezone']);

    	// Create a database object.
    if(isset($this->config['database'][0]['dsn'])) {
        $this->db = new CDatabase($this->config['database'][0]['dsn']);
     }
     
     // Create a container for all views and theme data
     $this->views = new CViewContainer();
  }
  
  
  /**
   * Singleton pattern. Get the instance of the latest created object or create a new one. 
   * @return CChef The instance of this class.
   */
  public static function Instance() {
    if(self::$instance == null) {
      self::$instance = new CSiteshop();
    }
    return self::$instance;
  }

  
  /**
   * Frontcontroller, check url and route to controllers.
   */
public function FrontControllerRoute() {   
    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest($this->config['url_type']);
    $this->request->Init($this->config['base_url']);
    $controller = $this->request->controller;	// guestbook
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
	
	// Is the controller enabled in config.php?
	$controllerExists 	= isset($this->config['controllers'][$controller]);
	$controllerEnabled	= false;
	$className         	= false;
	$classExists      	= false;

	if($controllerExists) {
		$controllerEnabled	= ($this->config['controllers'][$controller]['enabled'] == true);
		$className        	= $this->config['controllers'][$controller]['class'];
		$classExists      	= class_exists($className);
	}
        
	
    // Check if controller has a callable method in the controller class, if then call it
	if($controllerExists && $controllerEnabled && $classExists) 
	{
		$rc = new ReflectionClass($className);
		if($rc->implementsInterface('IController')) 
		{
            $formattedMethod = str_replace(array('_', '-'), '', $method);
			if($rc->hasMethod($formattedMethod)) 
			{
				$controllerObj = $rc->newInstance();
				$methodObj = $rc->getMethod($formattedMethod);
				if($methodObj->isPublic()) 
				{
					$methodObj->invokeArgs($controllerObj, $arguments);
				} 
				else 
				{
					die("404. " . get_class() . ' error: Controller method not public.');          
				}
			} 
			else 
			{
				die("404. " . get_class() . ' error: Controller does not contain method.');
			}
		} 
		else 
		{
			die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
		}
    } 
    else 
    { 
		die('404. Page is not found.');
    }
}
   
  /**
   * Theme Engine Render, renders the views using the selected theme.
   */
    public function ThemeEngineRender() {
        // Save to session before output anything
		//print_r($this->session);	// CSession Object ( [key:CSession:private] => siteshop [data:CSession:private] => Array ( ) [flash:CSession:private] => ) 
    $this->session->StoreInSession();
    
    // Is theme enabled?
    if(!isset($this->config['theme'])) {
      return;
    }
    
    // Get the paths and settings for the theme
	
	$themeName 	= $this->config['theme']['name'];
	$themePath 	= SITESHOP_INSTALL_PATH . "/themes/{$themeName}";
	$themeUrl 	= $this->request->base_url . "themes/{$themeName}";
       
	// Add stylesheet path to the $ss->data array
	$this->data['stylesheet'] = "{$themeUrl}/style.css";

	// Include the global functions.php and the functions.php that are part of the theme
	$ss = &$this;
	include(SITESHOP_INSTALL_PATH . '/themes/functions.php');
	$functionsPath = "{$themePath}/functions.php";
	if(is_file($functionsPath)) {
		include $functionsPath;
	}

        // Extract $ss->data and $ss->view->data to own variables and handover to the template file
		//print_r($this->data);	// a list of content that gets sent
		//echo '<br />';
        extract($this->data);	
		//print_r($this->views->GetData());
        extract($this->views->GetData());  // more content  - title
        include("{$themePath}/default.tpl.php");
    }
} 