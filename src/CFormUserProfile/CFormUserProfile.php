<?php
/**
 * A form for editing the user profile.
 * 
 * @package SiteshopCore
 */
class CFormUserProfile extends CForm {

  /**
   * Constructor
   */
  public function __construct($object, $user) {
    parent::__construct();
    if(CSiteshop::Instance()->config['theme']['name'] == 'bootwitter'){
        $this->AddElement(new CFormElementTextTwit('acronym', array('readonly'=>true, 'value'=>$user['acronym'], 'placeholder'=>$user['acronym'])))
         ->AddElement(new CFormElementPasswordTwit('password', array('placeholder'=>'Password')))
         ->AddElement(new CFormElementPasswordTwit('password1', array ('placeholder'=>'Password', 'label'=>'Password again:')))
         ->AddElement(new CFormElementButton('change_password', array('callback'=>array($object, 'DoChangePassword')) ))
         ->AddElement(new CFormElementTextTwit('name', array('value'=>$user['name'], 'placeholder'=>$user['name'], 'required'=>true)))
         ->AddElement(new CFormElementTextTwit('email', array('value'=>$user['email'], 'placeholder'=>$user['email'], 'required'=>true)))
         ->AddElement(new CFormElementButton('save', array('callback'=>array($object, 'DoProfileSave'))));
         
    $this->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty'));

    }else{
        $this->AddElement(new CFormElementText('acronym', array( 'readonly'=>true, 'value'=>$user['acronym'])))
         ->AddElement(new CFormElementPassword('password'))
         ->AddElement(new CFormElementPassword('password1', array ('label'=>'Password again:')))
         ->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'DoChangePassword'))))
         ->AddElement(new CFormElementText('name', array('value'=>$user['name'], 'required'=>true)))
         ->AddElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
         ->AddElement(new CFormElementSubmit('save', array('callback'=>array($object, 'DoProfileSave'))));
         
    $this->SetValidation('name', array('not_empty'))
         ->SetValidation('email', array('not_empty'));
    }
  }
  
}