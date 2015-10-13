<?php
/**
 * Site configuration, this file is changed by user per site.
 *
 */

/**
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);


/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
 */
$ss->config['debug']['siteshop'] = false;
$ss->config['debug']['session'] = false;
$ss->config['debug']['timer'] = false;
$ss->config['debug']['db-num-queries'] = false;
$ss->config['debug']['db-queries'] = false;
$ss->config['debug']['memory'] = false;
$ss->config['debug']['timestamp'] = false;


/**
 * Set database(s).
 */
$ss->config['database'][0]['dsn'] = 'sqlite:' . SITESHOP_APPLICATION_PATH . '/data/.ht.sqlite';


/**
 * What type of urls should be used?
 * 
 * default      = 0      => index.php/controller/method/arg1/arg2/arg3
 * clean        = 1      => controller/method/arg1/arg2/arg3
 * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
 */
$ss->config['url_type'] = 1;


/**
 * Set a base_url to use another than the default calculated
 */
$ss->config['base_url'] = null;


/**
 * How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
 */
$ss->config['hashing_algorithm'] = 'sha1salt';


/**
 * Allow or disallow creation of new user accounts.
 */
$ss->config['create_new_users'] = true;


/**
 * Define session name
 */
$ss->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);
$ss->config['session_key']  = 'siteshop';
    
/**
 * Define server timezone
 */
$ss->config['timezone'] = 'Europe/Stockholm';


/**
 * Define internal character encoding
 */
$ss->config['character_encoding'] = 'UTF-8';


/**
 * Define language
 *
 * langugage: the language of the webpage and locale, settings for i18n, 
 *            internationalization supporting multilanguage.
 * i18n: enable internationalization through gettext.
 */
$ss->config['language'] = 'en';
$ss->config['i18n'] = function_exists('gettext');


/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'developer/dump' would instantiate the controller with the key "developer", that is 
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $ss->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$ss->config['controllers'] = array(
    'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
    'blog'      => array('enabled' => true,'class' => 'CCBlog'),
    'content'   => array('enabled' => true,'class' => 'CCContent'),
    'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
    'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
    'index'     => array('enabled' => true,'class' => 'CCIndex'),
    'modules'   => array('enabled' => true,'class' => 'CCModules'),
    'my'        => array('enabled' => true,'class' => 'CCMycontroller'),   
    'page'      => array('enabled' => true,'class' => 'CCPage'),
    'theme'     => array('enabled' => true,'class' => 'CCTheme'),
    'user'      => array('enabled' => true,'class' => 'CCUser'),
    'startup'      => array('enabled' => true,'class' => 'CCStartup'),
);

/**
* Define a routing table for urls.
*
* Route custom urls to a defined controller/method/arguments
*/
$ss->config['routing'] = array(
    'home' => array('enabled' => true, 'url' => 'index/index'),
);
    
    

  /**
 * Define menus.
 *
 * Create hardcoded menus and map them to a theme region through $ss->config['theme'].
 */
$ss->config['menus'] = array(
    'navbar' => array(
        'home' => array('label' => t('Home'), 'url' => 'home'),
        'modules' => array('label' => t('Modules'), 'url' => 'modules'),
        'content' => array('label' => t('Content'), 'url' => 'content'),
    ),
    'my-navbar' => array(
        'me' => array('label' => t('About Me'), 'url' => 'my'),
        'blog' => array('label' => t('My blog'), 'url' => 'my/blog'),
        'guestbook' => array('label' => t('Guestbook'), 'url' => 'my/guestbook'),
    ),
    'login' => array(
        'id' => 'login-menu',
        'class' => '',
        'items' => array(
            'login' => array('label' => t('login'), 'url' => 'user/login', 'title' => t('Login')),
            'logout' => array('label' => t('logout'), 'url' => 'user/logout', 'title' => t('Logout')),
            'ucp' => array('label' => 'ucp', 'url' => 'user', 'title' => t('User control panel')),
            'acp' => array('label' => 'acp', 'url' => 'acp', 'title' => t('Admin control panel')),
        ),
    ),
    'navbar-ucp' => array(
    'id'    => 'navbar-ucp',
    'class' => 'nb-tab',
    'items' => array(
      'overview'  => array('label'=>t('Overview'),  'url'=>'user/overview'),
      'profile'   => array('label'=>t('Profile'),   'url'=>'user/profile'),
      'mail'      => array('label'=>t('Groups'),    'url'=>'user/groups'),
      'groups'    => array('label'=>t('Mail'),      'url'=>'user/email'),
      'password'  => array('label'=>t('Password'),  'url'=>'user/change-password'),
    ),
        ),
);

/**
 * Settings for the theme. The theme may have a parent theme.
 *
 * When a parent theme is used the parent's functions.php will be included before the current
 * theme's functions.php. The parent stylesheet can be included in the current stylesheet
 * by an @import clause. See application/themes/mytheme for an example of a child/parent theme.
 * Template files can reside in the parent or current theme, the CSiteshop::ThemeEngineRender()
 * looks for the template-file in the current theme first, then it looks in the parent theme.
 *
 * There are two useful theme helpers defined in themes/functions.php.
 *  theme_url($url): Prepends the current theme url to $url to make an absolute url.
 *  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url.
 *
 * path: Path to current theme, relativly SITESHOP_INSTALL_PATH, for example themes/grid or site/themes/mytheme.
 * parent: Path to parent theme, same structure as 'path'. Can be left out or set to null.
 * stylesheet: The stylesheet to include, always part of the current theme, use @import to include the parent stylesheet.
 * template_file: Set the default template file, defaults to default.tpl.php.
 * regions: Array with all regions that the theme supports.
 * data: Array with data that is made available to the template file as variables.
 *
 * The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made
 * available to the template files.
 */
$ss->config['theme'] = array(
    'path' => 'application/themes/mytheme',  //You can change code in 'style.css' in this path if you want to go from bb theme to grid theme
    //'path' => 'themes/bb',
    'parent' => 'themes/bb', // change this if you want to go to the grid theme
    //'path' => 'themes/grid', 
    //'parent' => 'themes/grid',
    'stylesheet' => 'style.css',
    'template_file' => 'index.tpl.php',
    'regions' => array('navbar', 'my-navbar', 'flash', 'featured-first', 'featured-middle', 'featured-last',
        'primary', 'custom', 'sidebar', 'triptych-first', 'triptych-middle', 'triptych-last',
        'footer-column-one', 'footer-column-two', 'footer-column-three', 'footer-column-four',
        'footer',
    ),
    'menu_to_region' => array('navbar' => 'navbar', 'my-navbar'=>'my-navbar'),
    'data' => array(
        'header' => 'Siteshop',
        'slogan' => t('A PHP-based MVC-inspired CMF'),
        'favicon' => 'icopig.ico',
        'logo' => 'pig.jpg',
        'logo_width' => 88,
        'logo_height' => 88,
        //'stylesheet' => 'style.php',
        'footer' => t('<p>Siteshop &copy; by Gunvor Nilsson (student at BTH)</p>'),
    ),
);
