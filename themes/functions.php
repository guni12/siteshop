<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 
/**
* Get list of tools.
*/
function get_tools() {
  global $ss;
  return <<<EOD
<p>Tools:
<a href="http://validator.w3.org/check/referer">html5</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a>
<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a>
<a href="http://validator.w3.org/checklink?uri={CSiteshop::Instance()->request->current_url}">links</a>
<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={CSiteshop::Instance()->request->current_url}">i18n</a>
<!-- <a href="link?">http-header</a> -->
<a href="http://csslint.net/">css-lint</a>
<a href="http://jslint.com/">js-lint</a>
<a href="http://jsperf.com/">js-perf</a>
<a href="http://www.workwithcolor.com/hsl-color-schemer-01.htm">colors</a>
<a href="http://dbwebb.se/style">style</a>
</p>

<p>Docs:
<a href="http://www.w3.org/2009/cheatsheet">cheatsheet</a>
<a href="http://dev.w3.org/html5/spec/spec.html">html5</a>
<a href="http://www.w3.org/TR/CSS2">css2</a>
<a href="http://www.w3.org/Style/CSS/current-work#CSS3">css3</a>
<a href="http://php.net/manual/en/index.php">php</a>
<a href="http://www.sqlite.org/lang.html">sqlite</a>
<a href="http://www.blueprintcss.org/">blueprint</a>
</p>
EOD;
}


/**
 * Print debuginformation from the framework.
 */
function get_debug() {
 
  $ss = CSiteshop::Instance(); 
  $ss->log->Timestamp('theme/functions.php', __FUNCTION__, 'End of template file'); 
  // Only if debug is wanted.
  if(empty($ss->config['debug'])) {
    return;
  }
  
  // Get the debug output
  $html = null;
  if(isset($ss->config['debug']['db-num-queries']) && $ss->config['debug']['db-num-queries'] && isset($ss->db)) {
    $flash = $ss->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $ss->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($ss->config['debug']['db-queries']) && $ss->config['debug']['db-queries'] && isset($ss->db)) {
    $flash = $ss->session->GetFlash('database_queries');
    $queries = $ss->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }    
  if(isset($ss->config['debug']['timer']) && $ss->config['debug']['timer']) {
    $now = microtime(true);
    //echo 'now: ' . $now . '<br />';
    $flash = $ss->session->GetFlash('timer');
    //echo 'flash: ' . $flash . '<br />';
    if($flash){
    $redirect = $flash ? round($flash['redirect'] - $flash['first'], 3) . ' secs + x + ' : null;
    echo 'redirect: ' . $redirect . '<br />';
    $total = $flash ? round($now - $flash['first'], 3) . ' secs. Per page: ' : null;
    echo 'total: ' . $total . '<br />';
    $html .= "<p>Page was loaded in {$total}{$redirect}" . round($now - $ss->timer['first'], 3) . " secs.</p>";
  }}
  if(isset($ss->config['debug']['memory']) && $ss->config['debug']['memory']) {
    $flash = $ss->session->GetFlash('memory');
    $flash = $flash ? round($flash/1024/1024, 2) . ' Mbytes + ' : null;
    $html .= "<p>Peek memory consumption was $flash" . round(memory_get_peak_usage(true)/1024/1024, 2) . " Mbytes.</p>";
  } 
  if(isset($ss->config['debug']['siteshop']) && $ss->config['debug']['siteshop']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CSiteshop:</p><pre>" . htmlent(print_r($ss, true)) . "</pre>";
  }    
  if(isset($ss->config['debug']['session']) && $ss->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of CSiteshop->session:</p><pre>" . htmlent(print_r($ss->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }
   if(isset($ss->config['debug']['timestamp']) && $ss->config['debug']['timestamp']) {
    $html .= $ss->log->TimestampAsTable();
    $html .= $ss->log->PageLoadTime();
    $html .= $ss->log->MemoryPeak();
  } 
  return "<div class='debug'>$html</div>";
}


/**
 * Get messages stored in flash-session.
 */
function get_messages_from_session() {
  $messages = CSiteshop::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}

function login_menu() {
  $ss = CSiteshop::Instance();
  if(isset($ss->config['menus']['login'])) {
    if($ss->user->isAuthenticated()) {
      $item = $ss->config['menus']['login']['items']['ucp'];
      $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'><img class='gravatar'  alt=''> " . $ss->user['acronym'] . "</a> ";
      if($ss->user['hasRoleAdmin']) {
        $item = $ss->config['menus']['login']['items']['acp'];
        $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
      }
      $item = $ss->config['menus']['login']['items']['logout'];
      $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    } else {
      $item = $ss->config['menus']['login']['items']['login'];
      $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    }
    return "<nav>$items</nav>";
  }
  return null;
}

function navbar_ucp() {
    $ss = CSiteshop::Instance();
    $item = $ss->config['menus']['navbar-ucp']['items']['profile']['mail']['groups']['password'];
}



/**
 * Get a gravatar based on the user's email.
 */
function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CSiteshop::Instance()->user['email']))) . '.jpg?r=pg&amp;d=wavatar&amp;' . ($size ? "s=$size" : null);
}

/**
 * Escape data to make it safe to write in the browser.
 *
 * @param $str string to escape.
 * @returns string the escaped string.
 */
function esc($str) {
  return htmlEnt($str);
}


/**
* Filter data according to a filter. Uses CMContent::Filter()
*
* @param $data string the data-string to filter.
* @param $filter string the filter to use.
* @returns string the filtered string.
*/
function filter_data($data, $filter) {
  return CMContent::Filter($data, $filter);
}


/**
* Display diff of time between now and a datetime.
*
* @param $start datetime|string
* @returns string
*/
function time_diff($start) {
    return formatDateTimeDiff($start);
}




/**
 * Prepend the base_url.
 */
function base_url($url=null) {
    return CSiteshop::Instance()->request->base_url . trim($url, '/');
}


/**
 * Create a url to an internal resource.
 *
 * @param string the whole url or the controller. Leave empty for current controller.
 * @param string the method when specifying controller as first argument, else leave empty.
 * @param string the extra arguments to the method, leave empty if not using method.
 */
function create_url($urlOrController=null, $method=null, $arguments=null) {
    return CSiteshop::Instance()->CreateUrl($urlOrController, $method, $arguments);
}

/**
 * Prepend the theme_url, which is the url to the current theme directory.
 *
 * @param $url string the url-part to prepend.
 * @returns string the absolute url.
 */
function theme_url($url) {
    return create_url(CSiteshop::Instance()->themeUrl . "/{$url}");
}

/**
 * Prepend the theme_parent_url, which is the url to the parent theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_parent_url($url) {
    return create_url(CSiteshop::Instance()->themeParentUrl . "/{$url}"); 
}


/**
 * Return the current url.
 */
function current_url() {
    return CSiteshop::Instance()->request->current_url;
}


/**
* Render all views.
*
* @param $region string the region to draw the content in.
*/
function render_views($region='default') {
    return CSiteshop::Instance()->views->Render($region);
}

/**
 * Check if region has views. Accepts variable amount of arguments as regions.
 *
 * @param $region string the region to draw the content in.
 */
function region_has_content($region = 'default' /* ... */) {
    return CSiteshop::Instance()->views->RegionHasView(func_get_args());
}

/**
 * Create menu.
 *
 * @param array $menu array with details to generate menu.
 * @return string with formatted HTML for menu.
 */
function create_menu($menu) {
  return CSiteshop::Instance()->CreateMenu($menu);
}

