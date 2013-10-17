<?php
/**
 * A form to login the user profile.
 * 
 * @package SiteshopCore
 */
class CFormUserLogin extends CForm {

  /**
   * Constructor
   */
  public function __construct($object) {
    parent::__construct();
    if(CSiteshop::Instance()->config['theme']['name'] == 'bootwitter'){
        
    $this->AddElement(new CFormElementTextTwit('acronym'))
         ->AddElement(new CFormElementPasswordTwit('password'))
         ->AddElement(new CFormElementButton('login', array('callback'=>array($object, 'DoLogin'))));

    $this->SetValidation('acronym', array('not_empty'))
         ->SetValidation('password', array('not_empty'));
    }  else {
        $this->AddElement(new CFormElementText('acronym'))
         ->AddElement(new CFormElementPassword('password'))
         ->AddElement(new CFormElementSubmit('login', array('callback'=>array($object, 'DoLogin'))));

    $this->SetValidation('acronym', array('not_empty'))
         ->SetValidation('password', array('not_empty'));
    
    }
  }
  
}
