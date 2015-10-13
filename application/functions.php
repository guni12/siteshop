<?php
/**
 * Here can the site owner include own code and functions. This file is included right 
 * after the creation of $ss and its function ssApplicationInit() is called. You can use this to 
 * overwrite existing functions or add new ones. This is a good place to use when 
 * integrating your Siteshop website with another website and want to use a session from the 
 * existing one.
 *
 * Prepend your functions with "ssApplication" or something other to get your own namespace.
 */

/**
 * This function is called by index.php, if defined, at the start of each page load, right 
 * after the creation of $ss.
 */
/*
function ssApplicationInit() {
  global $ss;
  
  if(isset($ss->config['extra']['phpbb_root_path'])) {
    ssSiteIntegratePHPBBSession($ss->config['extra']['phpbb_root_path']);
  }
}
*/


/**
 * Sample function to integrate with a phpbb installation and lend some information 
 * on the authorized user.
 *
 * @param string $path is the install path of PHPBB.
 */
/*
function ssSiteIntegratePHPBBSession($path) {
  global $ss, $phpbb_root_path, $phpEx, $user, $db, $config, $cache, $template, $auth;
  
  define('IN_PHPBB', true);
  $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $path;
  $phpEx = 'php'; //substr(strrchr(__FILE__, '.'), 1);
  include($phpbb_root_path . 'common.' . $phpEx);
  
  // Start session management
  $user->session_begin();
  $auth->acl($user->data);
  $user->setup();

  // Populate this user with data from phpbb user.
  if($user->data['user_id'] != ANONYMOUS && !$ss->user->IsAuthenticated()) {
    $ss->user['isAuthenticated'] = true;
    $ss->user['hasRoleAnonomous'] = false;
    $ss->user['hasRoleVisitor'] = true;
    $ss->user['id'] = 1;
    $ss->user['acronym'] = $user->data['username_clean'];      
    $ss->user['email'] = $user->data['user_email'];
    $ss->config['menus']['login']['logout']['url'] .= '&amp;sid=' . $user->data['session_id'];
  }
}
*/


