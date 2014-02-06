<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 2';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>

<?php

	addField( array(
			"label" => "Header needle:",
			"id" => "needle_head",
			"type" => "text(5)",
			"description" => "Add this to all your pages html: &lt;!-- HOOK: HEADER --&gt; - or define your own easily and always detectable html line that we can use to find where the header ends and content starts.",
			"min" => "2",
			"errors" => array(
							"min" => "Please keep number of character's on at least [MIN].",
						)
		) );

	addField( array(
			"label" => "Footer needle:",
			"id" => "needle_foot",
			"type" => "text(5)",
			"description" => "Suggested: &lt;!-- HOOK: FOOTER --&gt;",
			"min" => "2",
			"errors" => array(
							"min" => "Please keep number of character's on at least [MIN].",
						)
		) );

?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 2
			<small>locate content and strip redundant html ("ffucleaner")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>28<?php } else { ?>15<?php } ?>%;"></div>
	</div>


<?php
		
	if (ISPOST)
	{

		validateForm();

		if (empty($SYS_errors)) {

			$headerNeedle = $PAGE_form[0]["content"];
			$footerNeedle = $PAGE_form[1]["content"];

			if ($headerNeedle == "")
				$headerNeedle = "<!-- HOOK: HEADER -->";

			if ($footerNeedle == "")
				$footerNeedle = "<!-- HOOK: FOOTER -->";

			//$headerNeedle = "<!--<HR WIDTH=\"750\" COLOR=\"black\" NOSHADE><BR>-->";

			$headerNeedleLength = mb_strlen($headerNeedle);
			$footerNeedleLength = mb_strlen($footerNeedle);

			// Save MY settings, so we can test other values in case of fail
			$PAGE_headerNeedle = $headerNeedle;
			$PAGE_footerNeedle = $footerNeedle;
			$PAGE_headerNeedleLength = $headerNeedleLength;
			$PAGE_footerNeedleLength = $footerNeedleLength;

			echo "
			<p>
				<strong>Important!</strong> All html marked in red will be removed when saving these pages back to
				the database. The originally crawled content will be kept in the column 'html' in the table
				'migrate_content', if you need it for reference. This also adds support for you to redo this step
				as many time as you'd like in case you wanna change your needles). The green html will be saved
				into the column 'content'.
			</p>
			<p>
				Please do make extra sure that all code is intact, that is that the ONLY allowed difference between the
				left and the right side are the colors. Look for bad cuts shopping of the header or the start of the footer!
				If so, just tweak your needles and re-run this step until it gets perfect (found at bottom of the page).
			</p>
			<p>
				<strong>Needle miss:</strong> If any of your needles misses on the current html, the code will try and find everything sourrounded by
				the body-tag. If not even the body-tag is found it will try and use the html-tag. If that also is missing
				we'll save the entire content to the database.
			</p>
			";

			$result = db_getDataFromSite($PAGE_siteid);
			if ( isset( $result ) )
			{
				while ( $row = $result->fetch_object() )
				{

					echo "<strong>" . $row->page . "</strong><br />";
					
					$headerStart = 0;

					$body = $row->html;
					$UC_body = mb_strtoupper($body);

					if ( mb_strlen($body) == 0 OR is_null($body) ) {

						echo "<p>Empty body!</p>";

					} else {

						// Reset these, in case they've changed.
						$headerNeedle = $PAGE_headerNeedle;
						$footerNeedle = $PAGE_footerNeedle;
						$headerNeedleLength = $PAGE_headerNeedleLength;
						$footerNeedleLength = $PAGE_footerNeedleLength;
					
						$headerEnd = mb_strpos($body, $headerNeedle);

						// If we can't find a needle we need to try other things that usually exists in the html
						// TODO: Re-make code to be a loop that goes through an array of different predefined needles
						if ($headerEnd === FALSE) {
							$headerNeedle = "<BODY";
							$headerNeedleLength = mb_strlen($headerNeedle);
							$headerEnd = mb_strpos($UC_body, $headerNeedle);
							
							// Find placement of body closing tag
							$headerEnd = mb_strpos($UC_body, ">", $headerEnd) - 4;

							if ($headerEnd === FALSE) {
								$headerNeedle = "<HTML";
								$headerNeedleLength = mb_strlen($headerNeedle);
								$headerEnd = mb_strpos($UC_body, $headerNeedle);
								
								// Find placement of html closing tag
								$headerEnd = mb_strpos($UC_body, ">", $headerEnd) - 4;

								if ($headerEnd === FALSE) {
									$headerNeedle = "";
									$headerNeedleLength = 0;
									$headerEnd = 0;
								}
							}
						}

						$headerEnd += $headerNeedleLength;
						
						$footerEnd = mb_strlen($body);

						$footerStart = mb_strpos($body, $footerNeedle);

						// If we can't find a needle we need to try other things that usually exists in the html
						// TODO: Re-make code to be a loop that goes through an array of different predefined needles
						if ($footerStart === FALSE) {
							$footerNeedle = "</BODY>";
							// TODO: No need for footer needle length!
							// $footerNeedleLength = mb_strlen($footerNeedle);
							$footerStart = mb_strpos($UC_body, $footerNeedle);

							if ($footerStart === FALSE) {
								$footerNeedle = "</HTML>";
								// $footerNeedleLength = mb_strlen($footerNeedle);
								$footerStart = mb_strpos($UC_body, $footerNeedle);

								if ($footerStart === FALSE) {
									$footerNeedle = "";
									// $footerNeedleLength = 0;
									$footerStart = mb_strlen($body);
								}
							}
						}

						if ($headerEnd > 0) {
							$header = mb_substr( $body, $headerStart, $headerEnd );
						} else {
							$header = "";
						}
						
						// TODO: Footer should have a different kind of "> X" than 0 no?
						if ($footerStart < mb_strlen($body) ) {
							$footer = mb_substr( $body, $footerStart, $footerEnd-$footerStart );
						} else {
							$footer = "";
						}
					
						//string substr ( string $string , int $start [, int $length ] )
						//$body = str_replace( $footer, '', $body );
						
						//$line_array_head = preg_split( '/\n/', $header );
						//$lines_head = count( $line_array_head ); 
						//echo 'Header lines: ' . $lines_head . '<br />';
						
						//$line_array_foot = preg_split( '/\n/', $footer );
						//$lines_foot = count( $line_array_foot ); 
						//echo 'Footer lines: ' . $lines_foot . '<br />';

						
						// Start to cut from where the header ends, until the header and body total length is reached (where the footer starts)
						$body = mb_substr( $body, $headerEnd, ( mb_strlen($body) - mb_strlen($header) - mb_strlen($footer) ) );
						
						// Ugly little presentation of how the needles work on each page.
						echo "<div style='float: left; width: 49%; overflow: hidden;'>";
						echo "<pre style='font-size: 7pt;'>";
						echo "<span style='color: red;'>" . htmlentities( $header ) . "</span>";
						echo "<span style='color:green;'>" . htmlentities( $body ) . "</span>";
						echo "<span style='color: red;'>" . htmlentities( $footer ) . "</span>";
						echo "</pre>";
						echo "</div>";
						echo "<div style='float: left; width: 49%; overflow: hidden;'>";
						echo "<pre style='font-size: 7pt;'>" . htmlentities( $row->html ) . "</pre>";

					}

					if (formGet("save_needle") == "Run needles") {
						
						echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

						$body = trim( $body );

						// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
						db_MAIN("UPDATE migrate_content SET content = '" . $mysqli->real_escape_string($body) . "' WHERE id = " . $row->id . " LIMIT 1");
					
					} else {
					
						echo "<p><strong>Result:</strong> <span class=\"label label-important\">Not saved</span></p>";
					
					}

					echo "</div><br />";
					echo "<hr style='clear:both;' /><br />";

				}

			}

		}

	}

?>

	<?php
		outputErrors($SYS_errors);
	?>

<form class="well form-inline" action="" method="post" enctype="multipart/form-data">

	<div class="row">
		<div class="span12">

			<p>
				By defining "needles" for finding the start and end of all the content, we can
				strip away all the redundent html we picked up from the crawl in step 1.
			</p>

			<?php outputFormFields(); ?>

			<input type="submit" name="save_needle" value="Run needles" class="btn btn-primary" />

			<input type="submit" name="save_needle" value="Test needles" class="btn" />

		</div>
	</div>

</form>

<?php if (!ISPOST) { ?>

	<h2>Your main page html</h2>
	<p>
		Use this output of code extracted from the first page we crawled from your site. Identify
		a div with a unique id, a block of tags, or a text, that's always (always!!!) present in your
		html just before the actual content starts (or as close to it as possible). Do the same for
		were your content ends. All html between these two strings will be kept and used in futher
		steps.
	</p>
	<?php

		$result = db_getHtmlFromFirstpage($PAGE_siteid);
		if ( isset( $result ) )
		{
			$row = $result->fetch_object();
			$html = $row->html;
		}

	?>
	<pre><?= htmlentities( $html, ENT_COMPAT, 'UTF-8', false ) ?></pre>

<?php } ?>


<?php require('_footer.php'); ?>