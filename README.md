Chef, a PHP-based, MVC-inspired Content Management Framework
=============================================================

This is my first tryout in creating a site with som MVC patterns. It is part of
my studies at BTH, Blekinge Tekniska Högskola, in Sweden and some text may be
written in Swedish.
Most of the content is created by our teacher Mikael Roos.
https://github.com/mosbth/lydia/blob/v0.1.0/README.md


History
----------------
master (2013-09-12) 

* All requests handled by `index.php` and using mod_rewrite in `.htaccess`. 
* A base structure with `bootstrap.php`, frontcontroller and theme engine.
* Frontcontroller `CSiteshop::FronControllerRoute()` supporting varius url-constructs.
* A basic theme controller, `CSiteshop::ThemeEngineRender()`, with `functions.php`, `style.css` and template files.
* Managing base_url and introducing theme helper functions.
* 'CRequest' manages creation of internal links.

v0.1.0 (2013-09-22)

* CC is a controller class.
* CM is a model class.
* C is a ordinary class without any specific category.
* I is interface classes

Todo
----------------

* Lots of things to do.