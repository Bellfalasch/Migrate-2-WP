<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 3';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 3
			<small>wash away old html ("ffucleaner2 - A")</small>
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
				
				$content = $row->content;
				$clean = $content;
				
				// Start replacing old bad markup ... at the moment very manual work =/

				// Dessa taggar ær før summons olika levels, men tidy førstør dem pga att avslutande font-taggen tas bort av mig innan tidy får bita i koden
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>****</B>',         '<span class="stars"><span class="lit">*</span>****</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT></B><B>****</B>',  '<span class="stars"><span class="lit">*</span>****</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>***</B>',         '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>***',             '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT></B><B>***</B>',  '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT><B>***</B>',      '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT>**</B>',         '<span class="stars"><span class="lit">***</span>**</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">****</FONT>*</B>',         '<span class="stars"><span class="lit">****</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*****</FONT></B>',         '<span class="stars"><span class="lit">*****</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">****</FONT></B>',        '<span class="stars"><span class="lit">****</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT>*</B>',        '<span class="stars"><span class="lit">***</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT>*',            '<span class="stars"><span class="lit">***</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>**</B>',        '<span class="stars"><span class="lit">**</span>**</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>***</B>',        '<span class="stars"><span class="lit">*</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT></B><B>***</B>', '<span class="stars"><span class="lit">*</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT></B>',      '<span class="stars"><span class="lit">***</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>*</B>',      '<span class="stars"><span class="lit">**</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>*',          '<span class="stars"><span class="lit">**</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>**</B>',      '<span class="stars"><span class="lit">*</span>**</span>', $clean);
				$clean = str_replace('<B><font size="2" COLOR="Orange">**</font><font size="2">*</font></B>', '<span class="stars"><span class="lit">**</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange" size="2">***</FONT></B>', '<span class="stars"><span class="lit">***</span></span>', $clean);
				$clean = str_replace('<B><font size="2" COLOR="Orange">*</font><font size="2">**</font></B>', '<span class="stars"><span class="lit">*</span>**</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT></B>', '<span class="stars"><span class="lit">**</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>*</B>', '<span class="stars"><span class="lit">*</span>*</span>', $clean);

				// Old footer copyright notice from FF8-site
				$clean = str_replace('<BR><CENTER><IMG SRC="../hr.jpg" WIDTH="430" HEIGHT="2"><FONT size="2" FACE="Arial" COLOR="#bbbbbb"><SMALL><BR>', '', $clean);
				// $clean = str_replace('Site graphics, layout, text and parts of this site is ©opyright to <FONT COLOR="white">&lt;=- The Final Fantasy VIII Universe -=&gt;</FONT><BR>', '', $clean);
				$clean = str_replace('Site graphics, layout, text and parts of this site is &copy;opyright to <FONT COLOR="white">&lt;=- The Final Fantasy VIII Universe -=&gt;</FONT><BR>', '', $clean);
				$clean = str_replace('2000. Unauthorized reproduction or use of content on this site is prohibited. Squaresoft ® and<BR>', '', $clean);
				$clean = str_replace('Final Fantasy, are registered trademarks of Square Co, Ltd.', '', $clean);

				// $clean = str_replace('<FONT COLOR=red>', '<span class="color-red">', $clean);
				// $clean = str_replace('<FONT COLOR=yellow>', '<span class="color-yellow">', $clean);
//				$clean = str_replace('<FONT SIZE=2>', '', $clean);
//				$clean = str_replace('<FONT COLOR=white SIZE=2>', '', $clean);
//				$clean = str_replace('<FONT COLOR="#A0B1D2" SIZE="2" FACE="Arial">', '', $clean);
//				$clean = str_replace('<FONT COLOR="#A0B1D2" SIZE="2">', '', $clean);
				$clean = str_replace(' VALIGN="top"', '', $clean);
				$clean = str_replace(' ALIGN="left"', '', $clean);
				$clean = str_replace(' ALIGN="right"', '', $clean);
				$clean = str_replace(' ALIGN="center"', '', $clean);
				// Can't remove trailing font-tag or Tidy in next step will go nuts
				//$clean = str_replace('</FONT>', '</span>', $clean);

				// Trying to regexp-remove all the remaining font start tags, no matter what they contain
				$clean = preg_replace( array('@<FONT[^>]*?>@siu'), array(''), $clean );

				// Old chunk of code. Easier to remove now than in 3c
				// However ... can't make it work before Tidy without some regex =/
/*
				$clean = str_replace('<TD NAME="helpmenu" WIDTH=142>
  <TABLE CELLPADDING=0 CELLSPACING=0><TR><TD>
	<A HREF="default.asp"><IMG SRC="top/ffiv_logo.jpg" WIDTH=142 HEIGHT=57 ALT="Final Fantasy IV (återvänd till startsidan)" BORDER="0"></A><BR><IMG SRC="trans.gif" WIDTH=1 HEIGHT=1 VSPACE=2 HSPACE=1><BR>
  </TD></TR><TR><TD>
	<TABLE WIDTH="100%" CELLPADDING=1 CELLSPACING=0 STYLE="cursor:help"><TR>
	<TD COLSPAN=3 BGCOLOR="#C2D2EA"><IMG SRC="trans.gif" WIDTH=1 HEIGHT=1></TD>
	</TR><TR><TD BGCOLOR="#C2D2EA" ROWSPAN=2><IMG SRC="trans.gif" WIDTH=1 HEIGHT=1></TD><TD BGCOLOR="#91AACA">', '<div class="sidebar"><h4>Sidebar</h4><p>', $clean );
*/

				$clean = str_replace('<HR WIDTH="750" COLOR="black" NOSHADE>', '<hr />', $clean);
				$clean = str_replace('<CENTER><A HREF=#Upp><B>Upp</B></A></CENTER>', '', $clean);
				$clean = str_replace('<CENTER><IMG SRC="hr.jpg" WIDTH="436" HEIGHT="2"><BR><BR>', '', $clean);
				$clean = str_replace('<CENTER><FONT STYLE="font-size:10pt">', '', $clean);
				$clean = str_replace('WIDTH="15" LENGTH="15"', 'width="15" height="15"', $clean);
				$clean = str_replace('<td WIDTH="6"><FONT COLOR="black">.</font></td>', '', $clean);
				$clean = str_replace('<TD NAME="space2" WIDTH=3><IMG SRC="trans.gif" WIDTH=3 HEIGHT=1></TD>', '', $clean);
				$clean = str_replace('<BR><BR></TD></TR></TABLE>', '', $clean);

				// Donät think any of these are actually needed, so remove them all
				$clean = str_replace('<CENTER>', '', $clean);
				$clean = str_replace('</CENTER>', '', $clean);
				$clean = str_replace('<SMALL>', '', $clean);
				$clean = str_replace('</SMALL>', '', $clean);
				$clean = str_replace('<U>', '', $clean);
				$clean = str_replace('</U>', '', $clean);

				// Old e-mails and names we wanna clean up
				$clean = str_replace('cro075t@tninet.se', 'webmaster@ffuniverse.nu', $clean);
				$clean = str_replace('bobby@westberg.org', 'webmaster@ffuniverse.nu', $clean);
				$clean = str_replace('Bobby Vestberg', 'Bobby Westberg', $clean);

				echo "<div class=\"spalt\"><strong>Original code:</strong>";
				echo "<pre>" . htmlentities( $content, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"spalt\"><strong>Wash:</strong>";
				echo "<pre class=\"clean\">" . htmlentities( $clean, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				if (formGet("save_wash") == "Run wash") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
					db_MAIN("UPDATE migrate_content SET wash = '" . $mysqli->real_escape_string($clean) . "' WHERE id = " . $row->id . " LIMIT 1");

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
				A bunch of manual lines of replacement code will be run. These lines are run
				before we let Tidy try and clean up the code mess. Sometimes this is easier
				than running replacement code after Tidy (but you'll get that option as well).
			</p>
			<p>
				This step can be run multiple times until you feel it's been perfected. Just tune
				the replacements and removals directly in the source code (TODO: use forms or something)
			</p>
			<p>
				<strong>Notice!</strong> Feel free to fine tune settings and code and run this step over and over
				again until you're satisfied. It saves its data in a separate database column.
			</p>

			<input type="submit" name="save_wash" value="Run wash" class="btn btn-primary" />

			<input type="submit" name="save_wash" value="Test wash" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>