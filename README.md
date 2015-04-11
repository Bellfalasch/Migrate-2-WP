Migrate 2 WP - 1.0 Beta
================

Migrate your static files to Wordpress
------------------

*This repo is based on "[Bobby CMS](https://github.com/Bellfalasch/Bobby-CMS)", which is in development (basically just a simple CRUD-system to generate forms for the database).*

The main function of Migrate 2 WP is to first crawl/scrape an old site (perhaps built with static html-files), then clean up / tidy the html, connect old pages to new pages in WordPress, and end it all by pushing the clean code straight into Wordpress.

Until version 1.0 is reached it's highly recommended to use proper backups of your database before using this code.

Updates:
----------------

### 0.9.9.1 - End of Life

This is the last release. 0.9.9 is the final version, with only bug fix releases from now on. I came up with a lot of ideas that kind of changes the fundamentals of this project, so I'll start with these changes in [a new repo going](https://github.com/Bellfalasch/Migrate-2-WordPress-2.0) from 2.0 Beta up to the final 2.0 release. The new version will have a better file structure, and export a WordPress XML-file in the end instead of pushing data straight into the database. Also a lot of other improvements, of course.

### 0.9.9

Mostly improvements to the Wash and Clean steps, and general code quality. Switching out a lot of replace statements with smarter regex. Removing commented out code. Removing inline style. We're finally getting there! =)

### 0.9.8

After loads and loads of commits, we're closing in on 1.0. Only 22 Asana-tasks remain - 6 critical, and 10 low priority (refactoring-things). All the steps work - finally - and they all have a lot of improvements on speed and quality. I can't point on specific things fixed, but the crawler have been getting a lot of improvements, so has the new "Split"-step.

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


Disclaimer
----------------

This project is not made to be working universally on every setup there is. I made it to assist myself in porting some old code from about 10 different sites. Don't expect it to automagically work on every kind of weird old setup. It's only tested to work on WordPress 3.6 to 4.0 and doesn't take into account that WordPress database design might change in the future.

It won't handle URL's based on folders, as it expects a file ending to validate a file as crawlable. It doesn't handle JavaScript (or Ajax) at all, or Flash.

Also, don't expect Migrate 2 WP to produce perfect result from old code. It will do the best it can. You won't get away from having to manually editing some pages in the end anyway, but the amount of work is greatly reduced.

Migrate 2 WP doesn't create any pages inside WordPress, that is up to you! And it does not create Menus, Images, or other fancy things. It's just for crawling, formatting, and injecting simple html inte an existing structure.

It won't support content spread into many different "blocks" / areas on a single page. It only supports one starting point, and then one ending point. Everything between them will be counted as content.

It's based on a somewhat slow fopen-crawl, this will be changed in the future. We had no clue about Curl when we started the project.


Installation:
----------------

Look at the list of dependencies after this section. Make sure all is set up. Open phpMyAdmin (or similiar), execute everything in the included file "/DATABASE.sql" in root. Now upload all the files to your server (or localhost). It *should* work in any folder structure.

Log in with "admin@example.com" and "password" in the login form (you should be redirected automatically when you open the projects root folder).


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

Bobby CMS uses Bootstrap 2.3.1 (because I started the project when that was the latest and greatest, and not changed because I don't like the new flat style of Boostrap 3), TinyMCE, and jQuery 1.8.1. All included. Update/remove at own risk!


Basic file structure and form-generation:
----------------

Check out the readme for [Bobby CMS](https://github.com/Bellfalasch/Bobby-CMS) if you need more information on basic functions, structure, etc that this project uses. All forms are built using that project too. It has a way of setting up forms easily by defining arrays of options. Some code and boom - form for editing, adding, and validation is generated. It's far from finished, but at least it works good enough to be used here =)
