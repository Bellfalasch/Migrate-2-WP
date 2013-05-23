Migrate 2 WP
================

Migrate your static files to Wordpress
------------------

This repo is based on Bobby CMS, which is in development.

The basic function of this code is to first crawl an old site, then clean up the html, then manually connect old pages to new pages in Wordpress, and end it all with pushing the clean code straight into Wordpress.

Until 1.0 is reached it's highly recommended to not use this code without proper backups of your database.


Updates:
----------------

### 0.5
If you manually edit the code, you can get all the steps running and working fine =) Still a lot to go, and a bucket load of ideas coming. For instance moving the steps around a bit, merging a few, adding one last "checklist", and of course much better settings. Stay tuned!

### 0.2
Step 1 and 2 are working, but not without problem. Also the basics are down for the rest of the steps, as well as for the settings. Still, this release is far from release ready!

### 0.1
Initial release. Just setting up the basic structure and idea of the project.


Installation:
----------------

Look at the list of dependencies after this section. Make sure all is set up. Open phpMyAdmin (or similiar), execute everything in the included file "DATABASE.sql". Now you can upload your files (or start localhost).

Log in with "admin@example.com" and "password" in the folder "_admin" (might change in the future).


Dependencies:
----------------

This admin is based on: 

### PHP
* Version 4.3.10
* Settings: short open tags = true
* Settings: allow url fopen = true
* Extensions: php_mysqli = ON
* Extensions: php_tidy = ON

### MySQL
* Version 5.5.20
* InnoDB used as engine

### Bobby CMS
* Version 0.9.2.1
* By me
* https://github.com/Bellfalasch/Bobby-CMS
* Used as included files

### Bootstrap
* Version 2.3.1
* By Twitter
* http://twitter.github.io/bootstrap/
* Used as included file

### TinyMCE
* Version ?
* By Moxiecode
* Used as included files

### jQuery
* Version 1.8.1
* By jQuery
* Used as linked CDN


Basic structure:
----------------

Check readme at https://github.com/Bellfalasch/Bobby-CMS for more information on basic functions, structure, etc. All forms are created using functions in Bobby CMS.