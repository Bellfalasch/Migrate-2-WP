<?php
	/* Set up template variables */
	$PAGE_step  = 2;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'locate content and strip away repeating html';
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


<?php
		
	if (ISPOST)
	{

		validateForm();

		if (empty($SYS_errors)) {

			$headerNeedle = $PAGE_form[0]["content"];
			$footerNeedle = $PAGE_form[1]["content"];

			if ($headerNeedle === "")
				$headerNeedle = "<!-- HOOK: HEADER -->";

			if ($footerNeedle === "")
				$footerNeedle = "<!-- HOOK: FOOTER -->";

			$headerNeedleLength = mb_strlen($headerNeedle);
			$footerNeedleLength = mb_strlen($footerNeedle);

			// Save the settings, so we can test other values in case of fail
			$PAGE_headerNeedle = $headerNeedle;
			$PAGE_footerNeedle = $footerNeedle;
			$PAGE_headerNeedleLength = $headerNeedleLength;
			$PAGE_footerNeedleLength = $footerNeedleLength;

		?>

			<p>
				<strong>Important!</strong> All html marked in red will be removed when saving these pages back to
				the database. The originally crawled content will be kept in the column 'html' in the table
				'migrate_content', if you need it for reference. This also adds support for you to redo this step
				as many times as you'd like in case you wanna change your needles). The green html will be saved
				into the column 'content'.
			</p>
			<p>
				Please do make extra sure that all code is intact, that the ONLY allowed difference between the
				left and the right side are the colors. Look for bad cuts shopping of the header or the start of the footer!
				If so, just tweak your needles and re-run this step until it gets perfect (found at bottom of the page).
			</p>
			<p>
				<strong>Needle miss:</strong> If any of your needles misses on the current html, the code will expect that
				everything inside the body-tag is content. If not even the body-tag is found, it will try and use the html-tag.
				If that also fail we'll keep the entire html as the content.
			</p>

		<?php

			$result = db_getDataFromSite( array( 'site' => $PAGE_siteid ) );
			if ( isset( $result ) )
			{
				while ( $row = $result->fetch_object() )
				{

					echo "<strong>" . $row->page . "</strong><br />";
					
					$headerStart = 0;

					$body = $row->html;
					$UC_body = mb_strtoupper($body);

					$needleUsed = 'yours';

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
						if ($headerEnd === FALSE) {
							$headerNeedle = "<BODY";
							$headerNeedleLength = mb_strlen($headerNeedle);
							$headerEnd = mb_strpos($UC_body, $headerNeedle);
							
							// Find placement of body closing tag
							$headerEnd = mb_strpos($UC_body, ">", $headerEnd) - 4;

							$needleUsed = 'body';

							if ($headerEnd === FALSE) {
								$headerNeedle = "<HTML";
								$headerNeedleLength = mb_strlen($headerNeedle);
								$headerEnd = mb_strpos($UC_body, $headerNeedle);
								
								// Find placement of html closing tag
								$headerEnd = mb_strpos($UC_body, ">", $headerEnd) - 4;

								$needleUsed = 'html';

								if ($headerEnd === FALSE) {
									$headerNeedle = "";
									$headerNeedleLength = 0;
									$headerEnd = 0;

									$needleUsed = 'none';
								}
							}
						}

						$headerEnd += $headerNeedleLength;
						
						$footerEnd = mb_strlen($body);

						$footerStart = mb_strpos($body, $footerNeedle);

						// If we can't find a needle we need to try other things that usually exists in the html
						if ($footerStart === FALSE) {
							$footerNeedle = "</BODY>";
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
						
						switch($needleUsed) {
							case "yours":
								fn_infobox("", "<strong>Needle hit!</strong> Your Needles were found on this page, awesome!",'');
								break;

							case "body":
								fn_infobox("", "<strong>Needle miss!</strong> Your Needles missed on this page, trying to use the body-tag instead", 'error');
								break;

							case "html":
								fn_infobox("", "<strong>Needle miss!</strong> Your Needles missed on this page, trying to use the html-tag instead", 'error');
								break;

							case "none":
								fn_infobox("", "<strong>Needle miss!</strong> Your Needles missed on this page, trying to use the entire result instead", 'error');
								break;
						}

						$content = $body;

						// Get the right encoding for output:
						$html   = htmlspecialchars($row->html, ENT_QUOTES, "UTF-8");
						$header = htmlspecialchars($header, ENT_QUOTES, "UTF-8");
						$body   = htmlspecialchars($body, ENT_QUOTES, "UTF-8");
						$footer = htmlspecialchars($footer, ENT_QUOTES, "UTF-8");

						// Ugly little presentation of how the needles work on each page.
						echo "<div class='column'><strong>Original code:</strong>";
						echo "	<pre>" . $html . "</pre>";
						echo "</div>";
						echo "<div class='column'><strong>Stripped:</strong>";
						echo "	<pre>";
							echo "<span class='code removed'>" . $header . "</span>";
							echo "<span class='code kept'>" . $body . "</span>";
							echo "<span class='code removed'>" . $footer . "</span>";
						echo "	</pre>";
						// End div comes after we have saved the result ...

					}

					if (formGet("save_needle") == "Run needles") {
						
						echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

						$content = trim( $content );

						db_setContentCode( array(
							'content' => $content,
							'id' => $row->id
						) );

						db_updateStepValue( array(
							'step' => $PAGE_step,
							'id' => $PAGE_siteid
						) );
					
					} else {
					
						echo "<p><strong>Result:</strong> <span class=\"label label-important\">Not saved</span></p>";
					
					}

					echo "</div>"; // TODO: Looks like a bug. Ending the divs coming from inside a if ... investigate
					echo "<hr />";

				}

			}

		}

	}

?>

	<?php
		outputErrors($SYS_errors);
	?>



<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span11">


	<?php

		// Check and see if we can find some stored away html from step 1

		$html = "";

		$result = db_getHtmlFromFirstpage( array( 'site' => $PAGE_siteid ) );
		if ( isset( $result ) )
		{
			$row = $result->fetch_object();
			$html = $row->html;
		}

		if ($html != '') {

	?>
			<p>
				By defining "needles" for finding the start and end of all the content, we can
				strip away all the redundent html we picked up from the crawl in step 1.
			</p>

			<?php outputFormFields(); ?>

			<input type="submit" name="save_needle" value="Run needles" class="btn btn-primary" />

			<input type="submit" name="save_needle" value="Test needles" class="btn" />

	<?php 
		} else {
	?>

			<p>
				Not so fast mister. You haven't even crawled your site yet in <a href="migrate-step1.php">Step 1</a>!
				Do that first, then come back here.
			</p>

	<?php } ?>

		</div>
	</div>

</form>

<?php if (!ISPOST && $html != "") { ?>

	<h2>Your main page html</h2>
	<p>
		Use this output of code extracted from the first page we crawled from your site. Identify
		a div with a unique id, a block of tags, or a text, that's always (always!!!) present in your
		html just before the actual content starts (or as close to it as possible). Do the same for
		were your content ends. All html between these two strings will be kept and used in futher
		steps.
	</p>
	<pre><?= htmlentities( $html, ENT_COMPAT, 'UTF-8', false ) ?></pre>

<?php } ?>


<?php require('_footer.php'); ?>
