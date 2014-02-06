<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 5';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 5
			<small>push content into Wordpress ("ffu_merger")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>99<?php } else { ?>96<?php } ?>%;"></div>
	</div>

<?php

// Settings
// ****************************************************************************	
	$guide = "ff7";
	$new_site = "http://games.ffuniverse.nu/" . $guide . "/";
	$domain = "http://guide.ffuniverse.nu";


// The actual code
// ****************************************************************************	

	if (ISPOST)
	{

		// First fetch the crawled URL (from step 1)
		$result = db_getSite( array('id' => $PAGE_siteid) );

		// If anything was found, put it into pur PAGE_form
		if (!is_null($result))
		{
			$row = $result->fetch_object();

			$oldurl = $row->url;

		}

		// Get all pages that has been connected to a Wordpress page, these will get transfered now
		$result = db_getWPDataFromSite2($PAGE_siteid);
		if ( isset( $result ) )
		{

			while ( $row = $result->fetch_object() )
			{
				
				//$content = utf8_encode($row->clean);
				$content = $row->clean;

				$getWP = db_getPageFromWordpress($wp_table, $row->wp_postid);

				if (!is_null($getWP)) {


					// Update all links
					$newlink = $row->wp_guid;
					$oldlink = $row->page;

					if ($newlink != "" && !is_null($newlink))
					{

						$mapparArr = explode('/', $oldlink);
						$fil = $mapparArr[count($mapparArr) - 1];
						$mapp = $mapparArr[count($mapparArr) - 2];

						$newlink = str_replace( $domain,'',$newlink);

						echo "<strong>Changed links from</strong> \"" . $fil . "\" <strong>to</strong> \"" . str_replace( $new_site, "/", $newlink ) . "\" - ";


						// Ta samtidigt bort fix-classen om den finns:
						$fixWP = db_updateWPwithNewLinks($wp_table, '<a class="fix" href="' . $fil, '<a href="' . $newlink);
						
						// Alla har kanske inte fixklassen, uppdatera dem med:
						$fixWP2 = db_updateWPwithNewLinks($wp_table, ' href="' . $oldlink, ' href="' . $newlink);

						//$fixWP = 0;
						//$fixWP2 = 0;

						if ($fixWP >= 0 OR $fixWP2 >= 0) {
							
							//echo "$fixWP st ändrade (med class) och $fixWP2 utan!";
							echo "<span class=\"badge badge-success\">" . ($fixWP + $fixWP2) . "</span>";
						}

						echo "<br />";

					}
					// End link updater

					
					$WProw = $getWP->fetch_object();
/*
					// Separate content in WP if there already is something there
					if ($WProw->post_content != '') {

						$content = $WProw->post_content . "

	<hr /><hr /><hr />

	" . $content;

					}
*/
					db_updateWPwithText($wp_table, $content, $row->wp_postid);

					echo "<p>";
					echo "<strong>Move old page:</strong> \"" . str_replace( $oldurl, "/", $row->page ) . "\"";
					echo " <strong>to Wordpress page:</strong> \"" . str_replace( $new_site, "/", $WProw->guid ) . "\"";
					echo " <span class=\"label label-success\">OK</span>";
					echo "</p>";
					
//					echo "<pre>" . htmlentities( $content, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
//					echo '<div style="background-color:#bbb;">PAGEBREAKER</div>';

				}


			}

		}

	}

?>

<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span12">

			<p>
				Activating this step will run through your old clean pages and push all the code
				straight into your Wordpress pages (given the connections made in previous step).
			</p>
			<p>
				This step also updates all your old links so it fits nicely inside your new
				Wordpress installation.
			</p>

			<input type="submit" name="save_wash" value="Move 'em all!" class="btn btn-primary" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>