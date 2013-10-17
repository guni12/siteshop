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
$ss->config['debug']['timer'] = true;
$ss->config['debug']['db-num-queries'] = true;
$ss->config['debug']['db-queries'] = true;


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
 */
$ss->config['language'] = 'en';


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
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
  'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
  'theme'     => array('enabled' => true, 'class' => 'CCTheme'),
  'content'   => array('enabled' => true,'class' => 'CCContent'),
  'blog'      => array('enabled' => true,'class' => 'CCBlog'),
  'page'      => array('enabled' => true,'class' => 'CCPage'),
  'user'      => array('enabled' => true,'class' => 'CCUser'),
  'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
);

/**
* Settings for the theme.

$ss->config['theme'] = array(
'name'        => 'core',        // The name of the theme in the theme directory
'stylesheet'  => 'style.php',   // Main stylesheet to include in template files
'template_file'   => 'index.tpl.php',   // Default template file, else use default.tpl.php
// A list of valid theme regions
'regions' => array('flash','featured-first','featured-middle','featured-last',
'primary','sidebar','triptych-first','triptych-middle','triptych-last',
'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
'footer',
),
// Add static entries for use in the template file.
'data' => array(
'header' => 'Siteshop',
'slogan' => 'A PHP-based MVC-inspired CMF',
'favicon' => 'icopig.ico',
'logo' => 'pig.jpg',
'logo_width'  => 98,
'logo_height' => 98,
'footer' => '<p>Lydia &copy; by Mikael Roos (mos@dbwebb.se)</p>',
),
);
*/
/**
* Settings for the theme.

$ss->config['theme'] = array(
'name'        => 'grid',        // The name of the theme in the theme directory
'stylesheet'  => 'style.php',   // Main stylesheet to include in template files
'template_file'   => 'index.tpl.php',   // Default template file, else use default.tpl.php
// A list of valid theme regions
'regions' => array('flash','featured-first','featured-middle','featured-last',
'primary','sidebar','triptych-first','triptych-middle','triptych-last',
'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
'footer',
),
// Add static entries for use in the template file.
'data' => array(
'header' => 'Siteshop',
'slogan' => 'A PHP-based MVC-inspired CMF',
'favicon' => 'icopig.ico',
'logo' => 'pig.jpg',
'logo_width'  => 98,
'logo_height' => 98,
'footer' => '<p>Lydia &copy; by Mikael Roos (mos@dbwebb.se)</p>',
),
);
*/
/**
* Settings for the theme.
*/
$ss->config['theme'] = array(
'name'        => 'bootwitter',        // The name of the theme in the theme directory
'stylesheet'  => 'bootstrap/css/',              // Main stylesheet to include in template files
'javascript'    => 'bootstrap/js/',
'template_file'   => 'index.tpl.php',   // Default template file, else use default.tpl.php
// A list of valid theme regions
'regions' => array('flash','featured-first','featured-middle','featured-last',
'primary','sidebar','triptych-first','triptych-middle','triptych-last',
'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
'footer',
),
// Add static entries for use in the template file.
'data' => array(
'header' => 'Siteshop',
'slogan' => 'A PHP-based MVC-inspired CMF',
'favicon' => 'icopig.ico',
'logo' => 'pig.jpg',
'logo_width'  => 98,
'logo_height' => 98,
'footer' => '<p>Lydia &copy; by Mikael Roos (mos@dbwebb.se)</p>',
),
);
 