<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 4';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 4
			<small>connect crawled pages with Wordpress ("ffu_locator")</small>
		</h1>
	</div>

<form class="well form-inline" action="" method="post" enctype="multipart/form-data">

	<div class="row">
		<div class="span12">

<?php

/*
	WordPress data ser ut såhär: Tabellen wp_posts har kolumnen “post_content” för sin html-kod, och 
	kolumnen “post_title” för titel (skall inte ändras). Kolumnen “post_status” skall vara “publish”, 
	“post_type” skall vara “page” eller “ffu_characters” (eller annan CPT). “post_name” innehåller 
	url/slug till sidan, och kan användas för att underlätta mappningen.

*/

// Settings
// ****************************************************************************	
	$site = 9;
	$guide = "ff9";
	$new_site = "http://guide.ffuniverse.nu/" . $guide . "/";
	$wp_table = $wp_table;


// Database setup (MySQL)
// ****************************************************************************	

	global $mysqWP;
	$mysqWP = new mysqli( $wp_dburl, $wp_dbuser, $wp_dbpass, $wp_dbname );
	$mysqWP->set_charset('utf8');



// SQL
// ****************************************************************************		
	
	// List all ffueater data (from current/old site)
	function db_getDataFromSite($site) {
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


// Do the moving
// ****************************************************************************

	if (isset($_GET['connect']) && isset($_GET['to'])) {

		$id = $_GET['connect'];

		// Hämta data från WP
		$result = db_getPostFromWP($wp_table, $_GET['to']);

		if ( isset( $result ) ) {

			$row = $result->fetch_object();
			$newData_id = $row->ID;
			$newData_post_name = $row->post_name;
			$newData_post_title = $row->post_title;
			$newData_guid = $row->guid;

		}

		// Spara datan till FFU
		$result = db_updateCleanerWithWP($id, $newData_post_title, $newData_post_name, $newData_id, $newData_guid);

		header('Location: migrate-step4.php');

	}



// The actual code
// ****************************************************************************	

	// Array for all the WP-pages we have listed (don't list again)
	$arrWPidDone = array();

	$result = db_getDataFromSite($site);
	if ( isset( $result ) )
	{
		echo '<table style="width:50%; float:left;">';

		while ( $row = $result->fetch_object() )
		{
			if ($row->wp_postid > 0) {
				if ($row->id == $_GET['connect'])
					echo '<tr style="background-color:black; color:white; font-weight:bold;">';
				else
					echo '<tr style="background-color:green; opacity:0.4;">';
				$arrWPidDone[] = $row->wp_postid;
			} else
				if ($row->id == $_GET['connect'])
					echo '<tr style="background-color:black; color:white; font-weight:bold;">';
				else
					echo '<tr>';
			
			if (isset($_GET['connect']))
				echo "<td>-</td>";
			else
				echo "<td><a href=\"?connect=" . $row->id . "\">[Connect]</a></td>";

			echo "<td><a href=\"" . $row->page . "\" target=\"_blank\">" . $row->page . "</a></td>";
			echo "<td>&raquo; " . $row->wp_guid . "</td>";
			echo '</tr>';
		}

		echo '</table>';
	}

	$result = db_getDataFromWordpress($wp_table);
	if ( isset( $result ) )
	{
		echo '<table style="width:50%; float:left;">';

		while ( $row = $result->fetch_object() )
		{
			if (!in_array($row->ID, $arrWPidDone))
				echo '<tr>';
			else
				echo '<tr style="opacity:0.2;">';

			if (isset($_GET['connect']))
				echo "<td><a href=\"?connect=" . $_GET['connect'] . "&amp;to=" . $row->ID . "\">[Connect]</a></td>";
			else
				echo "<td>-</td>";

			echo "<td>" . $row->ID . "</td>";
			echo "<td>" . $row->post_name . "</td>";
			echo "<td><a href=\"" . $row->guid . "\" target=\"_blank\">" . $row->post_title . "</a></td>";
			echo "<td>" . $row->guid . "</td>";
			echo '</tr>';

		}

		echo '</table>';
	}






// Database main function (does all the talking to the database class and handling of errors)
// This can be updated so that it don't let empty results through, just uncomment all comments =)
// ****************************************************************************	

	function wp_MAIN($sql)
	{
		global $mysqWP;
		$result = $mysqWP->query( $sql );
		if ( $result )
		{
			return $result;
		} else {
			printf("<div class='error'>There has been an error from MySQL: %s<br /><br />%s</div>", $mysqli->error, nl2br($sql));
			exit;
		}
	}



// Close database
// ****************************************************************************	

	$mysqWP->close();



// END FILE
// ****************************************************************************

?>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>