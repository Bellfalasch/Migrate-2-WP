<?php
	/* Set up template variables */
	$PAGE_step  = 8;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'push content into Wordpress';
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


<?php

// The actual code
// ****************************************************************************	

	if (ISPOST)
	{

		// Get all pages that has been connected to a Wordpress page, these will get transfered now
		$result = db_getWPDataFromSite2( array( 'site' => $PAGE_siteid ) );
		if ( isset( $result ) )
		{

			while ( $row = $result->fetch_object() )
			{
				
				//$content = utf8_encode($row->clean);

				$stop = false;

				// Waterfall-choose the best (cleanest) html from the database depending on which is available
				if ( !is_null($row->clean) ) {

					$content = $row->clean;

				} elseif ( !is_null($row->tidy) ) {

					$content = $row->tidy;

				} elseif ( !is_null($row->wash) ) {

					$content = $row->wash;

				} elseif ( !is_null($row->content) ) {

					$content = $row->content;

				} else {

					$stop = true;
				
				}

				if ( !$stop ) {

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
							$content = str_replace( " href=\"" . $fil, " href=\"" . $newlink, $content, $counter );
							
							// This will turn out bad on WP folder navigation, we need full root linking!
							//$newlink = str_replace( $PAGE_sitenewurl,'',$newlink);
							//str_replace( $PAGE_sitenewurl, "/", $newlink )

							echo "<strong>Changed links from</strong> \"" . $fil . "\" <strong>to</strong> \"" . $newlink . "\" - ";


							// Update all the Links on ALL the pages in WP!!!
							$fixWP2 = db_updateWPwithNewLinks($wp_table, ' href="' . $oldlink, ' href="' . $newlink);
							$fixWP = 0;

							// Output a counter if we got any hits
							//if ($fixWP2 >= 0) {
								
								echo "<span class=\"badge badge-success\">" . ($fixWP + $fixWP2) . "</span>";
							//}

//							echo "<span class=\"badge badge-success\">" . $counter . "</span>";
							echo "<br />";

						}
						// End link updater

						$WProw = $getWP->fetch_object();

						// Add the review page flag
						if (isset($_POST['flag'])) {
							
							// Flag every page at the top for manual review
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
						echo "<strong>Move old page:</strong> \"" . str_replace( $PAGE_siteurl, "/", $row->page ) . "\"";
						echo " <strong>to Wordpress page:</strong> \"" . str_replace( $PAGE_sitenewurl, "/", $WProw->guid ) . "\"";
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

					}

				}

			}

		}

	}

?>

<form class="well form" action="" method="post">

	<div class="row">
		<div class="span11">

			<p>
				Activating this step will run through your old clean pages and push all the code
				straight into your Wordpress pages (given the connections made in previous step).
			</p>
			<p>
				This step also updates all your old links so it fits nicely inside your new
				Wordpress installation.
			</p>

			<h3>Settings:</h3>
			<label>
				<input type="checkbox" name="fix" value="yes"<?php if (isset($_POST['fix'])) { ?> checked="checked"<?php } ?> />
				Add the class "fix" to links and "imgfix" to images inside content (easily spot them in admin and on site if you style them)
				<span class="help-block">The class on links is removed on all links we can manage to update to the new correct links automatically.</span>
			</label>
			<label>
				<input type="checkbox" name="separator" value="yes"<?php if (isset($_POST['separator'])) { ?> checked="checked"<?php } ?> />
				When pages get smashed together in one WP-page, add a separator?
				<span class="help-block">Without this, existing content in WordPress will be removed!</span>
			</label>
			<label>
				<input type="checkbox" name="flag" value="yes"<?php if (isset($_POST['flag'])) { ?> checked="checked"<?php } ?> />
				Add a "Text not manually checked" on top of every moved page in WordPress?
			</label>
			<br />

			<input type="submit" name="save_move" value="Move 'em all!" class="btn btn-primary" />

			<input type="submit" name="save_move" value="Test move" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>