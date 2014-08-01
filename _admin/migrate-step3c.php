<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 3c';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 3c
			<small>clean up left over html you don't want ("ffucleaner2 - C")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>73<?php } else { ?>60<?php } ?>%;"></div>
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
				
				$content = $row->tidy;
				$clean = $content;

				$tidy = $clean;
				
				// Start replacing old bad markup


				// Right side menus on FFIV-site
				$tidy = str_replace('<table>
<tr>
<td width="142">
<table cellpadding="0" cellspacing="0">
<tr>
<td><a href="default.asp"><img src="top/ffiv_logo.jpg" width="142" height="57" alt="Final Fantasy IV (återvänd till startsidan)" border="0" /></a><br />
<img src="trans.gif" width="1" height="1" vspace="2" hspace="1" /><br /></td>
</tr>
<tr>
<td>
<table width="100%" cellpadding="1" cellspacing="0">
<tr>
<td colspan="3"><img src="trans.gif" width="1" height="1" /></td>
</tr>
<tr>
<td rowspan="2"><img src="trans.gif" width="1" height="1" /></td>
<td>', '<div class="sidebar"><p>', $tidy);
				$tidy = str_replace('</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>', '</p></div>', $tidy);
				$tidy = str_replace('<a class="fix" href="../rss.aspx"><img class="imgfix" src="../_main/icons/rss.gif" alt="RSS-kanaler" title="RSS-kanaler" class="alignleft" width="36" height="14" /></a> Få alla FFUs (inklusive denna sektors) uppdateringar och nyheter direkt på datorn! Läs mer om <a class="fix" href="../rss.aspx" class="help">FFUs RSS-kanaler</a>.<br />
<br />
<br />', '', $tidy);

				// Convert old school attributes to classes instead
				$tidy = str_replace(' width="15" height="15"', ' class="psx_button"', $tidy);
				$tidy = str_replace(' width="30" height="15"', ' class="psx_button wide"', $tidy);
				$tidy = str_replace(' width="10" height="10"', ' class="materia"', $tidy);
				$tidy = str_replace(' height="15" width="15"', ' class="psx_button"', $tidy);
				$tidy = str_replace(' height="15" width="30"', ' class="psx_button wide"', $tidy);
				$tidy = str_replace(' height="10" width="10"', ' class="materia small"', $tidy);
				$tidy = str_replace(' height="20" width="20"', ' class="materia"', $tidy);
				$tidy = str_replace(' width="20" height="20"', ' class="materia"', $tidy);
				$tidy = str_replace(' height="18" width="15"', ' class="acc_icon"', $tidy);
				$tidy = str_replace(' align="left"', ' class="alignleft"', $tidy);
				$tidy = str_replace(' align="right"', ' class="alignright"', $tidy);

				// Anvænd inte bilder med class før psx-knappar utan span-element med text inuti som vi sen døljer och har bg-bild på
				// Samma gæller acc_icon och materia-ikonerna med før den delen. Tænk <i class="icon-xxx"> som bootstrap kør med

				// Prøva att ersætta alla materia-slots gjorda i ASCII till bilder
				// (bara dubbla kan tyværr gøras det enkelt på, men prøvar æven med singla førutsatt att en dubbel finns føre eller efter)
				$tidy = str_replace('0=0 0 0 ', '<img src="b/0=0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				$tidy = str_replace('0=0 0 ', '<img src="b/0=0.jpg" /><img src="b/0.jpg" />', $tidy);
				$tidy = str_replace('0=0 ', '<img src="b/0=0.jpg" />', $tidy);
				$tidy = str_replace('0 0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				$tidy = str_replace('0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				$tidy = str_replace('0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				$tidy = str_replace('0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				//$tidy = str_replace('0 0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				//$tidy = str_replace('0 0 0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				//$tidy = str_replace('0 0 0 0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $tidy);
				$tidy = str_replace('<img src="b/0=0.jpg" />', '<span class="materia-slots double">0=0</span>', $tidy);
				$tidy = str_replace('<img src="b/0.jpg" />', '<span class="materia-slots">0</span>', $tidy);

				// Prøva att få alla level-stjærnor på materia till bilder
				$tidy = str_replace('<span class="color-yellow">*</span>****', '<span class="stars"><span class="lit">*</span>****</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">**</span>***', '<span class="stars"><span class="lit">**</span>***</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">***</span>**', '<span class="stars"><span class="lit">***</span>**</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">****</span>*', '<span class="stars"><span class="lit">****</span>*</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">*****</span>', '<span class="stars"><span class="lit">*****</span></span>', $tidy);

				$tidy = str_replace('<span class="color-yellow">****</span>', '<span class="stars"><span class="lit">****</span></span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">***</span>*', '<span class="stars"><span class="lit">***</span>*</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">**</span>**', '<span class="stars"><span class="lit">**</span>**</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">*</span>***', '<span class="stars"><span class="lit">*</span>***</span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">**</span>', '<span class="stars"><span class="lit">**</span></span>', $tidy);
				$tidy = str_replace('<span class="color-yellow">*</span>*', '<span class="stars"><span class="lit">*</span>*</span>', $tidy);

				$tidy = str_replace('<strong>***</strong>', '<span class="stars"><span class="lit">***</span></span>', $tidy);

				// Old school attributes that should be remove (because of universal style now handling them)
				$tidy = str_replace(' border="0"', '', $tidy);
				$tidy = str_replace(' cellspacing="0"', '', $tidy);
				$tidy = str_replace(' cellspacing="2"', '', $tidy);
				$tidy = str_replace(' cellspacing="3"', '', $tidy);
				$tidy = str_replace(' cellpadding="0"', '', $tidy);
				$tidy = str_replace(' cellpadding="2"', '', $tidy);
				$tidy = str_replace(' cellpadding="5"', '', $tidy);
				$tidy = str_replace(' width="1%"', '', $tidy); // Fiendetabellerna t.ex.
				$tidy = str_replace(' width="2%"', '', $tidy);
				$tidy = str_replace(' width="90%"', '', $tidy);
				$tidy = str_replace(' width="100%"', '', $tidy);
				$tidy = str_replace(' width="100%"', '', $tidy);
				$tidy = str_replace(' width="750"', '', $tidy);
				$tidy = str_replace(' width="740"', '', $tidy);
				$tidy = str_replace(' width="10"', '', $tidy);
				$tidy = str_replace(' width="20"', '', $tidy);
				$tidy = str_replace(' width="30"', '', $tidy);
				$tidy = str_replace(' border="1"', '', $tidy);
				$tidy = str_replace(' align="texttop"', '', $tidy);
				$tidy = str_replace(' hspace="9"', '', $tidy);
				$tidy = str_replace(' hspace="3"', '', $tidy);
				$tidy = str_replace(' vspace="3"', '', $tidy);
				$tidy = str_replace(' align="c"', ' align="center"', $tidy);
				$tidy = str_replace(' align="center"', '', $tidy);
				$tidy = str_replace(' valign="top"', '', $tidy);

				// Remove old color styles
				$tidy = str_replace(' bgcolor="#151515"', '', $tidy);
				$tidy = str_replace(' bgcolor="#303030"', '', $tidy);
				$tidy = str_replace(' bgcolor="#606060"', '', $tidy);
				$tidy = str_replace(' bgcolor="#202020"', '', $tidy);
				$tidy = str_replace(' bgcolor="#FFFFFF"', '', $tidy);
				$tidy = str_replace(' bgcolor="#A0A0A0"', '', $tidy);
				$tidy = str_replace(' bgcolor="#BBBBBB"', '', $tidy);
				$tidy = str_replace(' bgcolor="#C0C0C0"', '', $tidy);
				$tidy = str_replace(' bgcolor="#E0E0E0"', '', $tidy);

				// Plocka ihop radbryten på en o samma rad før att slippa vertikal mardrøm (jættefult kodat ^_____^)
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br />\n<br />", '<br /><br />', $tidy);
				$tidy = str_replace("<br /><br />\n", "<br /><br />\n\n", $tidy); // Dubbel radbrott efter flera br bara før att ge lite læsbarhets-luft i koden

				// Some last manual adjustments of the code
				$tidy = str_replace("</tr>\n<tr>", '</tr><tr>', $tidy);
				$tidy = str_replace("<table>\n<tr>", '<table><tr>', $tidy);
				$tidy = str_replace("</tr>\n</table>", '</tr></table>', $tidy);
				$tidy = str_replace("<br /></p>", '</p>', $tidy);
				$tidy = str_replace("<br /></p>", '</p>', $tidy);
				$tidy = str_replace("<br /></p>", '</p>', $tidy);
				$tidy = str_replace("<p><br />", '<p>', $tidy);
				$tidy = str_replace("<p><br />", '<p>', $tidy); // Ofta kommer dubbla br efter en start-p
				$tidy = str_replace("<p>\n<br />", '<p>', $tidy);
				$tidy = str_replace("<p>\n", '<p>', $tidy);
				$tidy = str_replace("<p>\n", '<p>', $tidy);
				$tidy = str_replace("<p>\n", '<p>', $tidy);
				$tidy = str_replace("</table>\n", "</table>\n\n", $tidy);
				$tidy = str_replace("<pre>\n\n", "<pre>\n", $tidy);
				$tidy = str_replace("</pre>\n", "</pre>\n\n", $tidy);
				$tidy = str_replace("<td>\n<hr />\n", "<td>", $tidy);
				$tidy = str_replace("<h3><span class=\"color-red\"><strong>", "<h3>", $tidy);
				$tidy = str_replace("</strong></span></h3>", "</h3>", $tidy);
				$tidy = str_replace("<td><span class=\"td_rubrik\">", "<th>", $tidy);
				$tidy = str_replace("</span></td>", "</th>", $tidy);

				// Delete empty tags
				$tidy = str_replace("<p><br /></p>\n", "", $tidy);
				$tidy = str_replace("<p><br /><br /></p>\n", "", $tidy);
				$tidy = str_replace("<pre>\n</pre>\n", "", $tidy);
				$tidy = str_replace("<pre>\n<br />\n</pre>\n", "", $tidy);
				$tidy = str_replace("<pre>\n<br />\n\n</pre>\n", "", $tidy);
				$tidy = str_replace("<p>&nbsp;&nbsp;</p>\n", "", $tidy);
				$tidy = str_replace("<p></p>\n", "", $tidy);

				// Do some simple indentation - NO, ruins later replaces
				//$tidy = str_replace('<td', "\t<td", $tidy);

				// Old images should all be moved to the assets-folder
				$tidy = str_replace(' src="b/', ' src="assets/_old/', $tidy);
				$tidy = str_replace(' src="pic/', ' src="assets/_old/', $tidy);
				$tidy = str_replace(' src="i/', ' src="assets/_old/', $tidy);

				// In med nya PSX-ikoner som är mycket bättre, och gör om till span med bakgrundsbild
				$tidy = str_replace('<img src="assets/O.jpg" class="psx_button" />', '<span class="psx_button circle">O</span>', $tidy);
				$tidy = str_replace('<img src="assets/X.jpg" class="psx_button" />', '<span class="psx_button ex">X</span>', $tidy);
				$tidy = str_replace('<img src="assets/F.jpg" class="psx_button" />', '<span class="psx_button square">[]</span>', $tidy);
				$tidy = str_replace('<img src="assets/A.jpg" class="psx_button" />', '<span class="psx_button triangle">/\</span>', $tidy);
				$tidy = str_replace('<img src="assets/R1.jpg" class="psx_button wide" />', '<span class="psx_button r1">R1</span>', $tidy);
				$tidy = str_replace('<img src="assets/R2.jpg" class="psx_button wide" />', '<span class="psx_button r2">R2</span>', $tidy);
				$tidy = str_replace('<img src="assets/L1.jpg" class="psx_button wide" />', '<span class="psx_button l1">L1</span>', $tidy);
				$tidy = str_replace('<img src="assets/L2.jpg" class="psx_button wide" />', '<span class="psx_button l2">L2</span>', $tidy);
				$tidy = str_replace('<img src="assets/upp.jpg" class="psx_button" />', '<span class="psx_button up">Upp</span>', $tidy);
				$tidy = str_replace('<img src="assets/vanster.jpg" class="psx_button" />', '<span class="psx_button left">Vänster</span>', $tidy);
				$tidy = str_replace('<img src="assets/hoger.jpg" class="psx_button" />', '<span class="psx_button right">Höger</span>', $tidy);
				$tidy = str_replace('<img src="assets/ner.jpg" class="psx_button" />', '<span class="psx_button down">Ner</span>', $tidy);

				$clean = trim($tidy);

				// This tag should be moved out of this step
				// $clean = '<div class="fixbox"><p>Innehåll ej genomgått!</p></div>' . "\n\n" . $clean;

				echo "<div class=\"spalt\"><strong>Original code:</strong>";
				echo "<pre>" . htmlentities( $content, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"spalt\"><strong>Clean:</strong>";
				echo "<pre class=\"clean\">" . htmlentities( $clean, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				if (formGet("save_clean") == "Run clean") {

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
				A bunch of manual lines of replacement code will be run on the code Tidy has
				cleaned. Smart things to replace here is things like replacing old use of icons as images
				with a span and put a class on it, or things like that.
			</p>
			<p>
				<strong>Warning!</strong> This step will overwrite the things done in other Step 3 (Wash and Tidy).
				So if you want to start over and re-work your stripped html you must re-start from "Step 3: Wash" and do
				the rest of the Step 3 in order.
			</p>

			<input type="submit" name="save_clean" value="Run clean" class="btn btn-primary" />

			<input type="submit" name="save_clean" value="Test clean" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>