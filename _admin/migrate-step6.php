<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 6';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 4
			<small>connect crawled pages with WordPress ("ffu_locator")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>95<?php } else { ?>75<?php } ?>%;"></div>
	</div>

	<div class="row">
		<div class="span8">
			<h2>Important information</h2>
			<p>
				Here you see to the left all the crawled pages with their old URL. Just click the "Connect"-link on any of these pages
				to reload this pages and see the same button on the right side. This side is all your Wordpress pages. Just click the
				right "Connect"-link here to connect the two pages. Many old pages can be moved to the same single Wordpress-page (the
				other way around is not supported, yet).
				<strong>No data is transfered to WordPress yet!</strong>
			</p>
			<p>
				Connected pages are moved to the bottom of the left table, but not moved at all (only grayed out) to the right. Thanks
				to this you get a good overview, but still can change earlier mistakes.
			</p>

			<div class="alert alert-block alert-success">
				<h4>No Save-button!?</h4>
				<p>
					When you're ready with all pages you wanna move, manually <a href="migrate-step7.php">go to Step 7</a>.
					Pages left unconnected on the left side in this step will not be moved to Wordpress!
				</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="span12">

<?php

/*
	Swedish comment:
	WordPress data ser ut såhär: Tabellen wp_posts har kolumnen “post_content” för sin html-kod, och 
	kolumnen “post_title” för titel (skall inte ändras). Kolumnen “post_status” skall vara “publish”, 
	“post_type” skall vara “page” eller “ffu_characters” (eller annan CPT). “post_name” innehåller 
	url/slug till sidan, och kan användas för att underlätta mappningen.
*/

// Settings
// ****************************************************************************	
	$new_site = "";
	$oldsite = "";
	
	$result = db_getSite(array('id' => $PAGE_siteid));
	if ( isset( $result ) ) {
		$row = $result->fetch_object();
		$new_site = $row->new_url;
		$oldsite = $row->url;
	}


// Do the moving
// ****************************************************************************

	if ( qsGet("connect") != "" && qsGet("to") != "") {

		$id = qsGet("connect");
		$to = qsGet("to");

		// Fetch data from WordPress
		$result = db_getPostFromWP($wp_table, $to);

		if ( isset( $result ) ) {

			$row = $result->fetch_object();
			$newData_id = $row->id;
			$newData_post_name = $row->post_name;
			$newData_post_title = $row->post_title;
			$newData_guid = $row->guid;

		}

		// Save the selection to the database
		$result = db_updateCleanerWithWP($id, $newData_post_title, $newData_post_name, $newData_id, $newData_guid);

		header('Location: migrate-step4.php');

	}

//	echo "<p>" . $new_site . "</p>";
//	echo "<p>" . $oldsite . "</p>";


// The actual code
// ****************************************************************************	

	// Array for all the WP-pages we have listed (don't list again)
	$arrWPidDone = array();

	$result = db_getWPDataFromSite($PAGE_siteid);
	if ( isset( $result ) )
	{
		echo '<table style="width:50%; float:left;">';

		while ( $row = $result->fetch_object() )
		{
			if ($row->wp_postid > 0) {
				if ($row->id == qsGet("connect") ) {
					echo '<tr style="background-color:black; color:white; font-weight:bold;">';
				} else {
					echo '<tr style="opacity:0.2;">';
				}
				array_push($arrWPidDone, $row->wp_postid);
			} else {
				if ($row->id == qsGet("connect") ) {
					echo '<tr style="background-color:black; color:white; font-weight:bold;">';
				} else {
					echo '<tr>';
				}
			}
			
			if (qsGet("connect") != "")
				echo "<td>-</td>";
			else
				echo "<td><a href=\"?connect=" . $row->id . "\" class=\"btn btn-mini btn-primary\">Connect</a></td>";

			$page = $row->page;

			echo "<td><a href=\"" . $page . "\" target=\"_blank\">" . str_replace( $oldsite, "/", $page ) . "</a></td>";
			echo "<td>&raquo; " . str_replace( $new_site, "/", $row->wp_guid . "" ) . "</td>";
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

			if ( qsGet("connect") != "" )
				echo "<td><a href=\"?connect=" . qsGet("connect") . "&amp;to=" . $row->ID . "\" class=\"btn btn-mini btn-primary\">Connect</a></td>";
			else
				echo "<td>-</td>";

			//echo "<td>" . $row->ID . "</td>";
			//echo "<td>" . $row->post_name . "</td>";
			echo "<td><a href=\"" . $row->guid . "\" target=\"_blank\">" . $row->post_title . "</a></td>";
			echo "<td>" . str_replace( $new_site, "/", $row->guid ) . "</td>";
			echo '</tr>';

		}

		echo '</table>';
	}

// END FILE
// ****************************************************************************

?>

		</div>
	</div>


<?php require('_footer.php'); ?>