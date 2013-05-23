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
		<div class="bar" style="width: <?php if (ISPOST) { ?>73<?php } else { ?>60<?php } ?>%;"></div>
	</div>

<?php

// Settings
// ****************************************************************************	
	$guide = "ff7";
	$new_site = "http://games.ffuniverse.nu/" . $guide . "/";


// The actual code
// ****************************************************************************	

	if (ISPOST)
	{

		// Öppna alla sidor i ffucleaner som har kopplats till WP
		$result = db_getWPDataFromSite2($PAGE_siteid);
		if ( isset( $result ) )
		{

			while ( $row = $result->fetch_object() )
			{
				
				//$content = utf8_encode($row->clean);
				$content = $row->clean;

				$getWP = db_getPageFromWordpress($wp_table, $row->wp_postid);

				if (!is_null($getWP)) {
					
					$WProw = $getWP->fetch_object();

					// Finns data redan i WP så skilj med hr-taggar
					if ($WProw->post_content != '') {
/*
						$content = $WProw->post_content . "

	<hr /><hr /><hr />

	" . $content;
*/
					}

					db_updateWPwithText($wp_table, $content, $row->wp_postid);

					echo "<p>";
					echo "<strong>Move old page:</strong> \"" . str_replace( "http://www.ffuniverse.nu/shop/", "/", $row->page ) . "\"";
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
				This step doesn't do anything more then that. The next steps also need to be run
				to get correct links and a bit more improved html, etc.
			</p>

			<input type="submit" name="save_wash" value="Move 'em all!" class="btn btn-primary" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>