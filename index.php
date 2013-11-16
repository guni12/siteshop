<?php
/**
 * All requests routed through here. This is an overview of what actaully happens during
 * a request.
 *
 * @package SiteshopCore
 */

// ---------------------------------------------------------------------------------------
//
// PHASE: BOOTSTRAP
//
define('SITESHOP_INSTALL_PATH', dirname(__FILE__));
define('SITESHOP_APPLICATION_PATH', SITESHOP_INSTALL_PATH . '/application');

require(SITESHOP_INSTALL_PATH.'/src/bootstrap.php');

$ss = CSiteshop::Instance()->Init();


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
CSiteshop::Instance()->FrontControllerRoute();


// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
CSiteshop::Instance()->ThemeEngineRender();
