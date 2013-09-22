<?php
    /**
    * Holding an instance of CSiteshop to enable use of $this in subclasses.
    *
    * @package SiteshopCore
    */
    class CObject {

       public $config;
       public $request;
       public $data;
       public $db;
       public $views;
       public $session;

       /**
        * Constructor
        */
       protected function __construct() {
        $ss = CSiteshop::Instance();
        $this->config   = &$ss->config;
        $this->request  = &$ss->request;
        $this->data     = &$ss->data;
        $this->db   = &$ss->db;
        $this->views = &$ss->views;
        $this->session = &$ss->session;
      }
      
      	/**
* Redirect to another url and store the session
*/
protected function RedirectTo($url) {
    $ss = CSiteshop::Instance();
    if(isset($ss->config['debug']['db-num-queries']) && $ss->config['debug']['db-num-queries'] && isset($ss->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }
    if(isset($ss->config['debug']['db-queries']) && $ss->config['debug']['db-queries'] && isset($ss->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }
    if(isset($ss->config['debug']['timer']) && $ss->config['debug']['timer']) {
$this->session->SetFlash('timer', $ss->timer);
    }
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($url));
  }



    }
