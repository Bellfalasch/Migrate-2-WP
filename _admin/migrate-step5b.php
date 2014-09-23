<?php
	/* Set up template variables */
	$PAGE_step  = 6;
	$PAGE_name  = 'Step 5b';
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'split a page into sub pages';
?>
<?php require('_global.php'); ?>

<?php 

	// Form generator
	addField( array(
		"label" => "Code to split on:",
		"id" => "splitcode",
		"type" => "area(5*6)",
		"description" => "Use '[*]'' as a wildcard, and use '[?]' as the locator (the page will be saved with that name).",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

?>
<?php include('_header.php'); ?>


	<div class="row">
		<div class="span8">

			<div class="alert">
				<h4>Optional step!</h4>
				<p>This step is not mandatory =)</p>
			</div>

			<h2>Split pages!</h2>
			<p>
				This function is extremely powerful when changing your site structure. You select one page to the left, and after that
				get to write a small "needle"-code that we will look for in the code. For each match we will create a new sub-page of the
				selected page. Brilliant for splitting long long pages into sub-pages instead.
			</p>
		</div>
	</div>

<?php

	// Now that we are just before the form starts, we can output any errors we might have pushed into the error-array.
	// Calling this function outputs every error, earlier pushes to the error-array also stops the saving of the form.

	outputErrors($SYS_errors);

?>

	<div class="row">
		<div class="span12">

<?php

// Do the splitting
// ****************************************************************************

	$split_id = qsGet("split");

	if ( $split_id > 0 ) {

		if (ISPOST)
		{
			validateForm();

			if (empty($SYS_errors)) {
				
				echo "<div class='alert alert-block alert-info'><h4>Beware!</h4><p>Early alpha code ...</p></div>";

				// Stupid way of getting all the form data into variables for use to save the data.
				$splitcode = $PAGE_form[0]["content"];
				$split_ORG = $splitcode;

				if ( substr_count( $splitcode, "[?]" ) == 1 ) {

					// I owe a lot to http://regex101.com/ for getting this correct! #regex_noob

					//$splitcode = str_replace('\\', '\\\\', $splitcode);
					$splitcode = str_replace('(', '\(', $splitcode);
					$splitcode = str_replace(')', '\)', $splitcode);
					$splitcode = str_replace('.', '\.', $splitcode);
					$splitcode = str_replace('&', '\&', $splitcode);
					$splitcode = str_replace('/', '\/', $splitcode);
					//$splitcode = str_replace('<', '\<', $splitcode);
					//$splitcode = str_replace('>', '\>', $splitcode);
					$splitcode = str_replace('[*]', '.*', $splitcode);
					$splitcode = str_replace('[?]', '(.*?)', $splitcode);

				} else {

					echo "<div class='alert alert-block alert-error'><h4>Hey now!</h4><p>No [?] added (or more than one), and we need that to find names for the new pages!</p></div>";

				}

			}

			$PAGE_form[0]["content"] = $split_ORG;
		}

	}

?>
	
		<?php if ( $split_id > 0 ) { ?>

<form class="form-horizontal" action="" method="post">

	<div class="row">
		<div class="span7">

	<?php

		// This is the output area, where all the field's html should be generated for empty field's SQL inserts, and already filled in field's SQL updates.
		// The fields data/content is generated in the upper parts of this document. Just call this function to get the html out.

		outputFormFields();

	?>

		</div>


		<div class="span4 offset1">

			<h4>Help</h4>
			<p>
				None to get so far ...
			</p>

		</div>
	</div>


	<div class="form-actions">
		<input type="submit" name="split" value="Run split" class="btn btn-primary" />

		<input type="submit" name="split" value="Test split" class="btn" />
	</div>

</form>

		<?php } ?>
	

<?php

// The actual code
// ****************************************************************************	

	// Array for all the WP-pages we have listed (don't list again)
//	$arrWPidDone = array();

	$result = db_getPagesFromSite( array('site'=>$PAGE_siteid) );

	if ( isset( $result ) )
	{
		echo '<table style="width:30%; float:left;">';

		while ( $row = $result->fetch_object() )
		{
			if ($row->id == $split_id ) {
				echo '<tr style="background-color:black; color:white; font-weight:bold;">';
			} else {
				echo '<tr>';
			}
			
			if ($split_id > 0) {
				echo "<td>-</td>";
			} else {
				echo "<td><a href=\"?split=" . $row->id . "\" class=\"btn btn-mini btn-primary\">Split</a></td>";
			}

			$page = $row->page;

			echo "<td><a href=\"" . $page . "\" target=\"_blank\">" . str_replace( $PAGE_siteurl, "/", $page ) . "</a></td>";
//			echo "<td>&raquo; " . str_replace( $PAGE_sitenewurl, "/", $row->wp_guid . "" ) . "</td>";
			echo '</tr>';
		}

		echo '</table>';
	}


	if ($split_id > 0) {

		$result = db_getHtmlFromPage( array( 
				'site' => $PAGE_siteid,
				'id' => $split_id
			) );
		if ( isset( $result ) )
		{

			$row = $result->fetch_object();

			$codeoutput = $row->clean;
			$baseurl    = $row->page;
			$baseid     = $row->id;

			if (isset($splitcode)) {

				//$clean = preg_replace( "/<!--(.*)-->/Uis", "$0", $codeoutput );

				$arr_content = array();
				$arr_titles  = array();

				$arr_content = preg_split( "/" . $splitcode . "/Ui", $codeoutput ); // Find the content
				preg_match_all( "/" . $splitcode . "/Ui", $codeoutput, $arr_titles ); // Find the boss names

				//var_dump( $arr_content );
				//var_dump( $arr_titles );

				// Pseudo:
				// arr_titles(1) innehåller match-array, början på 0.
				// arr_content(0) är all kod innan första match, arr_content(1) och upp alla matcher

				//exit;

				//$codeoutput = htmlentities( $codeoutput );

				echo "<strong>We found these sub pages:</strong>";
				echo "<pre style='width:67%; float:left; font-size: 7pt;'>";

				$length_arr = count($arr_content);
				$length_title = count($arr_titles[1]);

				for ($i = 0; $i < $length_arr; $i++ ) {

					if ($i <= $length_title && $i+1 < $length_arr) {
					
						$title   = $arr_titles[1][$i]; // Index 0 contains matching area, index 1 the extracted match
						$content = $arr_content[$i+1]; // Skip first content

						// Convert page title into something more URL friendly
						$title_db = trim( strtolower($title) );
						$title_db = str_replace(' ', '-', $title);
						$title_db = str_replace(',', '', $title);
						$title_db = str_replace('.', '', $title);
						$title_db = str_replace('&', '', $title);
						$title_db = str_replace('%', '', $title);
						$title_db = str_replace('#', '', $title);
						$title_db = urlencode( $title );

						$content_db = trim( $content );

						if (formGet("split") == "Run split") {

							$result = db_setNewPage( array(
										'site' => $PAGE_siteid,
										'html' => 'CREATED FROM STEP 5b - not from crawl!',
										'clean' => $content_db,
										'page' => $baseurl . '?' . $title_db
									) );

							if ( $result > 0 ) {

								echo '<div class="alert alert-success"><h4>Save successful</h4><p>New page for ' . $title . ' created, id: ' . $result . '</p></div>';

							}

/*
<a name="\.*"><\/a><\/p>
<table cellpadding="1">
<tr>
<th>(.*?)<\/span> <span class="td_svag">\(.* HP\)<\/th>
<\/tr>
<\/table>

<a name="[*]"></a></p>
<table cellpadding="1">
<tr>
<th>[?]</span> <span class="td_svag">([*] HP)</th>
</tr>
</table>
*/

						} else {

							var_dump( array(
									'site' => $PAGE_siteid,
									'html' => 'CREATED FROM STEP 5b - not from crawl!',
									'clean' => $content_db,
									'page' => $baseurl . '?' . $title_db
								) );

						}

					} else {

						$title = "NO MATCHING TITLE FOR THIS PAGE!!!"; // This should skip the split

						echo '<div class="alert alert-error"><h4>Couldn\'t save</h4><p>New page for following code could not be created!</p></div>';

					}

					echo "<p>";
					echo "<strong>" . $title . "</strong><br />";
					echo htmlentities( $content );
					echo "</p>";

				}

				echo "</pre>";

			} else {

				$codeoutput = htmlentities( $codeoutput );
				echo "<pre style='width:67%; float:left; font-size: 7pt;'>" . $codeoutput . "</pre>";

			}

		}

	}

?>

		</div>
	</div>


<?php require('_footer.php'); ?>
