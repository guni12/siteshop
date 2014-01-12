<?php

/**
 * All requests routed through here. This is an overview of what actaully happens during
 * a request.
 *
 * @package SiteshopCore
 */
// ---------------------------------------------------------------------------------------
//
// PHASE: INIT
//
define('SITESHOP_INSTALL_PATH', dirname(__FILE__));
define('SITESHOP_APPLICATION_PATH', SITESHOP_INSTALL_PATH . '/application');

define('SITESHOP_CONFIG_PATH', SITESHOP_APPLICATION_PATH . '/config.php');
define('SITESHOP_DATA_PATH', SITESHOP_APPLICATION_PATH . '/data');

if (defined('SITESHOP_INIT_ONLY')) {
    return;
}
// ---------------------------------------------------------------------------------------
//
  // PHASE BOOTSTRAP
//
if (!defined('SITESHOP_PASS_BOOTSTRAP')) {

    require(SITESHOP_INSTALL_PATH . '/src/bootstrap.php');
    $ss = CSiteshop::Instance()->Init();

    // Allow siteowner to add own code or overwrite existing. Call init function if defined.
    require(SITESHOP_APPLICATION_PATH . '/functions.php');
    if (function_exists('ssApplicationInit')) {
        ssApplicationInit();
    }
}

// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
//CSiteshop::Instance()->FrontControllerRoute();
if (!defined('LYDIA_PASS_FRONTCONTROLLER')) {
    $ss->FrontControllerRoute();
}

// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
//CSiteshop::Instance()->ThemeEngineRender();
if (!defined('LYDIA_PASS_THEMEENGINE')) {
    $ss->ThemeEngineRender();
}