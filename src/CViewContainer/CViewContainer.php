<?php
/**
 * A container to hold a bunch of views.
 *
 * @package SiteshopCore
 */
class CViewContainer {

	/**
	 * Members
	 */
	private $data = array();
	private $views = array();
	

	/**
	 * Constructor
	 */
	public function __construct() { ; }


	/**
	 * Getters.
	 */
  public function GetData() { return $this->data; }
  
  
	/**
	 * Set the title of the page.
	 *
	 * @param $value string to be set as title.
	 */
	public function SetTitle($value) {
    return $this->SetVariable('title', $value);
  }


	/**
	 * Set any variable that should be available for the theme engine.
	 *
	 * @param $value string to be set as title.
	 */
	public function SetVariable($key, $value) {
	  $this->data[$key] = $value;
	  return $this;
  }


	/**
	 * Add a view as file to be included and optional variables.
	 *
	 * @param $file string path to the file to be included.
	 * @param vars array containing the variables that should be avilable for the included file.
	 */
	public function AddInclude($file, $variables=array()) {
		//var_dump($variables);//array (size=3)'login_form' => object(CFormUserLogin)[11]public 'form' => array (size=0)empty public 'elements' => array (size=3)
        //  'acronym' => object(CFormElementText)[12]... 'password' => object(CFormElementPassword)[13] ... 'login' => object(CFormElementSubmit)[14]
        // ... 'allow_create_user' => boolean true 'create_user_url' => string 'http://localhost/bth/siteshop/user/create' (length=41)
	  $this->views[] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
	  //var_dump($this);
	  return $this;
  }


	/**
	 * Render all views according to their type.
	 */
	public function Render() {
	  foreach($this->views as $view) {
      switch($view['type']) {
        case 'include':
          extract($view['variables']);
          include($view['file']);
          break;
      }
	  }
  }


}