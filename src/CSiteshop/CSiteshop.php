<?php

/**
 * Main class for Siteshop, holds everything.
 *
 * @package SiteshopCore
 */
class CSiteshop implements ISingleton /* , IModule */ {

    /**
     * Members
     */
    private static $instance = null;
    public $config = array();
    public $request;
    public $data;
    public $db;
    public $views;
    public $session;
    public $user;
    public $log;

    /**
     * Constructor
     */
    protected function __construct() {
        
    }

    /**
     * Init the class, can not do init in constructor since the class itself is used during Init-process.
     */
    public function Init() {
        
        $this->log = new CLog();
        $this->log->Timestamp(__CLASS__, __METHOD__, 'Init Siteshop');

        $ss = &$this;
        //require(SITESHOP_APPLICATION_PATH . '/config.php');
        // Checking for default value for backward compatibility, should be removed later on.
    if(!defined('SITESHOP_CONFIG_PATH')) {
      define('SITESHOP_CONFIG_PATH', SITESHOP_APPLICATION_PATH.'/config.php');
    }
    
    // Setup data path if not defined (backward compatibility, should be removed later on.
    if(!defined('SITESHOP_DATA_PATH')) {
      define('SITESHOP_DATA_PATH', SITESHOP_SITE_PATH.'/data');
    } 

    if(is_readable(SITESHOP_CONFIG_PATH)) {
      require(SITESHOP_CONFIG_PATH);
    } else {
      throw new Exception("Missing config-file: " . SITESHOP_CONFIG_PATH);
    }

    if(!is_dir(SITESHOP_DATA_PATH)) {
      throw new Exception("Data-directory does not exists: " . SITESHOP_DATA_PATH);
    }

        // Setup i18n, internationalization and multi-language support
        $this->SetLocale();

        // Start a named session
        session_name($this->config['session_name']);
        session_start();
        $this->session = new CSession($this->config['session_key']);
        $this->session->PopulateFromSession();

        // Set default date/time-zone
        date_default_timezone_set('UTC');

        // Create a database object.
        if (isset($this->config['database'][0]['dsn'])) {

            $this->db = new CDatabase($this->config['database'][0]['dsn']);
           
        }

        // Create a container for all views and theme data
        $this->views = new CViewContainer();

        // Create a object for the user
        $this->user = new CMUser($this);

        return $this;
    }

    /**
     * Singleton pattern. Get the instance of the latest created object or create a new one. 
     * @return CSiteshop The instance of this class.
     */
    public static function Instance() {
        if (self::$instance == null) {
            self::$instance = new CSiteshop();
        }
        return self::$instance;
    }

    /**
     * Set up i18n.
     * 
     * @return CSiteshop The instance of this class.
     */
    public function SetLocale() {
        // Setup i18n, internationalization and multi-language support
        @putenv('LC_ALL=' . $this->config['language']); // Will not work in safe_mode, ignore warning.
        setlocale(LC_ALL, $this->config['language']);
        if ($this->config['i18n']) {
            bindtextdomain('siteshop', SITESHOP_INSTALL_PATH . '/language');
            bind_textdomain_codeset('siteshop', 'UTF-8');
            textdomain('siteshop');
        }
    }

    /**
     * Frontcontroller, check url and route to controllers.
     */
    public function FrontControllerRoute() {
        $this->log->Timestamp(__CLASS__, __METHOD__, 'Frontcontroller phase starts');

        // Take current url and divide it in controller, method and parameters
        $this->request = new CRequest(isset($this->config['url_type']) ? $this->config['url_type'] : null);
        $this->request->Init($this->config['base_url'], $this->config['routing']);
        $controller = $this->request->controller;
        $method = $this->request->method;
        $arguments = $this->request->arguments;

        // Is the controller enabled in config.php?
        $controllerExists = isset($this->config['controllers'][$controller]);
        $controllerEnabled = $controllerExists ? $this->config['controllers'][$controller]['enabled'] : false;
        $className = $controllerExists ? $this->config['controllers'][$controller]['class'] : false;
        $classExists = $controllerExists ? class_exists($className) : false;

        if ($controllerExists) {
            $controllerEnabled = ($this->config['controllers'][$controller]['enabled'] == true);
            $className = $this->config['controllers'][$controller]['class'];
            $classExists = class_exists($className);
        }

        // Check if controller has a callable method in the controller class, if then call it
        if ($controllerExists && $controllerEnabled && $classExists) {
            $rc = new ReflectionClass($className);
            if ($rc->implementsInterface('IController')) {
                $formattedMethod = str_replace(array('_', '-'), '', $method);
                if ($rc->hasMethod($formattedMethod)) {
                    $controllerObj = $rc->newInstance();
                    $methodObj = $rc->getMethod($formattedMethod);
                    if ($methodObj->isPublic()) {
                        $this->log->Timestamp(__CLASS__, __METHOD__, 'To Controller');
                        $methodObj->invokeArgs($controllerObj, $arguments);
                    } else {
                        $this->ShowErrorPage(404, 'Controller method not public.');
                    }
                } else if ($rc->hasMethod('CatchAll')) {
                    $controllerObj = $rc->newInstance();
                    $methodObj = $rc->getMethod('CatchAll');
                    if ($methodObj->isPublic()) {
                        $this->log->Timestamp(__CLASS__, __METHOD__, 'To Controller CatchAll');
                        $methodObj->invokeArgs($controllerObj, array_merge(array($method), $arguments));
                    } else {
                        $this->ShowErrorPage(404, 'Controller default method not public.');
                    }
                } else {
                    $this->ShowErrorPage(404, 'Controller does not contain method nor implements a default method.');
                }
            } else {
                $this->ShowErrorPage(404, 'Controller does not implement interface IController.');
            }
        }
        // Use content associated url to load page
        else if ($this->config['pageloader'] && CPageLoader::UrlHasContent($this->request->request)) {
            $this->log->Timestamp(__CLASS__, __METHOD__, 'To Controller PageLoader');
            CPageLoader::Factory($this->config['pageloader_class'])->DisplayContentByUrl($this->request->request);
        } else {
            $this->ShowErrorPage(404, t('Page is not found.'));
        }
    }

    /**
     * Display a custom error page.
     *
     * @param $code integer the code, for example 403 or 404.
     * @param $message string a message to be displayed on the page.
     */
    public function ShowErrorPage($code, $message = null) {
        $errors = array(
            '403' => array('header' => 'HTTP/1.0 403 Restricted Content', 'title' => t('403, restricted content')),
            '404' => array('header' => 'HTTP/1.0 404 Not Found', 'title' => t('404, page not found')),
        );
        if (!array_key_exists($code, $errors)) {
            throw new Exception(t('Header code is not valid.'));
        }

        $this->views->SetTitle($errors[$code]['title'])
                ->AddIncludeToRegion('primary', (__DIR__ . '/' . "{$code}.tpl.php"), array('message' => $message))
                ->AddIncludeToRegion('sidebar', (__DIR__ . '/' . "{$code}_sidebar.tpl.php"), array('message' => $message));

        $this->log->Timestamp(__CLASS__, __METHOD__);
        header($errors[$code]['header']);
        $this->ThemeEngineRender();
        exit();
    }

    /**
     * ThemeEngineRender, renders the reply of the request to HTML or whatever.
     */
    public function ThemeEngineRender() {
        $this->log->Timestamp(__CLASS__, __METHOD__, 'Theme rendering phase starts');

        // Save to session before output anything
        $this->session->StoreInSession();

        // Is theme enabled?
        if (!isset($this->config['theme'])) { {
                throw new Exception(t('Theme not enabled.'));
            }
        }

        // Get the paths and settings for the theme, look in the application dir first
        $themePath = SITESHOP_INSTALL_PATH . '/' . $this->config['theme']['path'];
        $themeUrl = $this->request->base_url . $this->config['theme']['path'];

        // Is there a parent theme?
        $parentPath = null;
        $parentUrl = null;
        if (isset($this->config['theme']['parent'])) {
            $parentPath = SITESHOP_INSTALL_PATH . '/' . $this->config['theme']['parent'];
            $parentUrl = $this->request->base_url . $this->config['theme']['parent'];
        }

        // Add stylesheet name to the $ss->data array
        $this->data['stylesheet'] = $this->config['theme']['stylesheet'];

        // Make the theme urls available as part of $ss
        $this->themeUrl = $themeUrl;
        $this->themeParentUrl = $parentUrl;

        // Map menu to region if defined
        if (is_array($this->config['theme']['menu_to_region'])) {
            foreach ($this->config['theme']['menu_to_region'] as $key => $val) {
                $this->views->AddString($this->DrawMenu($key), null, $val);
            }
        }

        // Include the global functions.php and the functions.php that are part of the theme
        $ss = &$this;
        // First the default Siteshop themes/functions.php
        include(SITESHOP_INSTALL_PATH . '/themes/functions.php');
        // Then the functions.php from the parent theme
        if ($parentPath) {
            if (is_file("{$parentPath}/functions.php")) {
                include "{$parentPath}/functions.php";
            }
        }
        // And last the current theme functions.php
        if (is_file("{$themePath}/functions.php")) {
            include "{$themePath}/functions.php";
        }

        // Extract $ss->data to own variables and handover to the template file
        extract($this->data);  // OBSOLETE, use $this->views->GetData() to set variables
        extract($this->views->GetData());
        if (isset($this->config['theme']['data'])) {
            extract($this->config['theme']['data']);
        }

        // Execute the template file
        $this->log->Timestamp(__CLASS__, __METHOD__, 'Including template file');
        $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
        if (is_file("{$themePath}/{$templateFile}")) {
            include("{$themePath}/{$templateFile}");
        } else if (is_file("{$parentPath}/{$templateFile}")) {
            include("{$parentPath}/{$templateFile}");
        } else {
            throw new Exception('No such template file.');
        }
    }

    /**
     * Redirect to another url and store the session
     * 
     * @param $url string the relative url or the controller
     * @param $method string the method to use, $url is then the controller or empty for current controller
     * @param $arguments string the extra arguments to send to the method
     */
    public function RedirectTo($urlOrController = null, $method = null, $arguments = null) {
        if (isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
            $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
        }
        if (isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
            $this->session->SetFlash('database_queries', $this->db->GetQueries());
        }
        if (isset($this->config['debug']['memory']) && $this->config['debug']['memory']) {
            $this->session->SetFlash('memory', memory_get_peak_usage(true));
        }
        if (isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
            $this->session->SetFlash('timer', $ss->timer);
        }
        $this->session->StoreInSession();
        header('Location: ' . $this->request->CreateUrl($urlOrController, $method, $arguments));
        exit;
    }

    /**
     * Redirect to the current url. Uses RedirectTo().
     *
     */
    public function RedirectToCurrent() {
        $this->RedirectTo($this->request->controller, $this->request->method, $this->request->arguments);
    }

    /**
     * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
     *
     * @param string method name the method, default is index method.
     * @param $arguments string the extra arguments to send to the method
     */
    public function RedirectToController($method = null, $arguments = null) {
        $this->RedirectTo($this->request->controller, $method, $arguments);
    }

    /**
     * Redirect to a controller and method. Uses RedirectTo().
     *
     * @param string controller name the controller or null for current controller.
     * @param string method name the method, default is current method.
     * @param $arguments string the extra arguments to send to the method
     */
    public function RedirectToControllerMethod($controller = null, $method = null, $arguments = null) {
        $controller = is_null($controller) ? $this->request->controller : null;
        $method = is_null($method) ? $this->request->method : null;
        $this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
    }

    /**
     * Redirect to current controller and method. Uses RedirectTo().
     *
     * @param $arguments string the extra arguments to send to the method
     */
    public function RedirectToCurrentControllerMethod($arguments = null) {
        $this->RedirectTo($this->request->CreateUrl($this->request->controller, $this->request->method, $arguments));
    }

    /**
     * Save a message in the session. Uses $this->session->AddMessage()
     *
     * @param $type string the type of message, for example: notice, info, success, warning, error.
     * @param $message string the message.
     * @param $alternative string the message if the $type is set to false, defaults to null.
     */
    public function AddMessage($type, $message, $alternative = null) {
        if ($type === false) {
            $type = 'error';
            $message = $alternative;
        } else if ($type === true) {
            $type = 'success';
        }
        $this->session->AddMessage($type, $message);
    }

    /**
     * Create an url. Uses $this->request->CreateUrl()
     *
     * @param $urlOrController string the relative url or the controller
     * @param $method string the method to use, $url is then the controller or empty for current
     * @param $arguments string the extra arguments to send to the method
     */
    public function CreateUrl($urlOrController = null, $method = null, $arguments = null) {
        return $this->request->CreateUrl($urlOrController, $method, $arguments);
    }

    /**
     * Create a clean url, wrapper and shorter method for $this->request->CreateCleanUrl()
     *
     * @param $urlOrController string the relative url or the controller
     * @param $method string the method to use, $url is then the controller or empty for current
     * @param $arguments string the extra arguments to send to the method
     * @return string as the url.
     */
    public function CreateCleanUrl($urlOrController = null, $method = null, $arguments = null) {
        return $this->request->CreateCleanUrl($urlOrController, $method, $arguments);
    }

    /**
     * Create an url to current controller, wrapper for CreateUrl().
     *
     * @param $method string the method to use, $url is then the controller or empty for current
     * @param $arguments string the extra arguments to send to the method
     * @return string as the url.
     */
    public function CreateUrlToController($method = null, $arguments = null) {
        return $this->request->CreateUrl($this->request->controller, $method, $arguments);
    }

    /**
     * Create an url to current controller and current method, wrapper for CreateUrl().
     *
     * @param $arguments string the extra arguments to send to the method
     * @return string as the url.
     */
    public function CreateUrlToControllerMethod($arguments = null) {
        return $this->request->CreateUrl($this->request->controller, $this->request->method, $arguments);
    }

    /**
     * Create an url to current controller, method with existing arguments, wrapper for CreateUrl().
     *
     * @return string as the url.
     */
    public function CreateUrlToControllerMethodArguments() {
        return $this->request->CreateUrl($this->request->controller, $this->request->method, $this->request->arguments);
    }

    /**
     * Draw HTML for a menu defined in $ss->config['menus'].
     *
     * @param $menu string then key to the menu in the config-array.
     * @returns string with the HTML representing the menu.
     */
    public function DrawMenu($menu) {
        $items = null;
        if (isset($this->config['menus'][$menu])) {
            foreach ($this->config['menus'][$menu] as $val) {
                $selected = null;
                if ($val['url'] == $this->request->request || $val['url'] == $this->request->routed_from) {
                    $selected = " class='selected'";
                }
                $items .= "<li><a {$selected} href='" . $this->CreateUrl($val['url']) . "'>{$val['label']}</a></li>\n";
            }
        } else {
            throw new Exception('No such menu.');
        }
        return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
    }

    /**
     * Create a menu from an array or use a predefined array from $ss->config['menus'].
     * 'items' => array(
     *    array('label'=>'visible label', 'url'=>'url/to', 'title'=> 'display when hovering'),
     * );
     *
     * @param  array $options array with details from which the menu is constructed.
     * @return string with the HTML representing the menu.
     *
     */
    public function CreateMenu($options) {
        $default = array(
            'id' => null,
            'class' => null,
            'items' => array(),
        );

        // If not an array, check if the menu is predefined in config.
        if (!is_array($options) && isset($this->config['menus'][$options])) {
            $options = $this->config['menus'][$options];
        }
        $options = array_merge($default, $options);

        // Walkthrough all items
        $items = null;
        foreach ($options['items'] as $val) {
            $selected = null;

            // Has submenu?
            $submenu = null;
            if (isset($val['items'])) {
                $subitems = null;

                foreach ($val['items'] as $subitem) {
                    $subSelected = null;

                    // Current item selected?
                    if (in_array($subitem['url'], array($this->request->request, $this->request->routed_from)) ||
                            substr_compare($subitem['url'], $this->request->controller, 0) == 0 ||
                            strncmp($this->request->routed_from, $subitem['url'], strlen($subitem['url'])) == 0) {
                        $subSelected = " class='selected'";
                        $selected = " class='selected'";
                    }

                    $title = isset($subitem['title']) ? " title='{$subitem['title']}'" : null;
                    $link = "<a{$title} href='" . $this->CreateUrl($subitem['url']) . "'>{$subitem['label']}</a>";
                    $subitems .= "<li{$subSelected}>{$link}</li>\n";
                }

                $submenu = "<ul>\n{$subitems}</ul>\n";
            }

            // Current item selected?
            if (!empty($val['url'])) {
                if ($selected ||
                        in_array($val['url'], array($this->request->request, $this->request->routed_from)) ||
                        substr_compare($val['url'], $this->request->controller, 0) == 0 ||
                        strncmp($this->request->routed_from, $val['url'], strlen($val['url'])) == 0) {
                    $selected = " class='selected'";
                }
            }

            $title = isset($val['title']) ? " title='{$val['title']}'" : null;
            $link = "<a{$title} href='" . $this->CreateUrl($val['url']) . "'>{$val['label']}</a>";
            $items .= "<li{$selected}>{$link}{$submenu}</li>\n";
        }

        $id = isset($options['id']) ? " id='{$options['id']}'" : null;
        $class = isset($options['class']) ? " class='{$options['class']}'" : null;
        return "<ul{$id}{$class}>\n{$items}</ul>\n";
    }

}