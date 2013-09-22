<?php
    /**
    * Parse the request and identify controller, method and arguments.
    *
    * @package SiteshopCore
    */
class CRequest {

	public $cleanUrl;
	public $querystringUrl;
	public $request_uri;
	public $script_name;
	public $query;
	public $splits;
	public $controller;
	public $method;
	public $arguments;
	public $base_url;
	public $current_url;
	
	/**
   * Constructor
   *
   * Decide which type of url should be generated as outgoing links.
   * default      = 0      => index.php/controller/method/arg1/arg2/arg3
   * clean        = 1      => controller/method/arg1/arg2/arg3
   * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
   *
   * @param boolean $urlType integer 
   */
	public function __construct($urlType=0) {
		$this->cleanUrl       = $urlType= 1 ? true : false;
		$this->querystringUrl = $urlType= 2 ? true : false;
	}
	
	/**
   * Create a url in the way it should be created.
   *
   */
public function CreateUrl($url=null, $method=null) {
    // If fully qualified just leave it.
    if(!empty($url) && (strpos($url, '://') || $url[0] == '/')){
        return $url;
    }
    //Get current controller if empty and method choosen
    if(empty($url) && !empty($method)){
        $url = $this->controller;
    }
    // Create url according to configured style
    $prepend = $this->base_url;
    if($this->cleanUrl) {
      ;
    } elseif ($this->querystringUrl) {
      $prepend .= 'index.php?q=';
    } else {
      $prepend .= 'index.php/';
    }
    return $prepend . rtrim("$url/$method", '/');
    
	}


      /**
       * Init the object by parsing the current url request.
	   /controller/method/arg1/arg2/arg3
       */
	public function Init($baseUrl = null) {
	      /**
       * Parse the current url request and divide it in controller, method and arguments.
       *
       * Calculates the base_url of the installation. Stores all useful details in $this.
       *
       * @param $baseUrl string use this as a hardcoded baseurl.
       */

        // Take current url and divide it in controller, method and arguments
        $requestUri = $_SERVER['REQUEST_URI'];	//t.ex.  /siteshop/guestbook
        $scriptPart = $scriptName = $_SERVER['SCRIPT_NAME'];	//: /siteshop/index.php		

        // Check if url is in format controller/method/arg1/arg2/arg3
        if(substr_compare($requestUri, $scriptName, 0)) {
          $scriptPart = dirname($scriptName);
		  //echo $scriptPart;	// mitt ex. /siteshop
        }
       
        //Set query to be everything after base_url, except the optional querystring
        $query = trim(substr($requestUri, strlen(rtrim($scriptPart, '/'))), '/'); 
		// echo $query;		// guestbook
        $pos = strcspn($query, '?');
		//echo $pos;	//9 mitt exempel
        if ($pos){
            $query = substr($query, 0, $pos);
			//echo $query;	// guestbook
        }
        
        // Check if this looks like a querystring approach link
        if(substr($query, 0, 1) === '?' && isset($_GET['q'])) {
          $query = trim($_GET['q']);
        }
        $splits = explode('/', $query);
       
        // Set controller, method and arguments
        $controller =  !empty($splits[0]) ? $splits[0] : 'index';
        $method     =  !empty($splits[1]) ? $splits[1] : 'index';
        $arguments = $splits;
        unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
       
        // Prepare to create current_url and base_url
        $currentUrl = $this->GetCurrentUrl();
        $parts       = parse_url($currentUrl); // Array ( [scheme] => http [host] => localhost [path] => /siteshop/guestbook )
        $baseUrl     = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');

        // Store it
        $this->base_url     = rtrim($baseUrl, '/') . '/';
		//echo $this->base_url . '<br />';	// http://localhost/siteshop/
        $this->current_url  = $currentUrl;
		//echo $this->current_url . '<br />';	// http://localhost/siteshop/guestbook
        $this->request_uri  = $requestUri;
		//echo $this->request_uri . '<br />';	//	/siteshop/guestbook
        $this->script_name  = $scriptName;
		//echo $this->script_name . '<br />';	//	/siteshop/index.php
        $this->query        = $query;
		//echo $this->query . '<br />';		// guestbook	
        $this->splits        = $splits;
		//print_r($this->splits) . '<br />';	// Array ( [0] => guestbook ) 
        $this->controller    = $controller;
		//echo $this->controller;	//guestbook
        $this->method        = $method;
		//echo $this->method . '<br />';	// index
        $this->arguments    = $arguments;
		//print_r($this->arguments) . '<br />';	// Array ( ) 
    }
	  
	/**
   * Get the url to the current page. 
   */  	 
	public function GetCurrentUrl() {
		$url = "http";
		$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
		$url .= "://";
		$serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
		(($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
		$url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
	return $url;
    }
}