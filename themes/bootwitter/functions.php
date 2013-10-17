<?php
function makeMenu(){
    $url = current_url();
    $menuitems = array('Index'=> base_url(),
        'Guestbook'=> base_url() . 'guestbook',
        'Blog'=> base_url() . 'blog',
        'Content'=> base_url() . 'content',
        'Developer'=> base_url() . 'developer',
        'Theme'=> base_url() . 'theme',
        'Login'=> base_url() . 'user/login',
        );
    
    $menu ='<ul class="nav nav-pills">';
    foreach($menuitems as $key => $value) {
            if ($value === $url) {
                $menu .= '<li  class="active"><a href="'.$value.'">'.$key.'</a></li>';
            } else {
                $menu .= '<li><a href="'.$value.'">'.$key.'</a></li>';
            }
        }
    $menu .= '</ul>';
    
    return $menu;
 
}
/**
 * Get messages stored in flash-session.
 */
function get_messages_from_session_modified() {
  $messages = CSiteshop::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='label label-$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}