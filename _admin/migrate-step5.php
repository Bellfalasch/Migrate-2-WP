<?php
	/* Set up template variables */
	$PAGE_step  = 5;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'clean up left over html and fix semantics';
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


<?php

	if (ISPOST)
	{
		
		$result = db_getContentFromSite($PAGE_siteid);
		if ( isset( $result ) )
		{
			while ( $row = $result->fetch_object() )
			{
				echo "<strong>" . $row->page . "</strong><br />";
				
				$html = $row->tidy;
				$original_html = $html;

				// Start replacing old bad markup
				// These are tags from some of my own private projects that needed to get improved markup, just delete of you don't need which I guess you don't.
				$html = str_replace('<table>
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
<td>', '<div class="sidebar"><p>', $html);
				$html = str_replace('</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>', '</p></div>', $html);
				$html = str_replace('<a class="fix" href="../rss.aspx"><img class="imgfix" src="../_main/icons/rss.gif" alt="RSS-kanaler" title="RSS-kanaler" class="alignleft" width="36" height="14" /></a> Få alla FFUs (inklusive denna sektors) uppdateringar och nyheter direkt på datorn! Läs mer om <a class="fix" href="../rss.aspx" class="help">FFUs RSS-kanaler</a>.<br />
<br />
<br />', '', $html);
				$html = str_replace('<div id="main_ads_big"><img src="http://www.ffuniverse.nu/_main/layout/annons.gif" alt="Annons" title="Annons" /> <script type="text/javascript">
//<![CDATA[
<!--
                google_ad_client = "pub-7169953545128308";
                google_ad_width = 468;
                google_ad_height = 60;
                google_ad_format = "468x60_as";
                google_ad_type = "text_image";
                google_ad_channel ="";
                google_color_border = "336699";
                google_color_bg = "FFFFFF";
                google_color_link = "0000FF";
                google_color_url = "008000";
                google_color_text = "000000";
                //-->
//]]>
</script> <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>', '', $html);
				$html = str_replace('<div class="area_body">
<p><a href="../rss.aspx"><img src="../_main/icons/rss.gif" alt="RSS-kanaler" title="RSS-kanaler" /></a> Få meddelande om alla FFUs (inklusive denna sidan) uppdateringar, nyheter och kommentarer direkt de inträffar (<a href="../rss.aspx">läs mer</a>)!</p>
</div>', '', $html);

				// Convert old school attributes to classes instead
				$html = str_replace(' width="15" height="15"', ' class="psx_button"', $html);
				$html = str_replace(' width="30" height="15"', ' class="psx_button wide"', $html);
				$html = str_replace(' width="10" height="10"', ' class="materia"', $html);
				$html = str_replace(' height="15" width="15"', ' class="psx_button"', $html);
				$html = str_replace(' height="15" width="30"', ' class="psx_button wide"', $html);
				$html = str_replace(' height="10" width="10"', ' class="materia small"', $html);
				$html = str_replace(' height="20" width="20"', ' class="materia"', $html);
				$html = str_replace(' width="20" height="20"', ' class="materia"', $html);
				$html = str_replace(' height="18" width="15"', ' class="acc_icon"', $html);
				$html = str_replace(' align="left"', ' class="alignleft"', $html);
				$html = str_replace(' align="right"', ' class="alignright"', $html);

				// These are tags from some of my own private projects that needed to get improved markup, just delete of you don't need which I guess you don't.
				$html = str_replace('0=0 0 0 ', '<img src="b/0=0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $html);
				$html = str_replace('0=0 0 ', '<img src="b/0=0.jpg" /><img src="b/0.jpg" />', $html);
				$html = str_replace('0=0 ', '<img src="b/0=0.jpg" />', $html);
				$html = str_replace('0 0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $html);
				$html = str_replace('0 0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $html);
				$html = str_replace('0 0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $html);
				$html = str_replace('0 0 0 ', '<img src="b/0.jpg" /><img src="b/0.jpg" /><img src="b/0.jpg" />', $html);
				$html = str_replace('<img src="b/0=0.jpg" />', '<span class="materia-slots double">0=0</span>', $html);
				$html = str_replace('<img src="b/0.jpg" />', '<span class="materia-slots">0</span>', $html);

				// These are tags from some of my own private projects that needed to get improved markup, just delete of you don't need which I guess you don't.
				$html = str_replace('<span class="color-yellow">*</span>****', '<span class="stars"><span class="lit">*</span>****</span>', $html);
				$html = str_replace('<span class="color-yellow">**</span>***', '<span class="stars"><span class="lit">**</span>***</span>', $html);
				$html = str_replace('<span class="color-yellow">***</span>**', '<span class="stars"><span class="lit">***</span>**</span>', $html);
				$html = str_replace('<span class="color-yellow">****</span>*', '<span class="stars"><span class="lit">****</span>*</span>', $html);
				$html = str_replace('<span class="color-yellow">*****</span>', '<span class="stars"><span class="lit">*****</span></span>', $html);
				$html = str_replace('<span class="color-yellow">****</span>', '<span class="stars"><span class="lit">****</span></span>', $html);
				$html = str_replace('<span class="color-yellow">***</span>*', '<span class="stars"><span class="lit">***</span>*</span>', $html);
				$html = str_replace('<span class="color-yellow">**</span>**', '<span class="stars"><span class="lit">**</span>**</span>', $html);
				$html = str_replace('<span class="color-yellow">*</span>***', '<span class="stars"><span class="lit">*</span>***</span>', $html);
				$html = str_replace('<span class="color-yellow">**</span>', '<span class="stars"><span class="lit">**</span></span>', $html);
				$html = str_replace('<span class="color-yellow">*</span>*', '<span class="stars"><span class="lit">*</span>*</span>', $html);

				$html = str_replace('<strong>***</strong>', '<span class="stars"><span class="lit">***</span></span>', $html);

				// Old school attributes that should be remove (because of universal style now handling them)
				$html = str_replace(' border="0"', '', $html);
				$html = str_replace(' cellspacing="0"', '', $html);
				$html = str_replace(' cellspacing="2"', '', $html);
				$html = str_replace(' cellspacing="3"', '', $html);
				$html = str_replace(' cellpadding="0"', '', $html);
				$html = str_replace(' cellpadding="2"', '', $html);
				$html = str_replace(' cellpadding="5"', '', $html);
				$html = str_replace(' width="1%"', '', $html); // Fiendetabellerna t.ex.
				$html = str_replace(' width="2%"', '', $html);
				$html = str_replace(' width="90%"', '', $html);
				$html = str_replace(' width="100%"', '', $html);
				$html = str_replace(' width="100%"', '', $html);
				$html = str_replace(' width="750"', '', $html);
				$html = str_replace(' width="740"', '', $html);
				$html = str_replace(' width="10"', '', $html);
				$html = str_replace(' width="20"', '', $html);
				$html = str_replace(' width="30"', '', $html);
				$html = str_replace(' width="60"', '', $html);
				$html = str_replace(' width="485"', '', $html);
				$html = str_replace(' border="1"', '', $html);
				$html = str_replace(' align="texttop"', '', $html);
				$html = str_replace(' hspace="9"', '', $html);
				$html = str_replace(' hspace="3"', '', $html);
				$html = str_replace(' vspace="3"', '', $html);
				$html = str_replace(' hspace="2"', '', $html);
				$html = str_replace(' vspace="2"', '', $html);
				$html = str_replace(' align="c"', ' align="center"', $html);
				$html = str_replace(' align="center"', '', $html);
				$html = str_replace(' valign="top"', '', $html);

				// Remove old color styles
				$html = str_replace(' bgcolor="#151515"', '', $html);
				$html = str_replace(' bgcolor="#303030"', '', $html);
				$html = str_replace(' bgcolor="#606060"', '', $html);
				$html = str_replace(' bgcolor="#202020"', '', $html);
				$html = str_replace(' bgcolor="#FFFFFF"', '', $html);
				$html = str_replace(' bgcolor="#A0A0A0"', '', $html);
				$html = str_replace(' bgcolor="#BBBBBB"', '', $html);
				$html = str_replace(' bgcolor="#C0C0C0"', '', $html);
				$html = str_replace(' bgcolor="#E0E0E0"', '', $html);

				// If user adds h1 programatically in WP themes we want to get rid of h1 and turn them into h2 
				if (isset($_POST['h1'])) {

					$html = str_replace("<h1>", '<h2>', $html);
					$html = str_replace("</h1>", '</h2>', $html);

				}

				// Ugly code, but remove myriad of line breaks left behind in the code
				if (isset($_POST['linebreaks'])) {
					
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);
					$html = str_replace("<br />\n<br />", '<br /><br />', $html);

					$html = str_replace("<br /></p>", '</p>', $html);
					$html = str_replace("<br /></p>", '</p>', $html);
					$html = str_replace("<br /></p>", '</p>', $html);
					$html = str_replace("<p><br />", '<p>', $html);
					$html = str_replace("<p><br />", '<p>', $html); // Starting breaking tag directly inside a p-tag is pretty common
					$html = str_replace("<p>\n<br />", '<p>', $html);
					
					// Do double line breaks after a stack of br-tags for readability
					$html = str_replace("<br /><br />\n", "<br /><br />\n\n", $html);
				}

				// Some last manual adjustments of the code (minor)
				$html = str_replace("</tr>\n<tr>", '</tr><tr>', $html);
				$html = str_replace("<table>\n<tr>", '<table><tr>', $html);
				$html = str_replace("</tr>\n</table>", '</tr></table>', $html);
				$html = str_replace("<p>\n", '<p>', $html);
				$html = str_replace("<p>\n", '<p>', $html);
				$html = str_replace("<p>\n", '<p>', $html);
				$html = str_replace("</table>\n", "</table>\n\n", $html);
				$html = str_replace("<pre>\n\n", "<pre>\n", $html);
				$html = str_replace("</pre>\n", "</pre>\n\n", $html);
				$html = str_replace("<td>\n<hr />\n", "<td>", $html);
				$html = str_replace("<h3><span class=\"color-red\"><strong>", "<h3>", $html);
				$html = str_replace("</strong></span></h3>", "</h3>", $html);
				$html = str_replace("<td><span class=\"td_rubrik\">", "<th>", $html);
				$html = str_replace("</span></td>", "</th>", $html);

				// Delete empty tags
				if (isset($_POST['empty'])) {

					$html = str_replace("<p>\n</p>", '', $html);
					$html = str_replace("<p><br /></p>\n", "", $html);
					$html = str_replace("<p><br /><br /></p>\n", "", $html);
					$html = str_replace("<pre>\n</pre>\n", "", $html);
					$html = str_replace("<pre>\n<br />\n</pre>\n", "", $html);
					$html = str_replace("<pre>\n<br />\n\n</pre>\n", "", $html);
					$html = str_replace("<p>&nbsp;&nbsp;</p>\n", "", $html);
					$html = str_replace("<p></p>\n", "", $html);
					$html = str_replace("<div></div>", "", $html);

				}

				// Do some simple indentation - NO, ruins later replaces
				//$html = str_replace('<td', "\t<td", $html);

				// Old images should all be moved to the assets-folder
				if (isset($_POST['images'])) {
					$html = str_replace(' src="b/', ' src="assets/_old/', $html);
					$html = str_replace(' src="pic/', ' src="assets/_old/', $html);
					$html = str_replace(' src="i/', ' src="assets/_old/', $html);
				}

				// These are tags from some of my own private projects that needed to get improved markup, just delete of you don't need which I guess you don't.
				$html = str_replace('<img src="assets/O.jpg" class="psx_button" />', '<span class="psx_button circle">O</span>', $html);
				$html = str_replace('<img src="assets/X.jpg" class="psx_button" />', '<span class="psx_button ex">X</span>', $html);
				$html = str_replace('<img src="assets/F.jpg" class="psx_button" />', '<span class="psx_button square">[]</span>', $html);
				$html = str_replace('<img src="assets/A.jpg" class="psx_button" />', '<span class="psx_button triangle">/\</span>', $html);
				$html = str_replace('<img src="assets/R1.jpg" class="psx_button wide" />', '<span class="psx_button r1">R1</span>', $html);
				$html = str_replace('<img src="assets/R2.jpg" class="psx_button wide" />', '<span class="psx_button r2">R2</span>', $html);
				$html = str_replace('<img src="assets/L1.jpg" class="psx_button wide" />', '<span class="psx_button l1">L1</span>', $html);
				$html = str_replace('<img src="assets/L2.jpg" class="psx_button wide" />', '<span class="psx_button l2">L2</span>', $html);
				$html = str_replace('<img src="assets/upp.jpg" class="psx_button" />', '<span class="psx_button up">Upp</span>', $html);
				$html = str_replace('<img src="assets/vanster.jpg" class="psx_button" />', '<span class="psx_button left">Vänster</span>', $html);
				$html = str_replace('<img src="assets/hoger.jpg" class="psx_button" />', '<span class="psx_button right">Höger</span>', $html);
				$html = str_replace('<img src="assets/ner.jpg" class="psx_button" />', '<span class="psx_button down">Ner</span>', $html);

				$html = trim($html);

				// Generate a view with original versus washed code
				echo "<div class=\"spalt\"><strong>Original code:</strong>";
				echo "<pre>" . htmlentities( $original_html, ENT_COMPAT, 'UTF-8', false ) . "</pre>";
				echo "</div>";

				echo "<div class=\"spalt\"><strong>Cleaned code:</strong>";
				echo "<pre class=\"clean\">" . htmlentities( $html, ENT_COMPAT, 'UTF-8', false ) . "</pre>";

				// Only save is the "Run"-button is pressed, skip if we're running a Test
				if (formGet("save_clean") == "Run clean") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					//db_MAIN("UPDATE migrate_content SET clean = '" . $mysqli->real_escape_string($clean) . "' WHERE id = " . $row->id . " LIMIT 1");

					db_setCleanCode( array(
						'clean' => $mysqli->real_escape_string($html),
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
				A bunch of manual lines of replacement code will be run on the code Tidy has
				cleaned. Smart things to replace here is things like replacing old use of icons as images
				with a span and put a class on it, or things like that. The focus of this step is this more
				or less the semantics of your code.
			</p>
			<p>
				<strong>Notice!</strong> Feel free to fine tune settings and code and run this step over and over
				again until you're satisfied. It saves its data in a separate database column.
			</p>

			<h3>Settings</h3>
			<label class="checkbox">
				<input type="checkbox" name="images" value="yes"<?php if (isset($_POST['images'])) { ?> checked="checked"<?php } ?> />
				Update all images to use the "assets/_old"-folder on the new site?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="h1" value="yes"<?php if (isset($_POST['h1'])) { ?> checked="checked"<?php } ?> />
				Downgrade every h1-tag in the content to h2?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="empty" value="yes"<?php if (isset($_POST['empty'])) { ?> checked="checked"<?php } ?> />
				Try to remove most empty tags (div and p)?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="linebreaks" value="yes"<?php if (isset($_POST['linebreaks'])) { ?> checked="checked"<?php } ?> />
				Try to remove clusters of extra linebreaks and br-tags? 
			</label>
			<br />

			<input type="submit" name="save_clean" value="Run clean" class="btn btn-primary" />

			<input type="submit" name="save_clean" value="Test clean" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>
