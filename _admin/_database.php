<?php

	// All these SQLs are for the different pages in this admin. Add yours here.

	//////////////////////////////////////////////////////////////////////////////////

	// Step 2
	/**
	 * Hæmta data från vald sajt som vi crawlat tidigare.
	 * 
	 * @param int site 				Vald site att hæmta data før
	 * @return id, page, data
	 */
	function db_getDataFromSite($site) {
		$q = "SELECT `id`, `page`, `html`
			  FROM `migrate_content`
			  WHERE `site` = $site
			  ORDER BY `page` DESC
			  ";
		return db_MAIN( $q );
	}

	// Step 3
	/**
	 * Hæmta data från vald sajt som vi crawlat tidigare.
	 * 
	 * @param int site 				Vald site att hæmta data før
	 * @return id, page, data
	 */
	function db_getContentFromSite($site) {
		$q = "SELECT `id`, `page`, `content`
			  FROM `migrate_content`
			  WHERE `site` = $site
			  ORDER BY `page` DESC
			  ";
		return db_MAIN( $q );
	}

	// Step 4
		// List all ffueater data (from current/old site)
	function db_getWPDataFromSite($site) {
		return db_MAIN("
			SELECT `id`, `page`, `html`, `wp_postid`, `wp_guid`
			FROM `migrate_content`
			WHERE `site` = $site
			ORDER BY wp_postid ASC, `page` DESC
		");
	}

	// List all Wordpress-pages
	function db_getDataFromWordpress($wptable) {
		return wp_MAIN("
			SELECT id, post_content, post_title, post_status, post_name, post_modified, post_parent, guid, post_type
			FROM `" . $wptable . "_posts`
			WHERE
				(`post_type` = 'page'
				OR `post_type` = 'ffu_characters')
				AND	`post_status` = 'publish'
			ORDER BY `post_name` DESC
		");
	}

	// Get specific files WP data
	function db_getPostFromWP($wptable, $id) {
		return wp_MAIN("
			SELECT id, post_content, post_title, post_status, post_name, post_modified, post_parent, guid, post_type
			FROM `" . $wptable . "_posts`
			WHERE `id` = $id
		");
	}

	function db_updateCleanerWithWP($id, $title, $name, $postid, $guid) {
		return db_MAIN("
			UPDATE `migrate_content`
			SET
				wp_slug = '$name',
				wp_postid = '$postid',
				wp_guid = '$guid'
			WHERE `id` = $id
		");
	}

	// Step 5
	// List all ffueater data (from current/old site)
	function db_getWPDataFromSite2($site) {
		return db_MAIN("
			SELECT `id`, `page`, `html`, `clean`, `wp_postid`, `wp_guid`
			FROM `migrate_content`
			WHERE `site` = $site
			AND wp_postid > 0
			AND wp_postid <> 14
			ORDER BY wp_postid ASC, `page` DESC
		");
	}

	// List all Wordpress-pages
	function db_getPageFromWordpress($wptable, $postid) {
		return wp_MAIN("
			SELECT ID, post_content, post_title, post_status, post_name, post_modified, post_parent, guid, post_type
			FROM `" . $wptable . "_posts`
			WHERE ID = " . $postid . "
		");
	}

	function db_updateWPwithText($wptable, $content, $postid) {
		global $mysqWP;
		return wp_MAIN("
			UPDATE `" . $wptable . "_posts`
			SET post_content = '" . $mysqWP->real_escape_string($content) . "'
			WHERE `id` = " . $postid . "
			LIMIT 1
		");
	}

	// Step 6
	// List all ffueater data (from current/old site)
	function db_getWPDataFromSite3($site) {
		return db_MAIN("
			SELECT `id`, `page`, `html`, `clean`, `wp_postid`, `wp_guid`
			FROM `migrate_content`
			WHERE `site` = $site
			AND wp_postid > 0
			ORDER BY wp_postid ASC, `page` DESC
		");
	}

	function db_updateWPwithNewLinks($wptable, $oldlink, $newlink) {
		global $mysqWP;
		return wp_EXEC("
			UPDATE `" . $wptable . "_posts`
			SET post_content = REPLACE(post_content, '" . $oldlink . "', '" . $newlink . "')
			WHERE `post_status` = 'publish'
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