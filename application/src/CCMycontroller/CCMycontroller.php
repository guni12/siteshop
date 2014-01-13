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
        $content = new CMContent();
        $bylines = $content->ListAll(array('type' => 'byline'));
        
        foreach ($bylines as $val)
        {
            if($val['id'] == 16)
            {
                $byline1 = $val;
            }
            if($val['id'] == 17)
            {
                $byline2 = $val;
            }
            if($val['id'] == 18)
            {
                $byline3 = $val;
            }
            if($val['id'] == 19)
            {
                $byline4 = $val;
            }
        }

		$text1 = t("Daddys workshop");
		$text2 = t("My dad's workshop before he passed away");
        $img = array('title' => $text1, 'source' => '/img/snickarboden.JPG', 'alt' => $text2, 'width' => '336', 'height' => '397');

        $this->views->SetTitle(t('About me') . htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/page.tpl.php', array('contents' => $content->ListAll(array('type' => 'home'))), 'primary')
               ->AddInclude(__DIR__ . '/imagepage.tpl.php', array('img' => $img),'sidebar')
               ->AddInclude(__DIR__ . '/byline1.tpl.php', array('byline1' => $byline1),'footer-column-one')
        ->AddInclude(__DIR__ . '/byline2.tpl.php', array('byline2' => $byline2),'footer-column-two')
        ->AddInclude(__DIR__ . '/byline3.tpl.php', array('byline3' => $byline3),'footer-column-three')
        ->AddInclude(__DIR__ . '/byline4.tpl.php', array('byline4' => $byline4),'footer-column-four');
    }

    /**
     * The blog.
     */
    public function Blog($id = null) {
        $contents = new CMContent();
        $posts = $contents->ListAll(array('type' => 'post'));
        $count = 0;
        foreach($posts as $val){
            if($val['id'] > $count)
            {
                $count = $val['id'];
            }
        } 
        $get_id = count($posts);
        if($id != null)
        {
            $count = $id;
        }
        $content = new CMContent($count);
        
        $currentMonth = $lastMonth = $twoMonthsAgo = $restOfMonths = array();
        
        setlocale(LC_ALL, 'sv_SE', 'sv_SE.ISO8859-1', 'sv_SE.ISO8859-15', 'sv_SE.iso88591', 'sv_SE.iso885915', 'swedish, sv_FI, sv_FI@euro', 'sv_FI.iso88591', 'sv_FI.iso885915@euro', 'swedish_finland');
        $first = date('Y-m');       
        $second = date("Y-m", strtotime("-1 month"));
        $third = date("Y-m", strtotime("-2 month"));
        
        foreach($posts as $val){
            //echo substr( $val['created'],0,4);
            if (substr( $val['created'],0,7) == $first)
            {
                array_push($currentMonth, $val);                
            }
            if (substr( $val['created'],0, 7) == $second)
            {
                array_push($lastMonth, $val);                
            }
             if (substr( $val['created'],0, 7) == $third)
            {
                array_push($twoMonthsAgo, $val);                
            }
            if (substr( $val['created'],0, 7) != $first && substr( $val['created'],0, 7) != $second && substr( $val['created'],0, 7) != $third)
            {
                array_push($restOfMonths, $val);                
            }
        }
        
        $older =t('Older articles');
        
        $month = date('m');
        $currentYear = date('Y');
        $passedMonth = date('m', strtotime('-1 month'));
        $monthYear = date('Y', strtotime('-1 month'));
        $olderMonth = date('m', strtotime('-2 month'));
        $olderYear = date('Y', strtotime('-2 month'));

        $thisMonth = ucfirst(strftime('%B %Y', mktime(0, 0, 0, $month, 1, $currentYear )));
        $previousMonth = ucfirst(strftime('%B %Y', mktime(0, 0, 0, $passedMonth, 1, $monthYear )));
        $thirdMonth = ucfirst(strftime('%B %Y', mktime(0, 0, 0, $olderMonth, 1, $olderYear )));
        
        $this->views->SetTitle(t('My blog'))
                ->AddInclude(__DIR__ . '/blog.tpl.php', array('title' => t('The Siteshop Blog'), 'content' => $content), 'primary')
                ->AddInclude(__DIR__ . '/blogside.tpl.php', array('contents' => $contents->ListAll(array('type' => 'post', 'order-by' => 'id', 'order-order' => 'DESC')),
                    'currentMonth' => $currentMonth, 'lastMonth' => $lastMonth, 'twoMonthsAgo' => $twoMonthsAgo, 'restOfMonths' => $restOfMonths, 'thisMonth' => $thisMonth,
                    'previousMonth'=> $previousMonth, 'thirdMonth' => $thirdMonth, 'older' => $older
                    ), 'sidebar')
                ;
    }

    /**
     * Oneblogpage.
     */
    public function Oneblog($id) {
        $content = new CMContent($id);
        $title = t('One post');
        $this->views->SetTitle($title)
                ->AddInclude(__DIR__ . '/blog.tpl.php', array('title' => $title, 'content' => $content), 'primary')
                ;
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

        $this->views->SetTitle(t('My Guestbook'))
                ->AddInclude(__DIR__ . '/guestbook.tpl.php', array('form' => $form), 'primary')
                ->AddInclude(__DIR__ . '/guestside.tpl.php', array('guestbook' => $guestbook->ListAll()), 'sidebar');
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


        $title = isset($id) ? t('Update') : t('Create');
        $this->views->SetTitle("$title post: ")
                ->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
                    'guestbook' => $guestbook->ListAll(),
                    'form' => $form,
        ));
    }
    
       
       /**
     * The secret page
     */
    public function Secrets() {
        $content = new CMContent();
        $footers = $content->ListAll(array('type' => 'footer'));
        $if = new CInterceptionFilter();
        $if->SecretRoleOrForbidden();
        
        foreach ($footers as $val)
        {
            if($val['id'] == 22)
            {
                $footer1 = $val;
            }
            if($val['id'] == 23)
            {
                $footer2 = $val;
            }
            if($val['id'] == 24)
            {
                $footer3 = $val;
            }
            if($val['id'] == 25)
            {
                $footer4 = $val;
            }
        }
        
        $this->views->SetTitle(t('Secretside') . htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/secrets.tpl.php', array('content1' => $content->ListAll(array('type' => 'secret1'))), 'primary')
               ->AddInclude(__DIR__ . '/secretside.php', array('content2' => $content->ListAll(array('type' => 'secret2'))),'sidebar')
               ->AddInclude(__DIR__ . '/footer1.php', array('footer1' => $footer1),'footer-column-one')
        ->AddInclude(__DIR__ . '/footer2.php', array('footer2' => $footer2),'footer-column-two')
        ->AddInclude(__DIR__ . '/footer3.php', array('footer3' => $footer3),'footer-column-three')
        ->AddInclude(__DIR__ . '/footer4.php', array('footer4' => $footer4),'footer-column-four');
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
        $add = isset($object['id']) ? t('update') : t('add');
        $this->AddElement(new CFormElementHidden('id', array('value' => $object['id'])))
                ->AddElement(new CFormElementTextarea('entry', array('label' => t('Add entry:'), 'value' => $object['entry'])))
                ->AddElement(new CFormElementSubmit($add, array('callback' => array($this, 'DoAdd'), 'callback-args' => array($object))))
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
    public function ClearOne($form, $guestbook) {
        $guestbook['id'] = $form['id']['value'];
        $guestbook->Delete();
        CSiteshop::Instance()->RedirectTo('my', 'guestbook');
    }
 
    

}