<?php
//
// PHASE: BOOTSTRAP
//
define('SITESHOP_INSTALL_PATH', dirname(__FILE__));
define('SITESHOP_APPLICATION_PATH', SITESHOP_INSTALL_PATH . '/site');

require(SITESHOP_INSTALL_PATH.'/src/CSiteshop/bootstrap.php');

$ss = CSiteshop::Instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$ss->FrontControllerRoute();


//
// PHASE: THEME ENGINE RENDER
//
$ss->ThemeEngineRender();