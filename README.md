Siteshop, a PHP-based, MVC-inspired CMF
====================================

Siteshop is a final home assignment at a University course covering framework with Model-View-Controller and with some Content Managment Framework, all in PHP. 
It follows the Lydia tutorial, written by Mikael Roos at BTH, Blekinge Tekniska Högskola, in Sweden.
You can find his code at https://github.com/mosbth/lydia.

License
-------

Lydia is licensed according to MIT-license. He will consider double licensing with GPL in the future.
Any included external modules are subject to their own licensing.


Download Siteshop
======================================================

Siteshop is written (modified from Lydia) by Gunvor Nilsson, student at BTH. 

You can download Siteshop from github.

	git clone git://github.com/guni12/siteshop.git

and you can review its source directly on 

	github: https://github.com/guni12/siteshop
	

Installation
======================================================

You have downloaded Siteshop from github and you have uploaded it on your server.

The data-directory must be writable, as Siteshop writes and creates files.

	cd siteshop; chmod 777 application/data

Depending on where you place Siteshop, relative to root in your server, you might need to edit your .htacess file accordingly. You find this file directly in root of Siteshop.

	RewriteBase /<correct server-path>/siteshop/
	
You can ask for help at your server-provider if you don't know the path.

You also need to enable short tags for Siteshop to work. Find short_open_tag in your php.ini-file and make shore it is enabled.

	
Now it's time to start your browser and go to Siteshop's indexpage and follow instructions from there. You will get information about your php-version and extensions that you
might need to have installed. If everything works out, you now get access to all the controllers in Siteshop via the index-page. You can only reach some of the content if you are
logged in as administrator, otherwise permission is denied. 

Login
=======================================================

You can login with root/root as the administrator or with doe/doe, helga/helga or bb/bb for fun. As the administrator you can go to the acp page and create or make changes of stuff
saved in the database, such as usernames, blogcontent, group-belongings etc. If the users are members of the third group, 'Järngänget', they have access to a secret page which they 
reach from siteshop/user. This can also be edited by the administrator.

Startup-class
==========================================================

If you want to reach the startup again you can click the link from the index-page. You can also set 
	'startup'      => array('enabled' => false,'class' => 'CCStartup'),
enabled = false as you see here. This means that only the administrator can install all the modules, if needed.


Change the sites appearance
========================================================

In order to change logo, the website-title, footer and navmenu - go to the config.php file in the application folder. 
You find for instance this:

	$ss->config['menus'] = array(
    'navbar' => array(
        'home' => array('label' => t('Home'), 'url' => 'home'),
        'modules' => array('label' => t('Modules'), 'url' => 'modules'),
        'content' => array('label' => t('Content'), 'url' => 'content'),
    ),
	
Here you can alter the label-names from Home, Modules etc. to something else. Beware - you will lose the translation to Swedish via the .mo-file then, unless you learn to 
edit this file with an editer as Poedit. You can add more labels if you follow the exact same pattern. An array in the config-file shows you which controllers you can use:
$ss->config['controllers'] = array(...

I have chosen to only include the my-navbar in the bb theme and both navbar and my-navbar in the grid theme. But you can go to the bb-folder, find and uncomment this code, 
<!--<div id='navbar'><?=render_views('navbar')?></div>-->, in the index.tpl.php file. Or you can change in
'menu_to_region' => array(...

from 	'my-navbar'=>'my-navbar'),
to 		'my-navbar'=>'navbar'),

(In the my-navbar case all of the navigation-elements' url points to the class CCMycontroller.php and methods in it. In order to make alterations here you need to know how to add 
new methods and templet files.)

	'my-navbar' => array(
        'me' => array('label' => t('About Me'), 'url' => 'my'),
        'blog' => array('label' => t('My blog'), 'url' => 'my/blog'),
        'guestbook' => array('label' => t('Guestbook'), 'url' => 'my/guestbook'),
    ),
	


You also find this:

	$ss->config['theme'] = array(
    'path' => 'application/themes/mytheme',  
    'parent' => 'themes/bb', 
    //'path' => 'themes/grid', 
    //'parent' => 'themes/grid',
	
If you want to have the grid theme instead of as now the bb theme, comment out 'parent' => 'themes/bb' and uncomment 'parent' => 'themes/grid'. You also need to change
some codes in the style.css situated in application/themes/mytheme. You will find instructions there.

Further down in the config.php-file you find this:

	'data' => array(
        'header' => 'Siteshop',
        'slogan' => t('A PHP-based MVC-inspired CMF'),
        'favicon' => 'icopig.ico',
        'logo' => 'pig.jpg',
        'logo_width' => 88,
        'logo_height' => 88,
        'footer' => t('<p>Siteshop &copy; by Gunvor Nilsson (student at BTH)</p>'),
    ),

It's easy to alter the header- slogan- and footer-text here and include a new image, which you place in application/themes/mytheme. 

You need to have gettext installed and enabled to make the translations into Swedish work. If you want to make your own translations you need to download an editor, such as
Poedit, and prepare it with the exact paths to the folders where your texts lie - the ones that you want to make translatons of. You can also make a new folder with 
another language, German for instance, and place it in the language folder. 
You cannot translate texts from the database with Poedit.

v0.1.3 (2014-01-15)

The following external modules are included in Siteshop.

### HTMLPurifier
HTML Purifier 4.5.0 - Standards Compliant HTML Filtering
Copyright (C) 2006-2008 Edward Z. Yang
* Website: http://htmlpurifier.org/ 
* License: LGPL
* Siteshop path: `src/CHTMLPurifier/htmlpurifier-4.5.0-standalone`
* Used by: `CHTMLPurifier`


### PHP Markdown & PHP Markdown Extra
PHP Markdown by Michel Fortin to filter text to HTML to write for the web. Based on the concept of Markdown by John Gruber.
* Website: PHP markdown: http://michelf.com/projects/php-markdown/
* Website: Markdown: http://daringfireball.net/projects/markdown/
* Version: PHP Markdown Lib 1.3 - 11 Apr 2013 (This is a library package that includes the PHP Markdown parser and its 
sibling PHP Markdown Extra which additional features.)
* License: PHP Markdown & PHP Markdown Extra has BSD-style open source license OR GNU General Public License version 2 or a later version.
* License: Markdown has BSD-style open source license.
* Siteshop path: `src/CTextFilter/php-markdown-lib`
* Used by: `CTextFilter`


### PHP SmartyPants & PHP Typographer
PHP SmartyPants and PHP Typographer by Michel Fortin for better typography. Based on the concept of Markdown by John Gruber.
* Website: PHP SmartyPants: http://michelf.com/projects/php-smartypants/
* Website: PHP Typographer: http://michelf.com/projects/php-smartypants/typographer/
* Website: SmartyPants: http://daringfireball.net/projects/smartypants/
* Version: PHP SmartyPants: 1.5.1f - Sun 23 Jan 2013
* Version: PHP Typographer: 1.0.1 - Sun 23 Jan 2013
* License: PHP SmartyPants & PHP Typographer has BSD-style open source license.
* License: SmartyPants has BSD-style open source license.
* Siteshop path: `src/CTextFilter/php_smartypants_1.5.1f`
* Siteshop path: `src/CTextFilter/php_smartypants_typographer_1.0.1`
* Used by: `CTextFilter`


### lessphp
lessphp by leaf to compile LESS.
* Website: http://leafo.net/lessphp
* Version: 0.3.8 (2012-08-18)
* License: Dual license, MIT LICENSE and GPL VERSION 3
* Siteshop path: `themes/grid/lessphp`, `themes/bb/lessphp`
* Used by: `themes/grid/style.php`, `themes/bb/style.php`



### The Semantic Grid System
by Tyler Tate/TwigKit to get grid layout through LESS.
* Website: http://semantic.gs/
* Version: 1.2 (2012-01-11)
* License: Apache License
* Siteshop path: `themes/grid/semantic.gs`, `themes/bb/semantic.gs` 
* Used by: `themes/grid/style.less`, `themes/bb/style.less`,


TODO
====================================================

Translations of texts in the database needs to be situated in another database-table and be distributed around the framework.




