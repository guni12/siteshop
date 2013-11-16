<?php
/**
 * A form for creating a new user.
 * 
 * @package SiteshopCore
 */
class CFormUserCreate extends CForm {

  /**
   * Constructor
   */
  public function __construct($object) {
    parent::__construct();
        
    $this->AddElement(new CFormElementText('acronym', array('size'=>'35','required'=>true)))
         ->AddElement(new CFormElementPassword('password', array('size'=>'35','required'=>true)))
         ->AddElement(new CFormElementPassword('password1', array('size'=>'35','required'=>true, 'label'=>'Password again:')))
         ->AddElement(new CFormElementText('name', array('size'=>'35','required'=>true)))
         ->AddElement(new CFormElementText('email', array('size'=>'35','required'=>true)))
         ->AddElement(new CFormElementSubmit('create', array('callback'=>array($object, 'DoCreate'))));
         
    $this->SetValidation('acronym', array('not_empty'))
         ->SetValidation('password', array('not_empty'))
         ->SetValidation('password1', array('not_empty'))
         ->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty'));
  }
  
}
