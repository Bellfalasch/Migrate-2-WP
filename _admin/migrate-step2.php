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
		
	// Settings
	// ****************************************************************************	
		$site = 9;


	if (ISPOST)
	{

		validateForm();

		if (empty($SYS_errors)) {

			$headerNeedle = $PAGE_form[0]["content"];
			$footerNeedle = $PAGE_form[1]["content"];

			$result = db_getDataFromSite($site);
			if ( isset( $result ) )
			{
				while ( $row = $result->fetch_object() )
				{

					echo "<strong>" . $row->page . "</strong><br />";
					
					//$header = $header;
					//$footer = $footer;		
					$body = $row->data;

					//$headerNeedle = "<!--<HR WIDTH=\"750\" COLOR=\"black\" NOSHADE><BR>-->";
					$headerNeedleLength = mb_strlen($headerNeedle);
					$headerStart = 0;
					$headerEnd = mb_strpos($body, $headerNeedle, 0);

					// Alla sidor har inte den bortkommenterade hr-taggen (den ær inlagd på varje sida, inte i mallfilerna)
					if ($headerEnd === FALSE) {
						$headerNeedle = "<span STYLE=\"font-size:10pt; color:#656565\"><BR>";
						$headerNeedleLength = mb_strlen($headerNeedle);
						$headerEnd = mb_strpos($body, $headerNeedle, 0);
					}

					$headerEnd += $headerNeedleLength;
					
					//$footerNeedle = "<!-- HOOK: FOOTER -->";
					$footerNeedleLength = mb_strlen($footerNeedle);
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
					
					echo "<div style='float: left; width: 49%; font-size: 7pt; overflow: hidden;'>";
					echo "<code><pre style='color: red;'>" . htmlentities( $header ) . "</pre></code>";
					echo "<code><pre style='color:green;'>" . htmlentities( $body ) . "</pre></code>";
					echo "<code><pre style='color: red;'>" . htmlentities( $footer ) . "</pre></code>";
					echo "</div>";
					echo "<div style='float: left; width: 49%; font-size: 7pt; overflow: hidden;'>";
					echo "<code><pre>" . htmlentities( $row->html ) . "</pre></code>";
					echo "</div>";

					// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
					db_MAIN("UPDATE migrate_content SET content = '" . $mysqli->real_escape_string($body) . "' WHERE id = " . $row->id . " LIMIT 1");
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

			<button type="submit" id="spara" name="spara" class="btn btn-primary">Run upgrade</button>

			<button type="submit" class="btn">Test upgrade</button>

		</div>
	</div>

</form>

	<h2>Your main page html</h2>
	<?php

		$result = db_getHtmlFromFirstpage($site);
		if ( isset( $result ) )
		{
			$row = $result->fetch_object();
			$html = $row->html;
		}

	?>
	<code><pre><?= htmlentities( $html ) ?></pre></code>


<?php require('_footer.php'); ?>