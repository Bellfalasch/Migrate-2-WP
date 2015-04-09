<?php
	/* Set up template variables */
	$PAGE_step  = 4;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'wash away or replace old html';
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

				$html = $row->content;

				$original_html = $html;

				// Start replacing old bad markup ... at the moment very manual work =/

				// These are tags from some of my own private projects that needed to get improved markup, just delete of you don't need which I guess you don't.
				$html = str_replace('<B><FONT COLOR="Orange">*</FONT>****</B>',         '<span class="stars"><span class="lit">*</span>****</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">*</FONT></B><B>****</B>',  '<span class="stars"><span class="lit">*</span>****</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT>***</B>',         '<span class="stars"><span class="lit">**</span>***</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT>***',             '<span class="stars"><span class="lit">**</span>***</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT></B><B>***</B>',  '<span class="stars"><span class="lit">**</span>***</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT><B>***</B>',      '<span class="stars"><span class="lit">**</span>***</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">***</FONT>**</B>',         '<span class="stars"><span class="lit">***</span>**</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">****</FONT>*</B>',         '<span class="stars"><span class="lit">****</span>*</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">*****</FONT></B>',         '<span class="stars"><span class="lit">*****</span></span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">****</FONT></B>',        '<span class="stars"><span class="lit">****</span></span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">***</FONT>*</B>',        '<span class="stars"><span class="lit">***</span>*</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">***</FONT>*',            '<span class="stars"><span class="lit">***</span>*</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT>**</B>',        '<span class="stars"><span class="lit">**</span>**</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">*</FONT>***</B>',        '<span class="stars"><span class="lit">*</span>***</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">*</FONT></B><B>***</B>', '<span class="stars"><span class="lit">*</span>***</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">***</FONT></B>',      '<span class="stars"><span class="lit">***</span></span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT>*</B>',      '<span class="stars"><span class="lit">**</span>*</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT>*',          '<span class="stars"><span class="lit">**</span>*</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">*</FONT>**</B>',      '<span class="stars"><span class="lit">*</span>**</span>', $html);
				$html = str_replace('<B><font size="2" COLOR="Orange">**</font><font size="2">*</font></B>', '<span class="stars"><span class="lit">**</span>*</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange" size="2">***</FONT></B>', '<span class="stars"><span class="lit">***</span></span>', $html);
				$html = str_replace('<B><font size="2" COLOR="Orange">*</font><font size="2">**</font></B>', '<span class="stars"><span class="lit">*</span>**</span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">**</FONT></B>', '<span class="stars"><span class="lit">**</span></span>', $html);
				$html = str_replace('<B><FONT COLOR="Orange">*</FONT>*</B>', '<span class="stars"><span class="lit">*</span>*</span>', $html);

				// Old footer copyright notice from an old site of mine, remove if you like
				$html = str_replace('<BR><CENTER><IMG SRC="../hr.jpg" WIDTH="430" HEIGHT="2"><FONT size="2" FACE="Arial" COLOR="#bbbbbb"><SMALL><BR>', '', $html);
				$html = str_replace('Site graphics, layout, text and parts of this site is &copy;opyright to <FONT COLOR="white">&lt;=- The Final Fantasy VIII Universe -=&gt;</FONT><BR>', '', $html);
				$html = str_replace('2000. Unauthorized reproduction or use of content on this site is prohibited. Squaresoft ® and<BR>', '', $html);
				$html = str_replace('Final Fantasy, are registered trademarks of Square Co, Ltd.', '', $html);

				// Can't remove trailing font-tag or Tidy in next step will go nuts
				//$html = str_replace('</FONT>', '</span>', $html);

				// Trying to regexp-remove all the remaining font start tags, no matter what they contain
				$html = preg_replace( '/<FONT[^>]*?>/siu', '', $html );

				// My pages have a ad from Google on every page, but it's always kept in this div - so just remove it all
				$html = preg_replace( '/<div id="main_ads_big">(.*)<\/div>/Uis', '', $html );

				// Another block of code for RSS-buttons etc on my sites, I'm just removing it all
				// Mistake! Loads of pages use this all over for important information
//				$html = preg_replace( '/<div class="area_body">(.*)<\/div>/Uis', '', $html );

				// Remove all HTML comments and their contents - if setting is activated
				if ( formGet('comments') ) {
					$html = preg_replace( '/<!--(.*)-->/Uis', '', $html );
				}

				// Some markup we need to delete
				$html = str_replace('<A HREF=#Upp><B>Upp</B></A>', '', $html);
				$html = str_replace('<IMG SRC="hr.jpg" WIDTH="436" HEIGHT="2"><BR><BR>', '', $html);
				$html = str_replace('<FONT STYLE="font-size:10pt">', '', $html);
				$html = str_replace('<td WIDTH="6"><FONT COLOR="black">.</font></td>', '', $html);
				$html = str_replace('<TD NAME="space2" WIDTH=3><IMG SRC="trans.gif" WIDTH=3 HEIGHT=1></TD>', '', $html);
				$html = str_replace('<BR><BR></TD></TR></TABLE>', '', $html);
/*
				$html = str_replace(' VALIGN="top"', '', $html);
				$html = str_replace(' ALIGN="left"', '', $html);
				$html = str_replace(' ALIGN="right"', '', $html);
				$html = str_replace(' ALIGN="center"', '', $html);
*/
				// Smarter regex removal of valign and align attributes
				$html = preg_replace('/ [v]?align="(center|top|left|right)"/ig', "", $html);

				// Some markup we can improve
				$html = str_replace('<HR WIDTH="750" COLOR="black" NOSHADE>', '<hr />', $html);

				// This should be handled by Tidy ... let's try without
				// $html = str_replace('WIDTH="15" LENGTH="15"', 'width="15" height="15"', $html);
/*
				// Some markup we potentially could improve, but they were more or less used for "design" and should be removed
				$html = str_replace('<CENTER>', '', $html);
				$html = str_replace('</CENTER>', '', $html);
				$html = str_replace('<SMALL>', '', $html);
				$html = str_replace('</SMALL>', '', $html);
				$html = str_replace('<U>', '', $html);
				$html = str_replace('</U>', '', $html);
*/
				// Old old tags used for design that we now can set with CSS instead. Just removed the tags.
				$html = preg_replace('/<[\/]?center>/ig', "", $html); // [\/]+ removes start and ending tag
				$html = preg_replace('/<[\/]?u>/ig', "", $html);
				$html = preg_replace('/<[\/]?small>/ig', "", $html);

				// Old e-mails and names we wanna clean up
				$html = str_replace('cro075t@tninet.se', 'webmaster@ffuniverse.nu', $html);
				$html = str_replace('bobby@westberg.org', 'webmaster@ffuniverse.nu', $html);
				$html = str_replace('Bobby Vestberg', 'Bobby Westberg', $html);

				$html = trim($html);

				// Generate a view with original versus washed code
				echo "<div class=\"column\"><strong>Original code:</strong>";
				echo "<pre>" . htmlentities( $original_html, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"column\"><strong>Washed code:</strong>";
				echo "<pre class=\"clean\">" . htmlentities( $html, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				// Only save is the "Run"-button is pressed, skip if we're running a Test
				if (formGet("save_wash") == "Run wash") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					db_setWashCode( array(
						'wash' => $html,
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

<form class="well form" action="" method="post">

	<div class="row">
		<div class="span12">

			<p>
				A bunch of manual lines of replacement code will be run. These lines are run
				before we let Tidy try and clean up the code mess. Sometimes this is easier
				than running replacement code after Tidy (but you'll get that option as well).
			</p>
			<p>
				<strong>Notice!</strong> Feel free to fine tune settings in the source code and run this step over and over
				again until you're satisfied. It saves its washed data in a separate database column.
			</p>

			<label class="checkbox">
				<input type="checkbox" name="comments" value="yes"<?php if (isset($_POST['comments'])) { ?> checked="checked"<?php } ?> />
				Remove all HTML-comments and their contents?
			</label>
			<br />

			<input type="submit" name="save_wash" value="Run wash" class="btn btn-primary" />

			<input type="submit" name="save_wash" value="Test wash" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>
