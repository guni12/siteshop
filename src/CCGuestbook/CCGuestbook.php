<?php
    /**
    * A guestbook controller as an example to show off some basic controller and model-stuff.
    *
    * @package SiteshopCore
    */

    class CCGuestbook extends CObject implements IController {
        
        private $guestbookModel;

      /**
       * Constructor
       */
      public function __construct() {
        parent::__construct();
        $this->guestbookModel = new CMGuestbook();
      }
      

/**
* Implementing interface IController. All controllers must have an index action.
 * Show a standard frontpage for the guestbook.
*/
  public function Index() {
    //echo $this->config['database'][0]['dsn']; // sqlite:C:\wamp\www\siteshop\application\data\.ht.sqlite 
    $this->views->SetTitle('Siteshop Guestbook Example');
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
      'entries'=>$this->guestbookModel->ReadAll(),
      'form_action'=>$this->request->CreateUrl('', 'handler')	//lägger till handler efter guestbook, händer med klick
    ));
  }
      
            /**
       * Handle posts from the form and take appropriate action.
       */
      public function Handler() {
          
        if(($_POST['email']) === ''){  
            if(isset($_POST['doAdd'])) {
                $this->guestbookModel->Add(strip_tags($_POST['newEntry']));
            }
            elseif(isset($_POST['doClear'])) {
                $this->guestbookModel->DeleteAll();
            }           
            elseif(isset($_POST['doCreate'])) {
                $this->guestbookModel->Init();
            }           
            $this->RedirectTo($this->request->CreateUrl($this->request->controller));
        }
      }
} 