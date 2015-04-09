<?php

	// All these SQLs are for the different pages in this admin. Add yours here.

	//////////////////////////////////////////////////////////////////////////////////
	// STEPS
	//////////////////////////////////////////////////////////////////////////////////

	/* Universal SQL used in many places */
	/* **************************************************************************** */
	function db_updateStepValue($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_sites`
			SET `step` = {$in['step']}
			WHERE `id` = {$in['id']}
			AND
			(
				{$in['step']} > `step`
				OR
				0 = {$in['step']}
			)
		");
	}

	/* Step 1 */
	/* **************************************************************************** */
	
	// Also used in Step 5b
	function db_setNewPage($in) { cleanup($in);
		return db_MAIN("
			INSERT INTO `migrate_content`
				(`site`,`html`,`content`,`clean`,`page`)
			VALUES(
				{$in['site']},
				{$in['html']},
				{$in['content']},
				{$in['clean']},
				{$in['page']}
			)
		");
	}
	// Check if crawled page exist already, so we won't create it again
	function db_getDoesPageExist($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			AND `page` = {$in['page']}
		");
	}
	// Crawled page existed, so update it's html instead of adding a new page
	function db_setUpdatePage($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET `html` = {$in['html']}
			WHERE `id` = {$in['id']}
		");
	}

	/* Step 2 */
	/* **************************************************************************** */
	function db_setContentCode($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET `content` = {$in['content']}
			WHERE `id` = {$in['id']}
			LIMIT 1
		");
	}

	function db_getHtmlFromFirstpage($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`, `html`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			ORDER BY `id` ASC
			LIMIT 1
		");
	}

	function db_getDataFromSite($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`, `html`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			ORDER BY `page` ASC
		");
	}

	/* Step 3 */
	/* **************************************************************************** */
	function db_getHtmlFromPage($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`, `content`, `wash`, `tidy`, `clean`
			FROM `migrate_content`
			WHERE `id` = {$in['id']}
			AND `site` = {$in['site']}
			ORDER BY `id` ASC
			LIMIT 1
		");
	}

	function db_getPagesFromSite($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			ORDER BY `page` ASC
		");
	}

	function db_delPage($in) { cleanup($in);
		return db_MAIN("
			DELETE FROM `migrate_content`
			WHERE `site` = {$in['site']}
			AND `id` = {$in['id']}
			LIMIT 1
		");
	}

	function db_setDuplicatePage($in) { cleanup($in);
		return db_MAIN("
			INSERT INTO `migrate_content`
			( `page`, `html`, `site`, `content`, `wash`, `tidy`, `clean` )
			SELECT `page`, `html`, `site`, `content`, `wash`, `tidy`, `clean`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			AND `id` = {$in['id']}
		");
	}

	/* Step 4 */
	/* **************************************************************************** */
	function db_setWashCode($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET `wash` = {$in['wash']}
			WHERE `id` = {$in['id']}
			LIMIT 1
		");
	}

	// Also used in Step 5 and 6
	function db_getContentFromSite($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`, `content`, `wash`, `tidy`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			ORDER BY `page` ASC
		");
	}

	/* Step 5 */
	/* **************************************************************************** */
	function db_setTidyCode($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET `tidy` = {$in['tidy']}
			WHERE `id` = {$in['id']}
			LIMIT 1
		");
	}

	/* Step 6 */
	/* **************************************************************************** */
	function db_setCleanCode($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET `clean` = {$in['clean']}
			WHERE `id` = {$in['id']}
			LIMIT 1
		");
	}

	/* Step 7 */
	/* **************************************************************************** */
	function db_getWPDataFromSite($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`, `html`, `wp_postid`, `wp_guid`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			ORDER BY wp_postid ASC, `page` DESC
		");
	}
	
	// NB! Old style - can't be changed because we're sending in the table name
	function db_getDataFromWordpress($wptable) {
		return wp_MAIN("
			SELECT ID, post_content, post_title, post_status, post_name, post_modified, post_parent, guid, post_type
			FROM `" . $wptable . "_posts`
			WHERE
				(`post_type` = 'page'
				OR `post_type` = 'ffu_characters')
				AND	`post_status` = 'publish'
			ORDER BY `post_name` DESC
		");
	}

	function db_updateCleanerWithWP($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET
				wp_slug = {$in['name']},
				wp_postid = {$in['postid']},
				wp_guid = {$in['guid']}
			WHERE `id` = {$in['id']}
		");
	}

	// NB! Old style - can't be changed because we're sending in the table name
	function db_getPostFromWP($wptable, $id) {
		return wp_MAIN("
			SELECT id, post_content, post_title, post_status, post_name, post_modified, post_parent, guid, post_type
			FROM `" . $wptable . "_posts`
			WHERE `id` = $id
		");
	}

	// Disconnect an already connected page from it's WordPress counterpart
	function db_updateDisconnectPage($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_content`
			SET
				`wp_guid` = null,
				`wp_postid` = 0
			WHERE `site` = {$in['site']}
			AND `id` = {$in['id']}
		");
	}

	/* Step 8 */
	/* **************************************************************************** */
	function db_getWPDataFromSite2($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `page`, `content`, `wash`, `tidy`, `clean`, `wp_postid`, `wp_guid`
			FROM `migrate_content`
			WHERE `site` = {$in['site']}
			AND wp_postid > 0
			AND `wp_guid` IS NOT NULL
			ORDER BY wp_postid ASC, `page` DESC
		");
	}

	// NB! Old style - can't be changed because we're sending in the table name
	function db_getPageFromWordpress($wptable, $postid) {
		return wp_MAIN("
			SELECT ID, post_content, post_title, post_status, post_name, post_modified, post_parent, guid, post_type
			FROM `" . $wptable . "_posts`
			WHERE ID = " . $postid . "
		");
	}

	// NB! Old style - can't be changed because we're sending in the table name
	function db_updateWPwithText($wptable, $content, $postid) {
		global $mysqWP;
		return wp_MAIN("
			UPDATE `" . $wptable . "_posts`
			SET post_content = '" . $mysqWP->real_escape_string($content) . "'
			WHERE `id` = " . $postid . "
			LIMIT 1
		");
	}

	function db_updateWPwithNewLinks($wptable, $oldlink, $newlink) {
		//global $mysqWP;
		return wp_EXEC("
			UPDATE `" . $wptable . "_posts`
			SET post_content = REPLACE(post_content, '" . $oldlink . "', '" . $newlink . "')
			WHERE `post_status` = 'publish'
		");
	}


	//////////////////////////////////////////////////////////////////////////////////
	// PROJECTS
	//////////////////////////////////////////////////////////////////////////////////

	function db_getSites() {
		return db_MAIN("
			SELECT `id`, `name`, `url`, `new_url`, `step`
			FROM `migrate_sites`
			ORDER BY `id` DESC
		");
	}
	function db_getSite($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `name`, `url`, `new_url`, `step`
			FROM `migrate_sites`
			WHERE id = {$in['id']}
		");
	}
	function db_setSite($in) { cleanup($in);
		return db_MAIN("
			INSERT INTO `migrate_sites`
				(`name`,`url`,`new_url`)
			VALUES(
				{$in['name']},
				{$in['url']},
				{$in['new_url']}
			)
		");
	}
	function db_setUpdateSite($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_sites`
			SET
				`name` = {$in['name']},
				`url` = {$in['url']},
				`new_url` = {$in['new_url']}
			WHERE `id` = {$in['id']}
		");
	}
	function db_delSite($in) { cleanup($in);
		return db_MAIN("
			DELETE FROM `migrate_sites`
			WHERE `id` = {$in['id']}
		");
	}
	function db_delSiteContent($in) { cleanup($in);
		return db_MAIN("
			DELETE FROM `migrate_content`
			WHERE `site` = {$in['site']}
		");
	}

	//////////////////////////////////////////////////////////////////////////////////
	// USERS
	//////////////////////////////////////////////////////////////////////////////////

	function db2_getUserLoginInfo($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `name`, `username`, `password`, `mail`, `level`
			FROM `migrate_users`
			WHERE `mail` LIKE {$in['mail']}
			LIMIT 1
		;");
	}
	function db2_getUsers() {
		return db_MAIN("
			SELECT `id`, `name`, `username`, `password`, `mail`, `level`
			FROM `migrate_users`
			ORDER BY `id` DESC
		");
	}
	function db2_getUser($in) { cleanup($in);
		return db_MAIN("
			SELECT `id`, `name`, `username`, `password`, `mail`, `level`
			FROM `migrate_users`
			WHERE id = {$in['id']}
		");
	}
	function db2_setUpdateUser($in) { cleanup($in);
		return db_MAIN("
			UPDATE `migrate_users`
			SET
				`name` = {$in['name']},
				`username` = {$in['username']},
				`mail` = {$in['mail']},
				`password` = {$in['password']},
				`level` = {$in['level']}
			WHERE `id` = {$in['id']}
		");
	}
	function db2_setUser($in) { cleanup($in);
		return db_MAIN("
			INSERT INTO `migrate_users`
				(`name`,`username`,`mail`,`password`,`level`)
			VALUES(
				{$in['name']},
				{$in['username']},
				{$in['mail']},
				{$in['password']},
				{$in['level']}
			)
		");
	}
	function db2_delUser($in) { cleanup($in);
		return db_MAIN("
			DELETE FROM `migrate_users`
			WHERE `id` = {$in['id']}
		");
	}

?>
