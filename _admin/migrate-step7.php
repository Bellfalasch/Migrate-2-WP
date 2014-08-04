<?php
	/* Set up template variables */
	$PAGE_step  = 7;
	$PAGE_name  = 'Step 7';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 7
			<small>push content into Wordpress ("ffu_merger")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>99<?php } else { ?>96<?php } ?>%;"></div>
	</div>

<?php

// Settings
// ****************************************************************************	

	$new_site = $PAGE_sitenewurl;
	$oldsite = $PAGE_siteurl;


// The actual code
// ****************************************************************************	

	if (ISPOST)
	{

		// Get all pages that has been connected to a Wordpress page, these will get transfered now
		$result = db_getWPDataFromSite2($PAGE_siteid);
		if ( isset( $result ) )
		{

			while ( $row = $result->fetch_object() )
			{
				
				//$content = utf8_encode($row->clean);
				$content = $row->clean;

				// Tag code that will stick out a bit in Wordpress admin afterwards so you manually can validate everything easier
				if (isset($_POST['fix'])) {
					$content = str_replace('<img ', '<img class="imgfix" ', $content);
					$content = str_replace('<a href="', '<a class="fix" href="', $content);
				}


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

						// Content with links that has the class="fix" added should get that removed now
						if (isset($_POST['fix'])) {
							$content = str_replace( " class=\"fix\" href=\"" . $fil, " href=\"" . $newlink, $content );
						}

						// Replace all the old href URLs with the new one in the current text
						$content = str_replace( " href=\"" . $fil, " href=\"" . $newlink, $content );
						// TODO: Counter - http://php.net/manual/en/function.str-replace.php
						
						// This will turn out bad on WP folder navigation, we need full root linking!
						//$newlink = str_replace( $new_site,'',$newlink);
						//str_replace( $new_site, "/", $newlink )

						echo "<strong>Changed links from</strong> \"" . $fil . "\" <strong>to</strong> \"" . $newlink . "\" - ";

/*
						// Update all the Links on ALL the pages in WP!!!
						$fixWP2 = db_updateWPwithNewLinks($wp_table, ' href="' . $oldlink, ' href="' . $newlink);

						$fixWP = 0;
						//$fixWP2 = 0;

						// Output a counter
						if ($fixWP >= 0 OR $fixWP2 >= 0) {
							
							echo "<span class=\"badge badge-success\">" . ($fixWP + $fixWP2) . "</span>";
						}
*/
						echo "<span class=\"badge badge-success\">?</span>";
						echo "<br />";

					}
					// End link updater

					$WProw = $getWP->fetch_object();

					// Add the page separator?
					if (isset($_POST['flag'])) {
						
						// Flag empty pages at the top for manual review
						if ($WProw->post_content == '') {

							$content = "<div class=\"infobox warning\"><p>This content needs to be reviewed manually before publishing (after that, remove this box!)</p></div>" . $content;

						}
					}

					// Add the page separator?
					if (isset($_POST['separator'])) {
						
						// Separate content in WP if there already is something there
						if ($WProw->post_content != '') {

							$content = $WProw->post_content . "<hr /><hr /><hr />" . $content;

						}
					}


				echo "<p>";
				echo "<strong>Move old page:</strong> \"" . str_replace( $oldurl, "/", $row->page ) . "\"";
				echo " <strong>to Wordpress page:</strong> \"" . str_replace( $new_site, "/", $WProw->guid ) . "\"";
				echo " <span class=\"label label-success\">OK</span>";
				echo "</p>";

				if (formGet("save_move") != "Test move") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					// Do some saving right into WP
					db_updateWPwithText($wp_table, $content, $row->wp_postid);

					db_updateStepValue( array(
						'step' => $PAGE_step,
						'id' => $PAGE_siteid
					) );

				} else {
					
					echo "<p><strong>Result:</strong> <span class=\"label label-important\">Not saved</span></p>";
				
				}

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

			<strong>Settings:</strong><br />
			<label>
				<input type="checkbox" name="fix" value="yes"<?php if (isset($_POST['fix'])) { ?> checked="checked"<?php } ?> />
				Add the class "fix" to links and "imgfix" to images inside content (easily spot them in admin and on site if you style them)
				This class is automatically removed on all links we can manage to update through the code.
			</label><br />
			<label>
				<input type="checkbox" name="separator" value="yes"<?php if (isset($_POST['separator'])) { ?> checked="checked"<?php } ?> />
				When pages get smashed together in one WP-page, add a separator? (without this, existing content in WordPress will be removed!)
			</label><br />
			<label>
				<input type="checkbox" name="flag" value="yes"<?php if (isset($_POST['flag'])) { ?> checked="checked"<?php } ?> />
				Add a "Text not manually checked" on top of every moved page in WordPress?
			</label><br />
			<br />

			<input type="submit" name="save_move" value="Move 'em all!" class="btn btn-primary" />

			<input type="submit" name="save_move" value="Test move" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>