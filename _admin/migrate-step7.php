<?php
	/* Set up template variables */
	$PAGE_step  = 7;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'connect crawled pages with WordPress';
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="row">
		<div class="span8">
			<h2>Important information</h2>
			<p>
				To the left under this text you'll see all the crawled pages with their old URL. Just click the "Connect"-link on any of these pages
				to reload this pages and see the same button on the right side. This side contains all your Wordpress pages. Just click the
				right "Connect"-link here to connect the two pages. Many old pages can be moved to the same single Wordpress-page (the
				other way around is not supported, yet).
				<strong>No data is transfered to WordPress yet!</strong>
			</p>
			<p>
				Connected pages are moved to the bottom of the left table, but not moved at all (only grayed out) in the right table. Thanks
				to this you get a good overview of what old pages remain. Feel free to change any connection by clicking the grayed out connect-button
				again on any page.
			</p>
			<p>
				Select a page once more (with the connect-button) and click the disconnect-button that took its place to remove a page's
				connection to WordPress. This will not remove any page, only the connection between them (so that on Step 8 that page will
				be skipped).
			</p>

			<div class="alert alert-block alert-success">
				<h4>No Save-button!?</h4>
				<?php if ($PAGE_sitestep >= 7) { ?>
				<p>
					When you're ready with all pages you wanna move, manually <a href="migrate-step8.php">go to Step 8</a>.
					Pages left unconnected on the left side in this step will not be moved to Wordpress!
				</p>
				<?php } else { ?>
				<p>
					Save is instant when you click the links. Go ahead, connect some pages!
				</p>
				<?php } ?>
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


// Do the connecting
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
			//$newData_post_title = $row->post_title;
			$newData_guid = $row->guid;

			// Save the selection to the database
			$result2 = db_updateCleanerWithWP( array(
							'id' => $id,
							'name' => $newData_post_name,
							'postid' => $newData_id,
							'guid' => $newData_guid
						) );

			// This step can be done directly after a crawl, but don't update the step counter until step 2 is done
			if ($PAGE_sitestep >= 2) {
				db_updateStepValue( array(
					'step' => $PAGE_step,
					'id' => $PAGE_siteid
				) );
			}

			header('Location: ' . $SYS_pageself);

		} else {
			echo "Could't find any data";
		}

	}

	// Disconnect a page
	if ( qsGet("disconnect") != "" ) {

		$page = qsGet("disconnect");

		$result = db_updateDisconnectPage( array(
						'id' => $page,
						'site' => $PAGE_siteid
					) );

		header('Location: ' . $SYS_pageself);

	}


// The actual code
// ****************************************************************************	

	// Array for all the WP-pages we have listed (don't list again)
	$arrWPidDone = array();

	$pages = array();

	// The crawled content side
	echo '<div class="column"><table>';
	
	$result = db_getWPDataFromSite( array( 'site' => $PAGE_siteid ) );
	if ( isset( $result ) )
	{

		while ( $row = $result->fetch_object() )
		{
			if ($row->wp_postid > 0) {
				if ($row->id == qsGet("connect") ) {
					echo '<tr class="highlighted">';
				} else {
					echo '<tr class="done">';
				}
				array_push($arrWPidDone, $row->wp_postid);
			} else {
				if ($row->id == qsGet("connect") ) {
					echo '<tr class="highlighted">';
				} else {
					echo '<tr>';
				}
			}
			
			if (qsGet("connect") != "") {
				if ( $row->id == qsGet("connect") ) {
					echo "<td><a href=\"?disconnect=" . $row->id . "\" class=\"btn btn-mini btn\">Disconnect</a></td>";
				} else {
					echo "<td>-</td>";
				}
			} else {
				echo "<td><a href=\"?connect=" . $row->id . "\" class=\"btn btn-mini btn-primary\">Connect</a></td>";
			}

			$page = $row->page;
			
			// Add to an array to be used to suggest a page structure if WP is empty
			array_push( $pages, $page );

			echo "<td><a href=\"" . $page . "\" target=\"_blank\">" . str_replace( $PAGE_siteurl, "/", $page ) . "</a><br />";
			echo "&raquo; " . str_replace( $PAGE_sitenewurl, "/", $row->wp_guid . "" ) . "</td>";
			echo '</tr>';
		}

	}
	echo '</table></div>';

//echo $wp_table;
//echo $wp_dbname;
//echo $wp_dburl;

	// The WordPress side
	$result = db_getDataFromWordpress($wp_table);
	
//	var_dump( $result );

	//if ( isset( $result->length ) ) // This is the only one that will see that the result is empty and output a site structure, however it can never see if data is there ... big bug
	if ( isset( $result ) )
	{
		echo '<div class="column"><table>';
		
		while ( $row = $result->fetch_object() )
		{
			if (!in_array($row->ID, $arrWPidDone))
				echo '<tr>';
			else
				echo '<tr class="done">';

			if ( qsGet("connect") != "" )
				echo "<td><a href=\"?connect=" . qsGet("connect") . "&amp;to=" . $row->ID . "\" class=\"btn btn-mini btn-primary\">Connect</a></td>";
			else
				echo "<td>-</td>";

			//echo "<td>" . $row->ID . "</td>";
			//echo "<td>" . $row->post_name . "</td>";
			echo "<td><a href=\"" . $row->guid . "\" target=\"_blank\">" . $row->post_title . "</a><br />";
			echo "( " . str_replace( $PAGE_sitenewurl, "/", $row->guid ) . " )</td>";
			echo '</tr>';

		}

		echo '</table></div>';
		
	} else {
		
		echo '<div class="column">';
		echo "<p><strong>No pages in WordPress!</strong></p>";
		echo "<p>1. Download and install the plugin '<a href=\"http://wordpress.org/extend/plugins/simple-add-pages-or-posts/\">Simple add pages or posts</a>' to WordPress.";
		echo "<p>2. Copy and paste the text bellow and paste it into the plugin to create your site structure in a second!</p>";
		echo "<textarea class=\"large\">";
		
		// Loop out every page from the array of crawled pages
		$pages_length = count($pages);
		for ($i = 0; $i < $pages_length; $i++) {
			
			// Wash URL so we get a good name
			$pagename = $pages[$i];
			$pagename = str_replace( $PAGE_siteurl, '', $pagename ); // Remove domain name
			$pagename = str_replace( array('aspx','asp','php','html','htm'), '', $pagename ); // Removed file endings
			$pagename = str_replace( array('_','-','+'), ' ', $pagename ); // Change some usual chars to replace spaces
			$pagename = str_replace( array('default','index'), '', $pagename ); // Change some usual main page names
			$pagename = str_replace( array('?page='), '', $pagename ); // Remove querystrings (FFU-specific)
			$pagename = str_replace( '.', '', $pagename ); // Remove any dot left from fileendings

			$pagename = strtoupper(mb_substr( $pagename, 0, 1 ) ) . mb_substr( $pagename, 1 ); // Uppercase first letter

			if ( $pagename != '' ) {
				echo $pagename . "\n";
			} else {
				echo "Frontpage" . "\n";
			}
		}
		
		echo "</textarea>";
		echo "</div>";
	}

// END FILE
// ****************************************************************************

?>

		</div>
	</div>


<?php require('_footer.php'); ?>
