<?php
	/* Set up template variables */
	$PAGE_step  = 6;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'clean up leftover html and fix semantics';
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

				$html = $row->tidy;

				// If we haven't run the Tidy-step, use the Wash-data
				if (is_null($html)) {
					$html = $row->wash;
				}
				// If we haven't run Wash either, use the Strip-data
				if (is_null($html)) {
					$html = $row->content;
				}

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
				////////////////////////////////////////////////////////

				// Smarter regex removal of valign and align attributes
				$html = preg_replace('/ [v]?align="(center|top|left|right)"/i', "", $html);

				// Remove all vspace and hspace attributes, and their value.
				$html = preg_replace('/ [vh]space="?[\d]*"?/i', "", $html);

				// Remove all width attributes
				$html = preg_replace('/ width="?[\d]*%?"?/i', "", $html);

				// Remove all border, cellpadding, and cellspacing, attributes
				$html = preg_replace('/ border="?[\d]*"?/i', "", $html);
				$html = preg_replace('/ cellpadding="?[\d]*"?/i', "", $html);
				$html = preg_replace('/ cellspacing="?[\d]*"?/i', "", $html);

				// Remove all bgcolor attributes and their content, no matter what setting (if valid hex-color only)
				$html = preg_replace('/ bgcolor="?#[\da-f]{6}"?/i', "", $html);

				// If user adds h1 programatically in WP themes we want to get rid of h1 in content and turn them into h2
				if ( formGet('h1') === 'yes' ) {

					$html = str_replace("<h1>", '<h2>', $html);
					$html = str_replace("</h1>", '</h2>', $html);

				}

				// Some of my pages has a lot of h4 instead of h1, h2 and h3. This ugly one fixes this ;P
				if ( formGet('h4-up') === 'yes' ) {

					$html = str_replace("<h4>", '<h2>', $html);
					$html = str_replace("</h4>", '</h2>', $html);
				}

				// Remove all those extra line breaks left behind in the code (empty p and br-tags)
				if ( formGet('linebreaks') === 'yes' ) {
/*
					$html = str_replace("<br /></p>", '</p>', $html);
					$html = str_replace("<br /></p>", '</p>', $html);
					$html = str_replace("<br /></p>", '</p>', $html);
					$html = str_replace("<p><br />", '<p>', $html);
					$html = str_replace("<p><br />", '<p>', $html); // Starting breaking tag directly inside a p-tag is pretty common
					$html = str_replace("<p>\n<br />", '<p>', $html);
*/

					// Remove blank double newlines
					$html = str_replace("\n\n", '', $html);
					// Remove lines with only tab and then blank new line
					$html = str_replace("\t\n", '', $html);

					// Trying my wings on some Regex for this since the manual replace-strings really really really sucks!
					// Replace three br on separate rows with just two
					$html = preg_replace( '/\s*<br.+[\/]+>\s*<br.+[\/]+>\s*<br.+[\/]+>/i', '<br /><br />', $html );

					// Match </p> <br /> <p> and remove that middle <br /> (will also match <br/> and <br>)
					$html = preg_replace( '/\s*<\/p>\s*<br.+[\/]+>\s*<p>\s*/i', "\n</p>\n<p>\n", $html );

					// Linebreak after each </p> and each <br /> if there is none yet
					$html = preg_replace( '/(<\/p>)(\S)/i', "$1\n$2", $html );
					$html = preg_replace( '/(<br\s+[\/]+>)(\S)/i', "$1\n$2", $html );

					// Do double line breaks after a stack of br-tags for readability
					$html = str_replace("<br /><br />\n", "<br /><br />\n\n", $html);

					// Not too many br's in row please
					//$html = str_replace("<br />\n\r<br />\n\r<br />\n\r", "<br /><br />\n\n", $html);
					//$html = preg_replace( '/\s*<br.+[\/]+>\s*<br.+[\/]+>\s*<br.+[\/]+>\s*/i', "<br /><br />\n", $html );
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
				$html = str_replace("> &nbsp;<", "><", $html);

				// Delete empty tags
				if ( formGet('empty') === 'yes' ) {

					$html = str_replace("<p>\n</p>", '', $html);
					//$html = str_replace("<p><br /></p>\n", "", $html);
					//$html = str_replace("<p><br /></p>", "", $html);
					$html = preg_replace('/<p><br\s?[\/]?><\/p>\s*/i', "", $html);
					$html = str_replace("<p><br /><br /></p>\n", "", $html);
					$html = str_replace("<pre>\n</pre>\n", "", $html);
					$html = str_replace("<pre>\n<br />\n</pre>\n", "", $html);
					$html = str_replace("<pre>\n<br />\n\n</pre>\n", "", $html);
					$html = str_replace("<p>&nbsp;&nbsp;</p>\n", "", $html);
					$html = str_replace("<p>&nbsp;</p>\n", "", $html);
					$html = str_replace("<p></p>\n", "", $html);
					$html = str_replace("<div></div>", "", $html);
					$html = preg_replace('/<br\s?[\/]?>\s*<br\s?[\/]?>\s*<\/p>/i', "</p>", $html);
					$html = preg_replace('/<br\s?[\/]?>\s*<\/p>/i', "</p>", $html);
					$html = preg_replace('/<p><br\s?[\/]?>\s*/i', "<p>", $html);
					$html = preg_replace('/<p><\/p>\s*/i', "", $html);
					$html = preg_replace('/<table>\s*<tr>\s*<td><\/td>\s*<\/tr>\s*<\/table>/i', "", $html);

				}

				// Replace double br-tags with wrapping p-tags instead
				$html = preg_replace('/<br\s?[\/]?>\s*<br\s?[\/]?>\s*/i', "</p>\n<p>", $html);

				// Check if we have a <div id="panel#"> in the first row, then remove it and also the last div.
				$count = 0;
				$html = preg_replace('/^<div id="panel[0-9]+[0-9]?">\s*/i', '', $html, -1, $count);

				if ( $count > 0 ) {
					$html = preg_replace('/<\/div>$/i', '', $html);
				}

				// Turn any remaining div's to p's
				if ( formGet('div2p') === 'yes' ) {
					$html = str_replace("<div>", "<p>", $html);
					$html = str_replace("</div>", "</p>", $html);
				}

				// Clean any fault remaining html
				$html = str_replace("<p><p>", "<p>", $html);
				$html = preg_replace('/<\/p>\s*<\/p>/i', "</p>", $html);
				$html = preg_replace('/<p>$/i', '', $html);

				// All this cleaning might've left more broken code, double check (if setting is active)
				if ( formGet('empty') === 'yes' ) {
					$html = str_replace("<p></p>\n", "", $html);
				}

				// If user adds a page title programatically in WP themes we want to get rid of any h-tag in the start of the text
				if ( formGet('first-h') === 'yes' ) {

					$html = preg_replace('/^<h[1-6]>.*<\/h[1-6]>/i', '', $html);
				}

				// Do some simple indentation - NO, ruins later replaces
				//$html = str_replace('<td', "\t<td", $html);

				// Old images should all be moved to the assets-folder
				// De-activated, it doesn't serve any purpose since new URL's are also wrong and we can't easily get them right - images should be uploaded manually through WordPress.
/*
				if (isset($_POST['images'])) {
					$html = str_replace(' src="b/', ' src="assets/_old/', $html);
					$html = str_replace(' src="pic/', ' src="assets/_old/', $html);
					$html = str_replace(' src="i/', ' src="assets/_old/', $html);
				}
*/

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
				echo "<div class=\"column\"><strong>Original code:</strong>";
				echo "<pre>" . htmlspecialchars($original_html, ENT_QUOTES, "UTF-8") . "</pre>";
				echo "</div>";

				echo "<div class=\"column\"><strong>Cleaned code:</strong>";
				echo "<pre class=\"clean\">" . htmlspecialchars($html, ENT_QUOTES, "UTF-8") . "</pre>";

				// Only save is the "Run"-button is pressed, skip if we're running a Test
				if (formGet("save_clean") == "Run clean") {

					echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";

					db_setCleanCode( array(
						'clean' => $html,
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
		<div class="span11">

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
				<input type="checkbox" name="first-h" value="yes"<?php if (isset($_POST['first-h'])) { ?> checked="checked"<?php } ?> />
				If the first line is any type of h-tag, remove it (you'll use the title-field from WordPress instead)?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="h1" value="yes"<?php if (isset($_POST['h1'])) { ?> checked="checked"<?php } ?> />
				Downgrade every h1-tag in the content to h2?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="h4-up" value="yes"<?php if (isset($_POST['h4-up'])) { ?> checked="checked"<?php } ?> />
				Upgrade every h4-tag to a h2?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="empty" value="yes"<?php if (isset($_POST['empty'])) { ?> checked="checked"<?php } ?> />
				Try to remove most empty tags (div and p)?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="linebreaks" value="yes"<?php if (isset($_POST['linebreaks'])) { ?> checked="checked"<?php } ?> />
				Try to remove clusters of extra linebreaks and br-tags?
			</label>
			<label class="checkbox">
				<input type="checkbox" name="div2p" value="yes"<?php if (isset($_POST['div2p'])) { ?> checked="checked"<?php } ?> />
				Try to change all div-tags into p-tags?
			</label>
			<br />

			<input type="submit" name="save_clean" value="Run clean" class="btn btn-primary" />

			<input type="submit" name="save_clean" value="Test clean" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>
