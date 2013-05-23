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

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>58<?php } else { ?>45<?php } ?>%;"></div>
	</div>

<form class="well form-inline" action="" method="post">

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
	$guide = "ff9";
	$new_site = "http://guide.ffuniverse.nu/" . $guide . "/";


// Do the moving
// ****************************************************************************

	if ( qsGet("connect") != "" && qsGet("to") != "") {

		$id = qsGet("connect");

		// Hämta data från WP
		$result = db_getPostFromWP($wp_table, qsGet("to"));

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

	$result = db_getWPDataFromSite($PAGE_siteid);
	if ( isset( $result ) )
	{
		echo '<table style="width:50%; float:left;">';

		while ( $row = $result->fetch_object() )
		{
			if ($row->wp_postid > 0) {
				if ($row->id == qsGet("connect") )
					echo '<tr style="background-color:black; color:white; font-weight:bold;">';
				else
					echo '<tr style="background-color:green; opacity:0.4;">';
				$arrWPidDone[] = $row->wp_postid;
			} else
				if ($row->id == qsGet("connect") )
					echo '<tr style="background-color:black; color:white; font-weight:bold;">';
				else
					echo '<tr>';
			
			if (qsGet("connect") != "")
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

			if ( qsGet("connect") != "" )
				echo "<td><a href=\"?connect=" . qsGet("connect") . "&amp;to=" . $row->ID . "\">[Connect]</a></td>";
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

// END FILE
// ****************************************************************************

?>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>