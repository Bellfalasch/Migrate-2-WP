<?php
	// This page will take a looong time to finish, so remove any timeouts on the server
	set_time_limit(0);
	ini_set('max_execution_time', 0);

	/* Set up template variables */
	$PAGE_step  = 1;
	$PAGE_name  = 'Step ' . $PAGE_step;
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'crawl an entire site\'s html';
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<?php
		outputErrors($SYS_errors);
	?>


	<div class="row">
		<div class="span7">

<?php

// Crawler setup
// ****************************************************************************

	// Code made by epaaj at ninjaloot.se!
	// Modifications by Bellfalasch

	$check_links = array();
	$check_links[$PAGE_siteurl] = 0;
	$checked_link = "";

	// At the moment only way to delete data in the table and start anew:
	//mysql_query("TRUNCATE `" . $cleaner_table . "`");

	// List of file endings on pages to crawl for, fetch from setting
	// Our formGet doesn't tackle post arrays, so need to read it directly
	$fileendings = array();

	if (isset($_POST['filetype'])) {
		$fileendings = $_POST['filetype'];
	}


// Crawler functions
// ****************************************************************************

// Simple insert into the database, no check if data already is there.
function savepage($url, $html)
{
	global $mysqli;
	global $PAGE_siteid;
	global $cleaner_table;

	if ( mb_detect_encoding($html, "utf-8, iso-8859-1") == "UTF-8" )
		$html;
	else
		$html = iconv("iso-8859-1", "utf-8", $html);

//	echo mb_detect_encoding($html, "utf-8, iso-8859-1");
//	exit;

	if ($html != "") {

		// Check if page exists
		$exists = db_getDoesPageExist( array(
						'site' => $PAGE_siteid,
						'page' => $url
					) );

		// Insert or Update?
		if ( isset($exists) ) {

			$row = $result->fetch_object();

			$result = db_setUpdatePage( array(
							'html' => $mysqli->real_escape_string($html),
							'id' => $row->id
						) );

		} else {

			$result = db_setNewPage( array(
							'site' => $PAGE_siteid,
							'html' => $mysqli->real_escape_string($html),
							'page' => $url,
							'clean' => null
						) );
		}

	}
}

function checklink($link)
{
	global $checked_link;

	// Find every space in URLs, and replace it with %20
//	$space_search = array('/\s/i');
//	$space_replace = array('%20');
//	$link = preg_replace($space_search, $space_replace, $link);

	// Simplification of the above:
	$link = str_replace( " ", "%20", $link );

	// Find all achors ( #-sign ) and delete it and everything after
	$matches = array();
	$anchor_search = "/(.*?)#(.*?)/i";
	preg_match($anchor_search, $link, $matches);
	
	// If match found (# found in URL) remove it
	if ( $matches ) {
		$link = $matches[0];
//		echo $matches[0];
	}
//	var_dump($matches);

//	echo $link;

	//var_dump( $endings );

	if ( validfiletype($link) ) {
		$checked_link = $link;
		//echo  "\n" . $checked_link . " ---<br />\n";
		return true;
	} else {
		return false;
	}

}

// Validate file ending (don't add links to files we don't want)
function validfiletype($link)
{
	global $fileendings;

	$filetype = explode(".", $link);
	$filetype = $filetype[sizeof($filetype)-1];
	$filetype = explode("?", $filetype);
	$filetype = $filetype[0];

	if (in_array($filetype, $fileendings) ) {
		return true;
	} else {
		return false;
	}

}

function forsites($check_links)
{
	global $PAGE_siteurl;
	global $check_links;
	global $checked_link;

	$continue = true;

	while($continue)
	{
		$continue = false;
		#		for ($i=0; $i<=count($check_links); $i++)
		foreach ($check_links as $k => $v)
		{
			if ($v == 0)
			{
				getsite($k, $PAGE_siteurl);
				$continue = true;
			}
		}
	}
}

// Request the site we want to crawl
function getsite($site, $site_address)
{
	global $check_links;
	global $checked_link;

	$linklist = array(); // Array to store all the links in

	// Different kind of link formats for this site.
	// Example from one of my old sites that had it's navigation in a select > option-list ... >_<
	$search = array (
		'/\<option value="(.*?)".*>.*<\/option>/i',
		'/ href="(.*?)"/i',
		'/ src="(.*?)"/i',
		'/window\.open\("(.*?)"/i'
	);
	$search_length = count($search);

	// '/src="([^\s"]+)"/iU',
	// '/\<a href="(.*?)"(.*?)>(.*?)<\/a>/i',

/*
	'/\<frame src="(.*?)"(.*?)/i',
	'/\<a(.*)href="(.*?)"(.*?)>(.*?)<\/a>/i',
	'/\<A HREF="(.*?)"(.*?)>(.|\n)+(.*?)(.|\n)+<\/A>/i',
	'/<a\s[^>]*href=([\"\']??)([^\\1 >]*?)\\1[^>]*>(.*)<\/a>/siU',
	'/\<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU',
*/
	// Need help? Check this awesome guide: http://www.the-art-of-web.com/php/parse-links/
	// http://nadeausoftware.com/articles/2007/09/php_tip_how_strip_html_tags_web_page
	// http://www.catswhocode.com/blog/15-php-regular-expressions-for-web-developers

	echo "<p>";
	echo "<strong>Fetching URL:</strong> " . $site . " ";

	// Get a URL's code
	$http_request = fopen($site, "r");

	//print_r($http_response_header);

	// Check HTTP status message, only get OK pages (if setting says so)
	if ($http_request)
	{
		// Check that it says status 200 OK in the header
		if (is_array($http_response_header)) {
			if ( in_array( substr($http_response_header[0],9,1), array("2","3") ) && substr($http_response_header[4],10,12) != "/_error.aspx" || formGet("header") == "" ) {
				echo "<span class=\"label label-success\">OK</span>";
			} else {
				echo "<span class=\"label label-important\">HTTP ERROR</span>";
				$check_links[$site] = 2;
				$search = "";
				//return false;
			}
		} else {
			echo "<span class=\"label label-important\">HTTP ERROR</span>";
			$check_links[$site] = 2;
			$search = "";
			//return false;
		}
		//print_r($http_response_header);
		//exit;
	}
	else
	{
		echo "<span class=\"label label-important\">HTTP ERROR</span>";
		$check_links[$site] = 2;
		$search = "";
		//return false;
	}

//	echo "<br />";
//	echo "The crawler found these links:";
	echo "</p>";

	//$http_request = stream_get_contents($http_request);

	// Create array to store all the links we find, one for each regex-string
//	for ($i=0; $i<=$search_length; $i++) {
//		$linklist = array();
//	}

	// Collect a list of links from our pages and check for duplicates
	$pagebuffer = "";
	
	if ($search_length > 0) { // If we have any search terms

		while ( ($buffer = fgets($http_request)) !== false )
		{

			$pagebuffer .= $buffer; // "while" checks if it worked, add it to the buffer (no idea why it adds it like this)
									// Nevermind, read up on documentation ... fgets apparently reads files/URLs line by line (facepalm)

//			echo "sparat ...";
//			exit;

			//echo "search_length: " . $search_length . "<br />";
		}
		//var_dump($linklist[0]);
		//var_dump($linklist[1]);
		//var_dump($linklist[2]);
		//var_dump($linklist[3]);
	}

	// Search for all the different regex we have
	for ( $i = 0; $i < $search_length; $i++ )
	{

		//exit;

		// Find all matching links in the fetched URL's html
		if ( preg_match_all($search[$i], $pagebuffer, $result) )
		{
	#		print_r($result[0]);
			//if ( $i < count($result[$i]) ) {

			//exit;

			// Add each link we find to our link list
			$result_length = count($result[1]);

			for ( $u = 0; $u < $search_length; $u++ )
			{
				// Don't add duplicates
				if ( !in_array($result[1][$u], $linklist) ) {

					if ( validfiletype($result[1][$u]) ) {

						array_push($linklist, $result[1][$u]); // Preg_match_all returns array like so:
															   // 0 = The matching strings (with href etc), and 1 = only the exact result matches

					}

				}
			
			}
				
			//var_dump($result);

			// TODO: Jag tror felet här är att detta blir fel här så kommande loop får aldrig något resultat i de andra regex:en.
			// Som det är nu körs bara regex 1, endast den. Jag förstår inte varför. 0, 2 och 3 skippas. Kan va att de sparar över varandra.

			// TODO: Testa att inte sätta detta till 1. Jag tror den innehåller en flera dimensioner djup array, och vi därför måste bygga ut loopen nedan ett steg till!

			//echo '$result[$i][1]';
			// var_dump($result[$i][0]);
			// var_dump($result[$i][1]);
			// var_dump($result[$i][2]);
			// var_dump($result[$i][3]);
			//var_dump($linklist);

			//} 
			//exit;

		}
	}

	// Regexp-format on the URL's we'll primarily look for as invalid (not contained in that site).
	$search_links = array(
		'/^\.\.(.*?)/i',
		'/^http\:\/\/(.*?)/i'
	);


	echo "<ol>";


	// For each type of URL format ...
//	for ($i=0; $i<=$search_length; $i++)
//	{

		$links_length = count($linklist);

		// For each link found ...
		for ($j=0; $j<=$links_length; $j++)
		{

			if (!empty($linklist[$j]) )
			{

				// Honeypot, catching bad URLs: (going down one folder)
				if (preg_match($search_links[0], $linklist[$j], $res_links))
				{
					#			print_r(".." . $res_links );
	#				echo "\n0:\n". $res_links . "\n";
	#				print_r($res_links);
				}
				// Honeypot, catching bad URLs: (http-links, most likely leaving the site but check and make sure)
				else if (preg_match($search_links[1], $linklist[$j], $res_links))
				{
					$break = false;
					//echo $res_links[0][strlen($site_address)] . "-" . $res_links[0][strlen($site_address)+1] . "<br />";
					/*
					echo strlen($res_links[0]) . "<br />";
					echo strlen($site_address) . "<br />";
					echo strlen($res_links[0]) . "<br />";
					echo strlen($site_address) . "<br />";
					echo $res_links[0][strlen($site_address)] . "<br />";
					*/
					//if ((strlen($res_links[0]) >= strlen($site_address)) && ((strlen($res_links[0]) >= strlen($site_address)) && (($res_links[0][strlen($site_address)] != ".") && ($res_links[0][strlen($site_address)+1] != "."))))
					if ((strlen($res_links[0]) >= strlen($site_address)) && ((strlen($res_links[0]) >= strlen($site_address)) ) && count($res_links[0] >= strlen($site_address) ) )
					{
						/*
						echo strlen($res_links[0]) . "<br />";
						echo strlen($site_address) . "<br />";
						echo strlen($res_links[0]) . "<br />";
						echo strlen($site_address) . "<br />";
						echo $res_links[0][strlen($site_address)-1] . "<br />";
						*/

						if ( (($res_links[0][strlen($site_address)-1] != ".") ) )
						{
							for ($k=0; $k<strlen($site_address); $k++)
							{
								if ($res_links[0][$k] != $site_address[$k])
								{
	#								echo "TRUE";
									//echo $site_address[$k] . " <span class=\"label label-info\">Link</span><br />";
									$break = true;
									break;
								}
							}
						}
					}
					else
					{
#						echo "TRUE2";
						$break = true;
					}


				// Looks like these are outside of the folder we're looking in =/
				/*
					if (!$break)
					{		
						echo "1: " . $res_links[0] . "";
						#					print_r($res_links);
#						$link = preg_replace($replace_search, $replace, $res_links[0]);
						if (checklink($res_links[0])) {
							if (!array_key_exists($checked_link, $check_links))
							{
								echo " <span class=\"label label-success\">Added</span>";
								$check_links[$checked_link] = 0;
							} else {
								echo " <span class=\"label label-warning\">Skipped</span>";
							}
						}

						echo "<br />";
					}

				*/
				}
				else
				{

				// Match link without regexp, should be valid and inside that site

#					echo "\n2: " . $linklist[$j][1] . "<br />\n";
#					print_r($res_links);

//					echo "count:" . count($linklist[$j]);
/*
					$del_val = "#";
					$key = array_search($del_val, $linklist[$j]);

					if ( $key !== false ) {
						unset($linklist[$j][$key]);
					}
*/
//					$links2_length = count($linklist[$j]);

					
//					for ($y=0; $y<=$links2_length; $y++)
//					{
//					$y = 0;

						var_dump( $linklist[$j] );

						// Don't collect garbage links (only # in the href, or mailto-links)
						if ($linklist[$j] != "#" && substr( $linklist[$j], 0, 7 ) != "mailto:")
						{
// exit;
//								if ( $y > 1 )
//									exit;

							// Create full http links with domain name and all
							$link_full = $site_address . $linklist[$j];

							// Output information (link) to user
							echo "<li><a href=\"" . $link_full . "\" target=\"_blank\">" . $link_full . "</a>\n";

	#						$link = preg_replace($replace_search, $replace, $link_full[1]);
							if (checklink($link_full))
							{
								//echo "\n" . $checked_link . " ---\n";
								if (!array_key_exists($checked_link, $check_links))
								{
									echo " <span class=\"label label-info\">Added</span>";
									$check_links[$checked_link] = 0;
								} else {
									echo " <span class=\"label\">Skipped</span>";
								}
							} else {
								echo " <span class=\"label\">Not a page</span>";
							}
							echo "</li>";

						}
						
						exit;
					
//					} // for $y

				}
			}
		}
//	}

	echo "</ol>";

	$check_links[$PAGE_siteurl] = 1;

	// Close file/URL
	fclose($http_request);

	//print_r($check_links);
	echo "<span class=\"badge badge-inverse\">" . count($check_links) . "</span> unique links collected (so far)!";

	// Only save when Run crawl is pressed (never on Test)
	if (formGet("save_crawl") == "Run crawl") {
		savepage($PAGE_siteurl, trim($pagebuffer) );

		db_updateStepValue( array(
			'step' => $PAGE_step,
			'id' => $PAGE_siteid
		) );
	}

	echo "<br /><br />";

}

// Crawler, caller
// ****************************************************************************

	if (ISPOST)
	{

		forsites($check_links);
		#getsite($site, $site_address);

		//print_r($check_links);
		//echo count($check_links);

		// Don't save on test
		if (formGet("save_crawl") == "Run crawl") {
			echo "<p><strong>Result:</strong> <span class=\"label label-success\">Saved</span></p>";
		} else {
			echo "<p><strong>Result:</strong> <span class=\"label label-important\">Not saved</span></p>";
		}

	}

?>
		</div>

		<div class="span4 offset1">

			<h3>Crawling / scraping</h3>
			<p>
				What we'll do here is go to your start page (the one entered on the Project page) and find any
				link we can identify. We add all these links to a list of items to crawl. After the first page
				is crawled we go to the first link in the list we created. Here we do the same thing as before,
				we find all valid links on that page too but we only add it to the list if it's not already there.
				This way we never crawl a page twice! After all the links in the list have been crawled we're
				done here.
			</p>

			<h3>Legend</h3>
			<h4>Requesting a page:</h4>
			<p>
				<span class="label label-success">OK</span> - A link to a valid page has been successfully crawled.
			</p>
			<p>
				<span class="label label-important">HTTP ERROR</span> - The URL to be crawled didn't give a valid response,
				so it has been skipped. You'd better check this issue up manually.
			</p>
			<h4>Found links:</h4>
			<p>
				<span class="label label-info">Added</span> - Found a new link, it's added to the list of
				pages/links we will collect later.
			</p>
			<p>
				<span class="label">Skipped</span> - This link is already crawled, it will not be
				crawled again.
			</p>
			<p>
				<span class="label">Not a page</span> - This link is not a page with content (images or something similar), it will not be
				crawled.
			</p>
			<h4>Storing HTML:</h4>
			<p>
				<span class="label label-success">Saved</span> - All the found links have been crawled
				and the html saved to your database.
			</p>
			<p>
				<span class="label label-important">Not saved</span> - All the found links have been crawled,
				but none of them have been saved to your database. Click the "Run crawl"-button instead to
				save your data (existing data will be replaced!).
			</p>
		</div>
	</div>


<form class="well form" action="" method="post">

	<div class="row">
		<div class="span12">

			<p>
				The crawler will find any links on the URL you've set up on the settings page "Project" for this Project. Only valid links
				that are located in the same folder/root as the URL you gave will be fetched. After the first page is crawled, the crawler will
				continue to follow every link it can find.
			</p>
			<p>
				We will crawl <strong><?= $PAGE_siteurl ?></strong> for you.
			</p>

			<div class="row">
				<div class="span5">
					<h4>Fetch these filetypes:</h4>
					
					<?php
						// Valid file endings to crawl
						$optionArray = array("aspx","asp","htm","html");
						if (isset($_POST['filetype'])) {
							$optionArray = $_POST['filetype'];
						}
					?>
					<label><input type="checkbox" name="filetype[]" value="aspx"<?php if (in_array("aspx",$optionArray)) { ?> checked="checked"<?php } ?> /> aspx</label>
					<label><input type="checkbox" name="filetype[]" value="asp"<?php if (in_array("asp",$optionArray)) { ?> checked="checked"<?php } ?> /> asp</label>
					<label><input type="checkbox" name="filetype[]" value="html"<?php if (in_array("html",$optionArray)) { ?> checked="checked"<?php } ?> /> html</label>
					<label><input type="checkbox" name="filetype[]" value="htm"<?php if (in_array("htm",$optionArray)) { ?> checked="checked"<?php } ?> /> htm</label>
				</div>

				<div class="span5 offset1">
					<h4>Perform HTTP-status check:</h4>
					<label><input type="checkbox" name="header" value="yes"<?php if (isset($_POST['header'])) { ?> checked="checked"<?php } ?> /> Yes! (skip all pages giving errors)</label>
				</div>
			</div>

			<p class="text-error">
				Crawling of a website can take a very long time, depending on how many pages and links it has.
			</p>

			<input type="submit" name="save_crawl" value="Run crawl" class="btn btn-primary" />

			<input type="submit" name="save_crawl" value="Test crawl" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>