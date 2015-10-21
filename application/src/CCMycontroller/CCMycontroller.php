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
            if($val['id'] == 14)
            {
                $byline1 = $val;
            }
            if($val['id'] == 15)
            {
                $byline2 = $val;
            }
            if($val['id'] == 16)
            {
                $byline3 = $val;
            }
            if($val['id'] == 17)
            {
                $byline4 = $val;
            }
        }

		$text1 = t("Daddys_workshop");
		$text2 = t("My daddys workshop before he passed away");

        $by1 = $content->Filter($byline1['data'], $byline1['filter']);
        $by2 = $content->Filter($byline2['data'], $byline1['filter']);
        $by3 = $content->Filter($byline3['data'], $byline1['filter']);
        $by4 = $content->Filter($byline4['data'], $byline1['filter']);
        $byl1 = t($by1);
        $byl2 = t($by2);
        $byl3 = t($by3);
        $byl4 = t($by4);
        $img = array('title' => '"' . $text1 . '"', 'source' => '/img/snickarboden.JPG', 'alt' => '"' . $text2 . '"', 'width' => '336', 'height' => '397');

        $this->views->SetTitle(t('About me') . htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/page.tpl.php', array('contents' => $content->ListAll(array('type' => 'home'))), 'primary')
               ->AddInclude(__DIR__ . '/imagepage.tpl.php', array('img' => $img),'sidebar')
               ->AddInclude(__DIR__ . '/byline1.tpl.php', array('byl1' => $byl1),'footer-column-one')
        ->AddInclude(__DIR__ . '/byline2.tpl.php', array('byl2' => $byl2),'footer-column-two')
        ->AddInclude(__DIR__ . '/byline3.tpl.php', array('byl3' => $byl3),'footer-column-three')
        ->AddInclude(__DIR__ . '/byline4.tpl.php', array('byl4' => $byl4),'footer-column-four');
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
        $thePost = t($content['data']);
        $filtered = $contents->Filter($thePost,$content['filter'] );
        $thePost = $filtered;
        $theTitle = t($content['title']);

        $currentMonth = $lastMonth = $twoMonthsAgo = $restOfMonths = array();
        
        setlocale(LC_ALL, 'sv_SE', 'sv');
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
                ->AddInclude(__DIR__ . '/blog.tpl.php', array('title' => t('The Siteshop Blog'), 'content' => $content, 'theTitle' => $theTitle, 'thePost' => $thePost), 'primary')
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

        $thePost = t($content['data']);
        $filtered = $content->Filter($thePost,$content['filter'] );
        $thePost = $filtered;
        $theTitle = t($content['title']);

        $title = t('One post');
        $this->views->SetTitle($title)
                ->AddInclude(__DIR__ . '/blog.tpl.php', array('title' => $title, 'content' => $content, 'thePost' => $thePost, 'theTitle' => $theTitle), 'primary')
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
            if($val['id'] == 20)
            {
                $footer1 = $val;
            }
            if($val['id'] == 21)
            {
                $footer2 = $val;
            }
            if($val['id'] == 22)
            {
                $footer3 = $val;
            }
            if($val['id'] == 23)
            {
                $footer4 = $val;
            }
        }

        $sek1 = t($footer1['data']);
        $sek2 = t($footer2['data']);
        $sek3 = t($footer3['data']);
        $sek4 = t($footer4['data']);
        $filtered1 = $content->Filter($sek1,$footer1['filter'] );
        $filtered2 = $content->Filter($sek2,$footer2['filter'] );
        $filtered3 = $content->Filter($sek3,$footer3['filter'] );
        $filtered4 = $content->Filter($sek4,$footer4['filter'] );
        $the1 = $filtered1;
        $the2 = $filtered2;
        $the3 = $filtered3;
        $the4 = $filtered4;

        $content1 = $content->ListAll(array('type' => 'secret1'));
        $con1 = t($content1[0]['data']);
        $tit1 = t($content1[0]['title']);
        $filt1 = $content->Filter($con1,$content1[0]['filter']);
        $filtTit1 = $content->Filter($tit1,$content1[0]['filter']);
        $theSecret = $filt1;
        $theTitle = $filtTit1;

        $content2 = $content->ListAll(array('type' => 'secret2'));
        $con2 = t($content2[0]['data']);
        $tit2 = t($content2[0]['title']);
        $filt2 = $content->Filter($con2,$content2[0]['filter']);
        $filtTit2 = $content->Filter($tit2,$content2[0]['filter']);
        $theSecretSide = $filt2;
        $theTitleSide = $filtTit2;

        
        $this->views->SetTitle(t('Secretside') . htmlEnt($content['title']))
                ->AddInclude(__DIR__ . '/secrets.tpl.php', array('theSecret' => $theSecret, 'theTitle' => $theTitle), 'primary')
               ->AddInclude(__DIR__ . '/secretside.php', array('theSecretSide' => $theSecretSide, 'theTitleSide' => $theTitleSide),'sidebar')
               ->AddInclude(__DIR__ . '/footer1.php', array('the1' => $the1),'footer-column-one')
        ->AddInclude(__DIR__ . '/footer2.php', array('the2' => $the2),'footer-column-two')
        ->AddInclude(__DIR__ . '/footer3.php', array('the3' => $the3),'footer-column-three')
        ->AddInclude(__DIR__ . '/footer4.php', array('the4' => $the4),'footer-column-four');
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
                ->AddElement(new CFormElementSubmit(t('delete'), array('callback' => array($this, 'ClearOne'), 'callback-args' => array($object))));
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