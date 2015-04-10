<?php
	/* Set up template variables */
	$PAGE_step  = 5;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'fix old html with PHP tidy-component';
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


<?php

	if (ISPOST)
	{
		
		$result = db_getContentFromSite( array( 'site' => $PAGE_siteid ) );
		if ( isset( $result ) )
		{
			while ( $row = $result->fetch_object() )
			{
				echo "<strong>" . $row->page . "</strong><br />";
				
				$html = $row->wash;

				// If we haven't run Wash, use the Strip-data
				if (is_null($html)) {
					$html = $row->content;
				}

				$original_html = $html;

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
				$html = tidy_parse_string($html, $options,'UTF8');
				//$tidy = tidy_parse_string($row->content, $options);
				tidy_clean_repair($html);
				
				// Tidy leaves some code that we do not want inside WP, so let's remove it
				$html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"', '', $html);
				$html = str_replace('    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">', '', $html);
				$html = str_replace('<html xmlns="http://www.w3.org/1999/xhtml">', '', $html);
				$html = str_replace('<head>', '', $html);
				$html = str_replace('<title></title>', '', $html);
				$html = str_replace('</head>', '', $html);
				$html = str_replace('<body>', '', $html);
				$html = str_replace('</body>', '', $html);
				$html = str_replace('</html>', '', $html);

				// Regexp that will go and find the style-tag in the beginning of the file and remove it and ALL contents!
				$html = preg_replace( '/<style[^>]*?>.*?</style>/siu', '', $html );

				// Some more garbage code from tidy (remove all classes it creates on styled items)
				/*
				$html = str_replace(' class="c1"', '', $html);
				$html = str_replace(' class="c2"', '', $html);
				$html = str_replace(' class="c3"', '', $html);
				$html = str_replace(' class="c4"', '', $html);
				$html = str_replace(' class="c5"', '', $html);
				*/
				$html = preg_replace('/ class="c[12345]+"/i', "", $html);

				$html = trim($html);

				// Generate a view with original versus washed code
				echo "<div class=\"column\"><strong>Original code:</strong>";
				echo "<pre>" . htmlentities( $original_html, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"column\"><strong>Tidy:</strong>";
				echo "<pre class=\"clean\">" . htmlentities( $html, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				// Only save is the "Run"-button is pressed, skip if we're running a Test
				if (formGet("save_tidy") == "Run Tidy") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					db_setTidyCode( array(
						'tidy' => $html,
						'id' => $row->id
					) );

					db_updateStepValue( array(
						'step' => $PAGE_step,
						'id' => $PAGE_siteid
					) );

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
	
		if (!ISPOST) {
	?>

		<div class="alert">
			<h4>Optional step!</h4>
			<p>Doing this step is highly recommended, but it's not mandatory =)</p>
		</div>

	<?php } ?>

<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span12">

			<p>
				This step configures and run the Tidy plugin in PHP. It brings old html version 3 and 4
				into the modern ages of xhtml. It doesn't transform anything to html5 semantics (aside, article, etc),
				but with the right doctype the code will also work for html5 pages.
			</p>
			<p>
				After this step you will get the opportunity to clean up in the code Tidy left
				and any other last changes before everything goes straight into Wordpress.
			</p>
			<p>
				<strong>Notice!</strong> Feel free to fine tune settings and code and run this step over and over
				again until you're satisfied. It saves its data in a separate database column.
			</p>

			<input type="submit" name="save_tidy" value="Run Tidy" class="btn btn-primary" />

			<input type="submit" name="save_tidy" value="Test Tidy" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>