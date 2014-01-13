Siteshop, the application directory
=========================

Some people would call this the application directory, I call it the application directory. It should 
contain the code to actually make a working website. When Siteshop is deployed, and extended into a 
website, then this directory should be the only place where content changes.

The config.php
---------------
The file `config.php` holds the configuration for this application.


The src-directory
-----------------

The directory `src` contains classes for addon controllers, models and more. The autoloader looks here first
for a class file, before it moves on to look in `SITESHOP_INSTALL_PATH/src`. It is therefore possible
to replace core-classes with your own version, just use same classname and place it here.