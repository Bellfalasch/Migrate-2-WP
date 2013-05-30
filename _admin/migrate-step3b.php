<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 3';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 3b
			<small>clean up old html, manually and with tidy ("ffucleaner2")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>43<?php } else { ?>30<?php } ?>%;"></div>
	</div>

	<style>
		.spalt {
			float: left;
			width: 49%;
			font-size: 7pt;
			overflow: hidden;
		}
		.clean {
			color: green;
		}
		hr {
			clear: both;
		}
		pre {
			font-size: 7pt;
		}
	</style>


<?php

	if (ISPOST)
	{
		
		$result = db_getContentFromSite($PAGE_siteid);
		if ( isset( $result ) )
		{
			while ( $row = $result->fetch_object() )
			{
				echo "<strong>" . $row->page . "</strong><br />";
				
				$content = $row->clean;
				$clean = $content;
				
				// Start replacing old bad markup

				echo "<div class=\"spalt\">";
				//echo "<code><pre class=\"clean\">" . htmlentities( $clean, ENT_COMPAT, 'UTF-8', false ) . "</pre></code>";
				/*
				$tidy = new tidy();
				$tidy->tidy_parse_string($row->content);
				$tidy->cleanRepair();
				$clean = (string)$tidy;
				*/

				// REF: http://tidy.sourceforge.net/docs/quickref.html
				$options = array(
						"output-xhtml" => true,
						"clean" => true,
						"css-prefix" => 'tidy_',
	//					"indent-spaces" => 2,
						"wrap" => 0,
						"indent" => false,
	//					"show-body-only" => true,
	//					"drop-font-tags" => true,
						"drop-empty-paras" => true,
						"hide-comments" => false,
						"join-styles" => true,
	//					"join-classes" => true,
						"word-2000" => true,
						"drop-proprietary-attributes" => true,
						"enclose-text" => true,
						"fix-uri" => true,
						"logical-emphasis" => true,
						"lower-literals" => true,
						"merge-divs" => true,
						"quote-ampersand" => true,
						"break-before-br" => false,
						"sort-attributes" => 'alpha',
	//					"tab-size" => 4,
						"char-encoding" => 'utf8',
						"doctype" => 'omit'
					);
				$tidy = tidy_parse_string($clean, $options,'UTF8');
				//$tidy = tidy_parse_string($row->content, $options);
				tidy_clean_repair($tidy);
				
				// Tidy læmnar kod jag inte tycker om - ta bort:
				$tidy = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"', '', $tidy);
				$tidy = str_replace('    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', '', $tidy);
				$tidy = str_replace('<html xmlns="http://www.w3.org/1999/xhtml">', '', $tidy);
				$tidy = str_replace('<head>', '', $tidy);
				$tidy = str_replace('<title></title>', '', $tidy);
				$tidy = str_replace('</head>', '', $tidy);
				$tidy = str_replace('<body>', '', $tidy);
				$tidy = str_replace('</body>', '', $tidy);
				$tidy = str_replace('</html>', '', $tidy);

				// Tidy skapar stilar på gamla element, så som CENTER, ta bort koden før detta ...
				$tidy = str_replace("<style type=\"text/css\">\n", '', $tidy);
				$tidy = str_replace("/*<![CDATA[*/\n", '', $tidy);
				$tidy = str_replace("/*]]>*/\n", '', $tidy);
				$tidy = str_replace("</style>\n", '', $tidy);

				$tidy = str_replace('p.c1 {text-align: center}', '<!-- p.c1 {text-align: center} -->', $tidy);
				$tidy = str_replace('div.c1 {text-align: center}', '<!-- div.c1 {text-align: center} -->', $tidy);
				$tidy = str_replace('p.c2 {text-align: center}', '<!-- p.c2 {text-align: center} -->', $tidy);
				$tidy = str_replace('div.c1 {margin-left: 2em}', '<!-- div.c1 {margin-left: 2em} -->', $tidy);
				$tidy = str_replace('p.c1 {text-align: left}', '<!-- p.c1 {text-align: left} -->', $tidy);
				
				// Helt onødig formatering
				$tidy = str_replace('pre.c1 {font-size:10pt}', '', $tidy);
				$tidy = str_replace('<pre class="c1">', '<pre>', $tidy);

				$clean = trim($tidy);

				// This tag should be moved out of this step
				// $clean = '<div class="fixbox"><p>Innehåll ej genomgått!</p></div>' . "\n\n" . $clean;

				echo "<pre class=\"clean\">" . htmlentities( $clean, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"spalt\">";
				echo "<pre>" . htmlentities( $content, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				if (formGet("save_wash") == "Run wash") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
					db_MAIN("UPDATE migrate_content SET clean = '" . $mysqli->real_escape_string($clean) . "' WHERE id = " . $row->id . " LIMIT 1");

				} else {
					
					echo "<p><strong>Result:</strong> <span class=\"label label-important\">Not saved</span></p>";
				
				}

				echo "</div>";

				echo "<hr /><br />";
			
			}
		
		}

	}

?>

	<?php
		outputErrors($SYS_errors);
	?>

<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span12">

			<p>
				This step mainly configures and run the Tidy plugin in PHP. It brings old html 3 and 4
				into the modern ages of xhtml.
			</p>
			<p>
				After this step you will get the oppertunity to clean out what Tidy might have messed
				up, and any other last changes before everything goes up in Wordpress.
			</p>

			<input type="submit" name="save_wash" value="Run wash" class="btn btn-primary" />

			<input type="submit" name="save_wash" value="Test wash" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>