<?php
/**
 * Holding an instance of CSiteshop to enable use of $this in subclasses and provide some helpers.
 *
 * @package SiteshopCore
 */
class CObject {
    /**
    * Members
    */
    protected $ss;
    protected $config;
    protected $request;
    protected $data;
    protected $db;
    protected $views;
    protected $session;
    protected $user;

    /**
    * Constructor, can be instantiated by sending in the $ss reference.
    */
    protected function __construct($ss=null) {
        if(!$ss) {
            $ss = CSiteshop::Instance();
        } 
        $this->ss       = &$ss;
        $this->config   = &$ss->config;
        $this->request  = &$ss->request;
        $this->data     = &$ss->data;
        $this->db       = &$ss->db;
        $this->views    = &$ss->views;
        $this->session  = &$ss->session;
        $this->user     = &$ss->user;
    }


    /**
    * Wrapper for same method in CSiteshop. See there for documentation.
    */
    protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
        $this->ss->RedirectTo($urlOrController, $method, $arguments);
    }
        


    /**
    * Wrapper for same method in CSiteshop. See there for documentation.
    */
    protected function RedirectToController($method = null, $arguments = null) {
        $this->ss->RedirectToController($method, $arguments);
    }

    /**
     * Wrapper for same method in CSiteshop. See there for documentation.
     */
    protected function RedirectToControllerMethod($controller = null, $method = null, $arguments = null) {
        $this->ss->RedirectToControllerMethod($controller, $method, $arguments);
    }

    /**
     * Wrapper for same method in CSiteshop. See there for documentation.
     */
    protected function AddMessage($type, $message, $alternative = null) {
        return $this->ss->AddMessage($type, $message, $alternative);
    }

    /**
     * Wrapper for same method in CSiteshop. See there for documentation.
     */
    protected function CreateUrl($urlOrController = null, $method = null, $arguments = null) {
        return $this->ss->CreateUrl($urlOrController, $method, $arguments);
    }
    
    /**
   * Wrapper for same method in CSiteshop. See there for documentation.
   */
  protected function CreateCleanUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->ss->CreateCleanUrl($urlOrController, $method, $arguments);
  }


  /**
   * Wrapper for same method in CSiteshop. See there for documentation.
   */
  protected function CreateUrlToController($method=null, $arguments=null) {
    return $this->ss->CreateUrlToController($method, $arguments);
  }


  /**
   * Wrapper for same method in CSiteshop. See there for documentation.
   */
  protected function CreateUrlToControllerMethod($arguments=null) {
    return $this->ss->CreateUrlToControllerMethod($arguments);
  }



  /**
   * Wrapper for same method in CSiteshop. See there for documentation.
   */
  protected function CreateUrlToControllerMethodArguments() {
    return $this->ss->CreateUrlToControllerMethodArguments();
  }


    /**
     * Wrapper for same method in CSiteshop. See there for documentation.
     */
    protected function CreateMenu($options) {
        return $this->ss->CreateMenu($options);
    }
    
     /**
	 * Wrapper for same method in CSiteshop. See there for documentation. Tries to find view 
	 * related to class, if it fails it tries to find view related to parent class.
   */
  protected function LoadView($view) {
    $file = $this->ss->LoadView(get_class($this), $view);
    if(!$file) {
      $file = $this->ss->LoadView(get_parent_class($this), $view);
    }
    if(!$file) {
      throw new Exception(t('No such view @viewname.', array('@viewname' => $view)));
    }
    return $file;
  }
  
  /**
   * Wrapper for same method in CSiteshop. See there for documentation.
   * 
   */

   protected function SetLocale() {
    return $this->ss->SetLocale();
  }


}

