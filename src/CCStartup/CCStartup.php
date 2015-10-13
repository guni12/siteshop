<?php

/**
 * Standard controller layout.
 * 
 * @package SiteshopCore
 */
class CCStartup extends CObject implements IController {

    public $goodSofar = false;
    public $goodSidebar = false;
    public $db_works = false;

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
      $result = $this->testThings();
      $controllers = 'controllers';
      //$sealInstallation = $this->sealInstallation();

      $dsn = isset($this->config['database'][0]['dsn']) ? $this->config['database'][0]['dsn'] : t('Settings for default database is missing in application/config.php.');

    $this->views->SetTitle(t('Install Siteshop'))
                ->AddInclude(__DIR__ . '/index.tpl.php', array('result' => $result), 'primary')
                ->AddInclude(__DIR__ . '/sidebar.tpl.php', array('dsn' => $dsn, 'goodSofar' => $this->goodSofar, 'goodSidebar' => $this->goodSidebar), 'sidebar');
  }
  
  
  
      public function testThings() {

        $php = PHP_VERSION;
        $html23 = t("This is an outdated version of PHP and might mean that Siteshop won't work. Please download a new verion.");
        $html24 = t('You have a new enough version of PHP.');
        if($php < '5.3.0'){
            $html22 = "<br />{$html23}<br />";
        } else {
            $html22 = "<br />{$html24}<br />";
        }
        $memory_limit = ini_get('memory_limit');
        $gettext = function_exists('gettext');
        $safemode = ini_get('safe_mode');
        $pdo = class_exists('PDO'); 
        $pdo_sqlite = in_array("sqlite", PDO::getAvailableDrivers());
        $magic_quotes = ini_get('magic_quotes_gpc') || ini_get('magic_quotes_runtime') || ini_get('magic_quotes_sybase');

        $problems = !$gettext || $safemode || !$pdo || !$pdo_sqlite || $magic_quotes;

//var_dump(PDO::getAvailableDrivers());

        $html1 = t('Your PHP version is: ');
        $html2 = t('Max memory limit is ');
        $html3 = t('. Operating system is ');
        $html4 = t('You have gettext enabled and can use the multilanguage support.');
        $html5 = t('You have NOT gettext enabled and can NOT use the multilanguage support. To use these functions you must download and install the GNU gettext package from » http://www.gnu.org/software/gettext/gettext.html . But you can use the framework still. ');
        $html6 = t('You have safe mode enabled on this server. Turn this off.');
        $html7 = t('Safe mode is NOT enabled on this server. Good!');
        $html8 = t('You have PDO enabled and you have SQLite as available driver for PDO.');
        $html9 = t('You have PDO enabled but is lacking support for the SQLite PDO driver. You must fix this.');
        $html10 = t('Database driver PDO is not available. You must fix this.');
        $html11 = t('You have magic quotes enabled on this server. Fix this.');
        $html12 = t('Magic quotes are disabled. This is good.');
        $html13 = t('Your environment does not fully match the need of Siteshop. Go back and correct issues here if you get problems.');
        $html14 = t('Nice!');
        $html15 = t(' It seems like this is an environment where Siteshop may enjoy herself.');
        $html16 = t("It's important to have a fairly new version of PHP (min 5.3) for some of the features used in Siteshop. We are testing this here.");
        $html17 = t('We have included a Swedish translation in Siteshop, but to get it working you need to have gettext enabled. We have used Poedit to create the .po and .mo-files.');
        $html18 = t('Safe mode has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0. ');
        $html19 = t('This framework runs with PDO but with PHP version 5.1.0 it should be included.');
        $html20 = t('Magic quotes has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.');
        $html21 = t('And here is your result:');

        $result = "";
        $result .= "<p class ='smaller-text'>" . $html16 . "</p>";
        $result .= "<p class='info'>" . $html1 . PHP_VERSION;
        $result .= $html22;
        $result .= $html2 . $memory_limit;
        $result .= $html3 . PHP_OS;       
        $result .= ".</p><br /><br />"; 
        $result .= "<p class ='smaller-text'>" . $html19 . "</p>";
        if ($pdo && $pdo_sqlite):
            $result .= "<p class='success'>" . $html8 . "</p><br />";
        elseif ($pdo):
            $result .= "<p class='error'>" . $html9 . "</p><br />";
        else:
            $result .= "<p class='error'>" . $html10 . "</p><br />";
        endif;
        $result .= "<p class ='smaller-text'>" . $html18 . "</p>";
        if ($safemode):
            $result .= "<p class='error'>" . $html6 . "</p><br />";
        else:
            $result .= "<p class='success'>" . $html7 . "</p><br />";
        endif;        
        $result .= "<p class ='smaller-text'>" . $html20 . "</p>";
        if ($magic_quotes):
            $result .= "<p class='error'>" . $html11 . "</p><br />";
        else:
            $result .= "<p class='success'>" . $html12 . "</p><br />";
        endif;
        $result .= "<p class ='smaller-text'>" . $html17 . "</p>";
        if ($gettext):
            $result .= "<p class='success'>" . $html4 . "</p><br />";
        else:
            $result .= "<p class='info'>" . $html5 . "</p><br />";
        endif;
        $result .= "<p class ='smaller-text'>" . $html21 . "</p>";
        if ($problems):
            $result .= "<p class='error'>" . $html13 . "</p>";
        else:
            $result .= "<p class='success'><b>" . $html14 . "</b>" . $html15 . "</p>";
        $this->goodSofar = true;
        endif;

        return $result;
    }
    
    public function sealInstallation() {
        
    $file = fopen('application/data/installed.txt', 'w');
    $data = "Installation OK";
    fwrite($file,$data);
    fclose($file);
    return null;
  }
}
