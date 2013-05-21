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
	$site = 9;
	$guide = "ff9";
	$new_site = "http://guide.ffuniverse.nu/" . $guide . "/";


// The actual code
// ****************************************************************************	

	// Öppna alla sidor i ffucleaner som har kopplats till WP
	$result = db_getWPDataFromSite2($site);
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
					$content = $WProw->post_content . "

<hr /><hr /><hr />

" . $content;
				}

				db_updateWPwithText($wp_table, $content, $row->wp_postid);
				
				echo $content;
				echo '<div style="background-color:#bbb;">PAGEBREAKER</div>';

			}


		}

	}

// END FILE
// ****************************************************************************

?>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>