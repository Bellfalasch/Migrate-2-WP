Migrate 2 WP
================

Migrate your static files to Wordpress
------------------

This repo is based on "[Bobby CMS](https://github.com/Bellfalasch/Bobby-CMS)", which is in development.

The basic function of this code is to first crawl/scrape an old site (perhaps built with static html-files), then clean up / tidy the html, connect old pages to new pages in WordPress, and end it all by pushing the clean code straight into Wordpress.

Until version 1.0 is reached it's highly recommended to use proper backups of your database before using this code.

Updates:
----------------

### 0.9

Other than a lot of refactoring and other improvements, I've introduced Step 5b (I will use the time until 1.0 to think of a new step/menu order). Step 5b is called "Split", because that is what it does. It let's you select any page from your menu structure and then type in a html-code to look for. For each time that code is found within the same page, a sub-page will be created with that text! This makes splitting huge textual pages into smaller sub-pages a breeze. You can even use wildcards in the html-code.

Only problem is that now that I found out how to use Regex this way I want to rewrite a lot of the other steps :P

### 0.8.9

Steps 3b and 3c renamed to 4 and 5 respectivly, thus pushing the previous step 4 to be step 6 and step 5 to be step 7. Makes it all much clearer. Redundant code removed, now using the database settings on all the pages instead of hardcoded values. And a vastly improved crawler for performance and accuracy. However it doesn't run more than one regex (out of four) so I'm gonna look more into that before 0.9 gets out.

Basically it's now possible to do all the steps from beginning to end on a totally new site and get it all into WordPress. I'm just gonna work on some minor things from now on, no big new features coming before 1.0. Happy days!

### 0.8

A lot of changes. Rewrote a lot of the code, changed around all the steps, etc. Step 3 turned into three different steps now. Loads of tweaks to all the code on all the steps and pages.

Still not useful without hacking the code manually. It won't connect to the destination database for instance. Other than that it almost works all the way, with supervision and code edits on the fly. And, it's still only aimed at aiding my move of old code on my homepage and not universal ... yet.

### 0.7
Getting there! Major rearrangement of all the steps after doing some heavy (well, light ;P) thinking. Dropped the last two steps, moved the link-updater from Step 6 into Step 5 ("Move"), and splitting the Step 3 ("Clean") into three steps of it's own (needed for easier handling of the code). All steps now have code - or will soon have - for testing the step before actually running it.

And the crawling in Step 1 had major improvements, with bugfixes, http header status checks, settings, etc. I'm also introducing the brand new Projects page where you create and manage your project, as well as a drop down in the nav-bar so you can select which of your projects to run the migration on.

### 0.5
If you manually edit the code, you can get all the steps running and working fine =) Still a lot to go, and a bucket load of ideas coming. For instance moving the steps around a bit, merging a few, adding one last "checklist", and of course much better settings. Stay tuned!


Disclaimer
----------------

This project is not made to be working universally on every setup there is. I made it to assist myself in porting some old code. Don't expect it to automagically work on every kind of weird old setup. It's only tested to work on WordPress 3.6 to 4.0 and doesn't take into account that WordPress database design might change in the future.

Also, don't expect Migrate 2 WP to produce perfect result from old code. It will do the best it can. You won't get away from manually editing some pages in the end.

Migrate 2 WP doesn't create any pages inside WordPress, that is up to you! And it does not create Menus, Images, or other fancy things. It's just for crawling, formatting, and injecting simple html.

It won't support content spread into many different "blocks" / areas on a single page. It only supports one starting point, and then one ending point. Everything between them will be counted as content.


Installation:
----------------

Look at the list of dependencies after this section. Make sure all is set up. Open phpMyAdmin (or similiar), execute everything in the included file "/DATABASE.sql". Now you can upload your files (or start localhost).

Log in with "admin@example.com" and "password" in the folder "_admin" (might change in the future). Going to the projects root-folder will automatically take you there too.


Dependencies:
----------------

This admin is based and tested on: 

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
* Developed by me =)
* https://github.com/Bellfalasch/Bobby-CMS
* Used as included files

Bobby CMS uses Bootstrap 2.3.1 (because I started the project when that was the latest and greatest, and because I don't like the new flat style of Boostrap 3), TinyMCE, and jQuery 1.8.1. All included. Update/remove at own risk!


Basic file structure and form-generation:
----------------

Check out the readme for [Bobby CMS](https://github.com/Bellfalasch/Bobby-CMS) if you need more information on basic functions, structure, etc that this project uses. All forms are built using that project too. It has a way of setting up forms easily by defining arrays of options. Some code and boom - form for editing, adding, and validation is generated. It's far from finished, but at least it works good enough to be used here =)
