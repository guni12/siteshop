<?php
/**
* A test controller for themes.
*
* @package SiteshopCore
*/
class CCTheme extends CObject implements IController {


/**
* Constructor
*/
    public function __construct() { parent::__construct();
        if(CSiteshop::Instance()->config['theme']['name'] == 'bootwitter'){
            $this->views->AddStyle('body:hover{background:url('.$this->request->base_url.'themes/bootwitter/960_grid_12_col.png) center 6px repeat-y #fff}');
        }else{   
            $this->views->AddStyle('body:hover{background:url('.$this->request->base_url.'themes/grid/grid_12_60_20.png) center 6px repeat-y #fff;}');
        }                                     
    }

    
    /**
* Display what can be done with this controller.
*/
  public function Index() {
    // Get a list of all kontroller methods
    $rc = new ReflectionClass(__CLASS__);
    $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
    $items = array();
    foreach($methods as $method) {
      if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
        $items[] = $this->request->controller . '/' . mb_strtolower($method->name);
      }
   }

    $this->views->SetTitle('Theme')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                  'theme_name' => $this->config['theme']['name'],
                  'methods' => $items,
                ));
  }
    public function H1h6(){
         $this->views->SetTitle('h1h6')
                ->AddInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');
    }
      
/**
* Put content in some regions.
*/
  public function SomeRegions() {
    $this->views->SetTitle('Theme display content for some regions');
        if(CSiteshop::Instance()->config['theme']['name'] == 'bootwitter'){
            $this->views->AddString('Primary region<hr />This is the primary region', array(), 'primary')
            ->AddStyle('.primary{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the sidebar region', array(), 'sidebar')
            ->AddStyle('.sidebar{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the <b>triptych-last</b> region', array(), 'triptych-last')
            ->AddStyle('.triptych-last{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the <b>featured-middle</b> region', array(), 'featured-middle') 
            ->AddStyle('.featured-middle{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the <b>footer-column-three</b> region', array(), 'footer-column-three')
            ->AddStyle('.footer-column-three{background-color:hsla(0,0%,90%,0.5);}');
        }else{
            $this->views->AddString('Primary region<hr />This is the primary region', array(), 'primary')
            ->AddStyle('#primary{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the sidebar region', array(), 'sidebar')
            ->AddStyle('#sidebar{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the <b>triptych-last</b> region', array(), 'triptych-last')
            ->AddStyle('#triptych-last{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the <b>featured-middle</b> region', array(), 'featured-middle')
            ->AddStyle('#featured-middle{background-color:hsla(0,0%,90%,0.5);}')
            ->AddString('This is the <b>footer-column-three</b> region', array(), 'footer-column-three')
            ->AddStyle('#footer-column-three{background-color:hsla(0,0%,90%,0.5);}'
            );
        }
                
    if(func_num_args()) {
      foreach(func_get_args() as $val) {
        $this->views->AddString("This is region: $val", array(), $val);
        if(CSiteshop::Instance()->config['theme']['name'] == 'bootwitter'){
            $this->views->AddStyle('.'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
        }else{
            $this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
        }
      }
    }
  }
  
     /**
     * Put content in all regions.
     */
    public function AllRegions() {
        $this->views->SetTitle('Theme display content for all regions');
        foreach($this->config['theme']['regions'] as $val) {
            $this->views->AddString("This is region: $val", array(), $val);
            if(CSiteshop::Instance()->config['theme']['name'] == 'bootwitter'){
                $this->views->AddStyle('.'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
            }else{
                $this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
            }
        }
    }
} 
