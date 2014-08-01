<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 3b';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 3b
			<small>fix old html with PHP tidy-component ("ffucleaner2 - B")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>58<?php } else { ?>45<?php } ?>%;"></div>
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
				
				$content = $row->wash;
				$clean = $content;
				
				// Start replacing old bad markup
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
				
				// Tidy leaves some code that we do not want inside WP, so let's remove it
				$tidy = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"', '', $tidy);
				$tidy = str_replace('    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', '', $tidy);
				$tidy = str_replace('<html xmlns="http://www.w3.org/1999/xhtml">', '', $tidy);
				$tidy = str_replace('<head>', '', $tidy);
				$tidy = str_replace('<title></title>', '', $tidy);
				$tidy = str_replace('</head>', '', $tidy);
				$tidy = str_replace('<body>', '', $tidy);
				$tidy = str_replace('</body>', '', $tidy);
				$tidy = str_replace('</html>', '', $tidy);

				// Regexp that will go and find the style-tag in the beginning of the file and remove it and ALL contents!
				$tidy = preg_replace( array('@<style[^>]*?>.*?</style>@siu'), array(''), $tidy );

				// Some more garbage code from tidy (remove all classes it creates on styled items)
				$tidy = str_replace(' class="c1"', '', $tidy);
				$tidy = str_replace(' class="c2"', '', $tidy);
				$tidy = str_replace(' class="c3"', '', $tidy);
				$tidy = str_replace(' class="c4"', '', $tidy);
				$tidy = str_replace(' class="c5"', '', $tidy);


				$clean = trim($tidy);

				// This tag should be moved out of this step
				// $clean = '<div class="fixbox"><p>Innehåll ej genomgått!</p></div>' . "\n\n" . $clean;


				echo "<div class=\"spalt\"><strong>Original code:</strong>";
				echo "<pre>" . htmlentities( $content, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"spalt\"><strong>Tidy:</strong>";
				echo "<pre class=\"clean\">" . htmlentities( $clean, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				if (formGet("save_tidy") == "Run Tidy") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
					db_MAIN("UPDATE migrate_content SET tidy = '" . $mysqli->real_escape_string($clean) . "' WHERE id = " . $row->id . " LIMIT 1");

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
				This step mainly configures and run the Tidy plugin in PHP. It brings old html version 3 and 4
				into the modern ages of xhtml (it doesn't transform for html5 semantics, but with the right doctype
				this code will also work for html5 pages, most of the time).
			</p>
			<p>
				After this step you will get the oppertunity to clean up what Tidy might have messed
				up, and any other last changes before everything goes straight into Wordpress.
			</p>
			<p>
				<strong>Warning!</strong> This step will overwrite the things done in other Step 3 (Wash and Clean).
				So if you want to start over and re-work your stripped html you must re-start from "Step 3: Wash" and do
				the rest of the Step 3 in order.
			</p>

			<input type="submit" name="save_tidy" value="Run Tidy" class="btn btn-primary" />

			<input type="submit" name="save_tidy" value="Test Tidy" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>