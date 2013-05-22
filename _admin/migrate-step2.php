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

			$headerNeedleLength = mb_strlen($headerNeedle);
			$footerNeedleLength = mb_strlen($footerNeedle);
			$headerStart = 0;

			echo "
			<p>
				<strong>Important!</strong> All html marked in red will be removed when saving these pages back to
				the database. The originally crawled content will be kept in the column 'html' in the table
				'migrate_content', if you need it for reference. The green html will be saved into the column
				'content'.
			</p>
			<p>
				Please do make extra sure that all code is intact, that is that the ONLY allowed difference between the
				left and the right side are the colors. Look for bad cuts ending the header or starting the footer!
			</p>";

			$result = db_getDataFromSite($PAGE_siteid);
			if ( isset( $result ) )
			{
				while ( $row = $result->fetch_object() )
				{

					echo "<strong>" . $row->page . "</strong><br />";
					
					//$header = $header;
					//$footer = $footer;		
					$body = $row->html;

					//$headerNeedle = "<!--<HR WIDTH=\"750\" COLOR=\"black\" NOSHADE><BR>-->";
					// $headerNeedleLength = mb_strlen($headerNeedle);
					// $headerStart = 0;
					$headerEnd = mb_strpos($body, $headerNeedle, 0);

					// Alla sidor har inte den bortkommenterade hr-taggen (den ær inlagd på varje sida, inte i mallfilerna)
					/*
					if ($headerEnd === FALSE) {
						$headerNeedle = "<span STYLE=\"font-size:10pt; color:#656565\"><BR>";
						$headerNeedleLength = mb_strlen($headerNeedle);
						$headerEnd = mb_strpos($body, $headerNeedle, 0);
					}
					*/

					$headerEnd += $headerNeedleLength;
					
					//$footerNeedle = "<!-- HOOK: FOOTER -->";
					// $footerNeedleLength = mb_strlen($footerNeedle);
					$footerStart = mb_strpos($body, $footerNeedle, 0);
					$footerEnd = mb_strlen($body);

					$header = mb_substr( $body, $headerStart, $headerEnd );
					$footer = mb_substr( $body, $footerStart, $footerEnd-$footerStart );
					
					//$body = str_replace( $header, 'BÄRS!', $body );
					
					//string substr ( string $string , int $start [, int $length ] )
					//$body = str_replace( $footer, '', $body );
					
					//$line_array_head = preg_split( '/\n/', $header );
					//$lines_head = count( $line_array_head ); 
					//echo 'Header lines: ' . $lines_head . '<br />';
					
					//$line_array_foot = preg_split( '/\n/', $footer );
					//$lines_foot = count( $line_array_foot ); 
					//echo 'Footer lines: ' . $lines_foot . '<br />';
					
					$body = mb_substr( $body, mb_strlen($header), ( mb_strlen($body) - mb_strlen($footer) - mb_strlen($header) ) );
					$body = trim( $body );
					
					echo "<hr />";
					echo "<div style='float: left; width: 49%; overflow: hidden;'>";
					echo "<pre style='color: red; font-size: 7pt;'><strong>Header:</strong>\n" . htmlentities( $header ) . "</pre>";
					echo "<pre style='color:green; font-size: 7pt;'><strong>Content:</strong>\n" . htmlentities( $body ) . "</pre>";
					echo "<pre style='color: red; font-size: 7pt;'><strong>Footer:</strong>\n" . htmlentities( $footer ) . "</pre>";
					echo "</div>";
					echo "<div style='float: left; width: 49%; font-size: 7pt; overflow: hidden;'>";
					echo "<pre style='font-size: 7pt;'><strong>Source:</strong>\n" . htmlentities( $row->html ) . "</pre>";

					if (formGet("save_needle") == "Run needles") {
						
						echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

						// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
						db_MAIN("UPDATE migrate_content SET content = '" . $mysqli->real_escape_string($body) . "' WHERE id = " . $row->id . " LIMIT 1");
					
					} else {
					
						echo "<p><strong>Result:</strong> <span class=\"label label-important\">Not saved</span></p>";
					
					}

					echo "</div><br />";

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