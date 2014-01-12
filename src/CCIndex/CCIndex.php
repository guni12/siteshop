<?php

/**
 * Standard controller layout.
 * 
 * @package SiteshopCore
 */
class CCIndex extends CObject implements IController {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Implementing interface IController. All controllers must have an index action.
     */
    public function Index() {
        $modules = new CMModules();
        $controllers = $modules->AvailableControllers();
        $result = $this->testThings();
        $this->views->SetTitle('Index')
                ->AddInclude(__DIR__ . '/index.tpl.php', array('result' => $result), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('controllers' => $controllers), 'sidebar');
    }

    public function testThings() {

        $memory_limit = ini_get('memory_limit');
        $gettext = function_exists('gettext');
        $safemode = ini_get('safe_mode');
        $pdo = class_exists('PDO');
        $pdo_sqlite = in_array("sqlite", PDO::getAvailableDrivers());
        $magic_quotes = ini_get('magic_quotes_gpc') || ini_get('magic_quotes_runtime') || ini_get('magic_quotes_sybase');

        $problems = !$gettext || $safemode || !$pdo || !$pdo_sqlite || $magic_quotes;

//var_dump(PDO::getAvailableDrivers());

        $html1 = t('Your PHP version is: ');
        $html2 = t('. Max memory limit is ');
        $html3 = t('. Operating system is ');
        $html4 = t('You have gettext enabled and can use the multilanguage support.');
        $html5 = t('You have NOT gettext enabled and can NOT use the multilanguage support. To use these functions you must download and install the GNU gettext package from » http://www.gnu.org/software/gettext/gettext.html . But you can use the framwork still. ');
        $html6 = t('You have safe mode enabled on this server. Safe mode has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0. Turn this off.');
        $html7 = t('Safe mode is NOT enabled on this server. Good, as this feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.');
        $html8 = t('You have PDO enabled and you have SQLite as available driver for PDO.');
        $html9 = t('You have PDO enabled but is lacking support for the SQLite PDO driver. You must fix this.');
        $html10 = t('Database driver PDO is not available. You must fix this.');
        $html11 = t('You have magic quotes enabled on this server. Fix this.');
        $html12 = t('Magic quotes are disabled. This is good.');
        $html13 = t('Your environment does not fully match the need of Siteshop. Go back and correct issues here if you get problems.');
        $html14 = t('Nice!');
        $html15 = t(' It seems like this is an environment where Siteshop may enjoy herself.');


        $result = "";
        $result .= "<p class='info'>" . $html1 . PHP_VERSION;
        $result .= $html2 . $memory_limit;
        $result .= $html3 . PHP_OS;
        $result .= ".</p>";
        if ($gettext):
            $result .= "<p class='success'>" . $html4 . "</p>";
        else:
            $result .= "<p class='info'>" . $html5 . "</p>";
        endif;
        if ($safemode):
            $result .= "<p class='error'>" . $html6 . "</p>";
        else:
            $result .= "<p class='success'>" . $html7 . "</p>";
        endif;
        if ($pdo && $pdo_sqlite):
            $result .= "<p class='success'>" . $html8 . "</p>";
        elseif ($pdo):
            $result .= "<p class='error'>" . $html9 . "</p>";
        else:
            $result .= "<p class='error'>" . $html10 . "</p>";
        endif;

        if ($magic_quotes):
            $result .= "<p class='error'>" . $html11 . "</p>";
        else:
            $result .= "<p class='success'>" . $html12 . "</p>";
        endif;
        if ($problems):
            $result .= "<p class='error'>" . $html13 . "</p>";
        else:
            $result .= "<p class='success'><b>" . $html14 . "</b>" . $html15 . "</p>";
        endif;

        return $result;
    }

}