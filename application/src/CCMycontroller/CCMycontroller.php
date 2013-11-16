<?php

/**
 * Sample controller for a application builder.
 */
class CCMycontroller extends CObject implements IController {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * The page about me
     */
    public function Index() {
        //$content = new CMContent(5);
        $content = new CMContent();
        //var_dump($content->ListAll(array('type' => 'home')));
        $this->views->SetTitle('About me' . htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/page.tpl.php', array(
                    //'content' => $content,
                    'contents' => $content->ListAll(array('type' => 'home'))
                 ));
       
    }

    /**
     * The blog.
     */
    public function Blog() {
        $content = new CMContent();
        $this->views->SetTitle('My blog')
                ->AddInclude(__DIR__ . '/blog.tpl.php', array(
                    'contents' => $content->ListAll(array('type' => 'post', 'order-by' => 'id', 'order-order' => 'DESC')),
        ));
    }
    
     /**
     * Oneblogpage.
     */
    public function Oneblog($id) {
        $content = new CMContent($id);
        $this->views->SetTitle('One post')
                ->AddInclude(__DIR__ . '/blogpage.tpl.php', array(
                    'content' => $content,
        ));
    }

    /**
     * The guestbook.
     */
    public function Guestbook() {
        $guestbook = new CMGuestbook();
        $form = new CFormMyGuestbook($guestbook);
        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The form could not be processed.');
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            $this->RedirectToControllerMethod();
        }

        $this->views->SetTitle('My Guestbook')
                ->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
                    //'entries' => $guestbook->ReadAll(),
                    'guestbook' => $guestbook->ListAll(),
                    'form' => $form,
        ));
    }
    
    /**
     * Edit a selected content, or prepare to create new content if argument is missing.
     *
     * @param id integer the id of the content.
     */
    public function Edit($id = null) {
        $guestbook = new CMGuestbook($id);
        $form = new CFormMyGuestbook($guestbook);

            $status = $form->Check();        
            if ($status === false) {
                $this->AddMessage('notice', 'The form could not be processed.');
                $this->RedirectToController('edit', $id);
            } else if ($status === true) {
                $this->RedirectToController('edit', $guestbook['id']);
            }


        $title = isset($id) ? 'Update' : 'Create';
        $this->views->SetTitle("$title post: ")
                ->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
                    'guestbook' => $guestbook->ListAll(),
                    'form' => $form,
        ));
    }
        
}

/**
 * Form for the guestbook
 */
class CFormMyGuestbook extends CForm {

    /**
     * Properties
     */
    private $object;

    /**
     * Constructor
     */
    public function __construct($object) {
        parent::__construct();
        $this->object = $object;
        $add = isset($object['id']) ? 'update' : 'add';
        $this->AddElement(new CFormElementHidden('id', array('value' => $object['id'])))
            ->AddElement(new CFormElementTextarea('entry', array('label' => 'Add entry:', 'value' => $object['entry'])))
                ->AddElement(new CFormElementSubmit($add, array('callback' => array($this, 'DoAdd'), 'callback-args' => array($object))))
               // ->AddElement(new CFormElementSubmit('deleteall', array('callback' => array($this, 'DoClear'), 'callback-args' => array($object))))
    ->AddElement(new CFormElementSubmit('delete', array('callback' => array($this, 'ClearOne'), 'callback-args' => array($object))));
        
    }
    
    /**
     * Callback to save the form content to database.
     */
    public function DoAdd($form, $guestbook) {
        $guestbook['id'] = $form['id']['value'];
        $guestbook['entry'] = $form['entry']['value'];
        return $guestbook->UpdateOrCreate();
    }

    /**
     * Callback to delete the form content from database.
     */
    public function DoClear($form, $guestbook) {
        $guestbook->DeleteAll();
    }
    
    /**
     * Callback to delete the form content from database.
     */
    public function ClearOne($form, $guestbook) {
        $guestbook['id'] = $form['id']['value'];
        $guestbook->Delete();
        CSiteshop::Instance()->RedirectTo('my', 'guestbook');
    }

}