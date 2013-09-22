<?php
//
// PHASE: BOOTSTRAP
//
define('SITESHOP_INSTALL_PATH', dirname(__FILE__));	// C:\wamp\www\siteshop
define('SITESHOP_APPLICATION_PATH', SITESHOP_INSTALL_PATH . '/application');

require(SITESHOP_INSTALL_PATH.'/src/bootstrap.php');

$ss = CSiteshop::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$ss->FrontControllerRoute();


//
// PHASE: THEME ENGINE RENDER
//
$ss->ThemeEngineRender();