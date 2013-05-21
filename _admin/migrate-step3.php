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
	</style>


<?php

	if (ISPOST)
	{
		
		// Settings
		// ****************************************************************************	
			$site = 9;


		// Code
		// ****************************************************************************	

		$result = db_getContentFromSite($site);
		if ( isset( $result ) )
		{
			while ( $row = $result->fetch_object() )
			{
				echo "<strong>" . $row->page . "</strong><br />";
				
				$content = $row->content;
				$clean = $content;
				
				// Start replacing old bad markup
				// Men prova denna istællet - mycket bættre: http://infohound.net/tidy/
				/*
				$clean = str_replace('<BR>', '<br />', $clean);
				$clean = str_replace('<br>', '<br />', $clean);

				$clean = str_replace('<B>', '<strong>', $clean);
				$clean = str_replace('</B>', '</strong>', $clean);
				$clean = str_replace('<b>', '<strong>', $clean);
				$clean = str_replace('</b>', '</strong>', $clean);
				$clean = str_replace('<I>', '<em>', $clean);
				$clean = str_replace('</I>', '</em>', $clean);
				$clean = str_replace('<i>', '<em>', $clean);
				$clean = str_replace('</i>', '</em>', $clean);
				*/

				// Dessa taggar ær før summons olika levels, men tidy førstør dem pga att avslutande font-taggen tas bort av mig innan tidy får bita i koden
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>****</B>', '<span class="stars"><span class="lit">*</span>****</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT></B><B>****</B>', '<span class="stars"><span class="lit">*</span>****</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>***</B>', '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>***', '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT></B><B>***</B>', '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT><B>***</B>', '<span class="stars"><span class="lit">**</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT>**</B>', '<span class="stars"><span class="lit">***</span>**</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">****</FONT>*</B>', '<span class="stars"><span class="lit">****</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*****</FONT></B>', '<span class="stars"><span class="lit">*****</span></span>', $clean);

				$clean = str_replace('<B><FONT COLOR="Orange">****</FONT></B>', '<span class="stars"><span class="lit">****</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT>*</B>', '<span class="stars"><span class="lit">***</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT>*', '<span class="stars"><span class="lit">***</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>**</B>', '<span class="stars"><span class="lit">**</span>**</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>***</B>', '<span class="stars"><span class="lit">*</span>***</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT></B><B>***</B>', '<span class="stars"><span class="lit">*</span>***</span>', $clean);

				$clean = str_replace('<B><FONT COLOR="Orange">***</FONT></B>', '<span class="stars"><span class="lit">***</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>*</B>', '<span class="stars"><span class="lit">**</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT>*', '<span class="stars"><span class="lit">**</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>**</B>', '<span class="stars"><span class="lit">*</span>**</span>', $clean);

				$clean = str_replace('<B><font size="2" COLOR="Orange">**</font><font size="2">*</font></B>', '<span class="stars"><span class="lit">**</span>*</span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange" size="2">***</FONT></B>', '<span class="stars"><span class="lit">***</span></span>', $clean);
				$clean = str_replace('<B><font size="2" COLOR="Orange">*</font><font size="2">**</font></B>', '<span class="stars"><span class="lit">*</span>**</span>', $clean);

				$clean = str_replace('<B><FONT COLOR="Orange">**</FONT></B>', '<span class="stars"><span class="lit">**</span></span>', $clean);
				$clean = str_replace('<B><FONT COLOR="Orange">*</FONT>*</B>', '<span class="stars"><span class="lit">*</span>*</span>', $clean);

				// span.stars gør all text så att varje bokstav (stjærna) blir exakt X pixlar, och har sen gråa stjærnor som bakgrund (loop-x)
				// span.stars span.lit ærver text-storleken men har tænda stjærnor som bakgrund istællet
				// Bægge døljer texten så bara bilderna syns

				$clean = str_replace('<FONT COLOR=red>', '<span class="color-red">', $clean);
				$clean = str_replace('<FONT COLOR=yellow>', '<span class="color-yellow">', $clean);
				$clean = str_replace('<FONT SIZE=2>', '', $clean); // Tror denna enbart anvænds inom PRE-taggarna, och då kan vi istællet stila det via den sen
				$clean = str_replace('</FONT>', '</span>', $clean);
				$clean = str_replace('<U>', '', $clean);
				$clean = str_replace('</U>', '', $clean);

				$clean = str_replace('<HR WIDTH="750" COLOR="black" NOSHADE>', '<hr />', $clean);
				$clean = str_replace('<CENTER><A HREF=#Upp><B>Upp</B></A></CENTER>', '', $clean);
				$clean = str_replace('WIDTH="15" LENGTH="15"', 'width="15" height="15"', $clean);

				// Center anvænds nog ingenstans meningsfullt, den bara skræpar ner så ta bort helt
				$clean = str_replace('<CENTER>', '', $clean);
				$clean = str_replace('</CENTER>', '', $clean);

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
						"hide-comments" => true,
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

				// ... och byt sedan namn på klasserna tidy skapat så att de matchar klasserna i vår egna stilmall!
				// tidy byter klassnamn mellan filerna, så detta ær inte så lætt tyværr =/
				//$tidy = str_replace(' class="c1"', ' class="center"', $tidy);

				// Gamla e-postadressen ligger fortfarande och skræpar hær och dær
				$tidy = str_replace('cro075t@tninet.se', 'webmaster@ffuniverse.nu', $tidy);
				$tidy = str_replace('bobby@westberg.org', 'webmaster@ffuniverse.nu', $tidy);
				$tidy = str_replace('Bobby Vestberg', 'Bobby Westberg', $tidy);

				// Klasser istællet før hårdkådade attribut
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

				// Hårdkodade attribut som jag kan skriva allmæn CSS før istællet
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
				
				 // Troligen behøvs aldrig dessa nær vi kan køra med CSS istællet
				$tidy = str_replace(' align="center"', '', $tidy);
				$tidy = str_replace(' valign="top"', '', $tidy);

				// Se øver tabeller och deras bakgrundsfærger etc, det borde ju faktiskt egentligen tas bort helt, rakt av liksom. Kolla lite tabeller, så som items-listorna
				// DONE: Vi provar att bara ta bort dem så får vi se =P Bara få sidor anvænder dem och oftast bara før att tabellerna ær så grøtiga och kompakta (dålig grund-css)
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

				// Styr om html-koden lite nær den inte ser ut som jag gillar efter tidy och mina egna kodjusteringar
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

				// Helt radera "tomma" taggar
				$tidy = str_replace("<p><br /></p>\n", "", $tidy);
				$tidy = str_replace("<p><br /><br /></p>\n", "", $tidy);
				$tidy = str_replace("<pre>\n</pre>\n", "", $tidy);
				$tidy = str_replace("<pre>\n<br />\n</pre>\n", "", $tidy);
				$tidy = str_replace("<pre>\n<br />\n\n</pre>\n", "", $tidy);
				$tidy = str_replace("<p>&nbsp;&nbsp;</p>\n", "", $tidy);
				$tidy = str_replace("<p></p>\n", "", $tidy);

				// Busenkel indentation på vissa taggar
				$tidy = str_replace('<td', "\t<td", $tidy);

				// Tagga upp all data som ska "lysa" i admin så man lättare kan korrigera
				//$tidy = str_replace('<img ', '<img class="imgfix" ', $tidy);
				$tidy = str_replace(' class="alignleft"', ' class="alignleft imgfix"', $tidy);
				$tidy = str_replace(' class="alignright"', ' class="alignright imgfix"', $tidy);
				$tidy = str_replace('<a href="', '<a class="fix" href="', $tidy);

				// Uppdatera gammal url till bilder/ikoner att passa nytt upplägg.
				$tidy = str_replace(' src="b/', ' src="assets/', $tidy);

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

				$clean = '<div class="fixbox"><p>Innehåll ej genomgått!</p></div>' . "\n\n" . $clean;

				echo "<code><pre class=\"clean\">" . htmlentities( $clean, ENT_COMPAT, 'UTF-8', false ) . "</pre></code>";
				echo "</div>";

				echo "<div class=\"spalt\">";
				echo "<code><pre>" . htmlentities( $content, ENT_COMPAT, 'UTF-8', false ) . "</pre></code>";
				echo "</div>";

				echo "<hr />";

				// Pusha strippad data tillbaks in i databasen så kan vi køra en cleaner v2 på den strippade koden =)
				db_MAIN("UPDATE migrate_content SET clean = '" . $mysqli->real_escape_string($clean) . "' WHERE id = " . $row->id . " LIMIT 1");
			
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
				A bunch of manual lines of replacement code will be run, as well as the
				Tidy plugin on the server. Out comes clean html for the crawled and stripped
				content.
			</p>

			<button type="submit" id="spara" name="spara" class="btn btn-primary">Run upgrade</button>

			<button type="submit" class="btn">Test upgrade</button>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>