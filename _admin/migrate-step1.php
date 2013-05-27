<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 1';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>

<?php

	addField( array(
			"label" => "Crawl this URL:",
			"id" => "crawl_url",
			"type" => "text(5)",
			"description" => "Type in the complete URL to crawl. The crawler will only visit links to that folder, and subfolders.",
			"min" => "2",
			"errors" => array(
							"min" => "Please keep number of character's on at least [MIN].",
						)
		) );

?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 1
			<small>crawl selected site ("ffueater")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>12<?php } else { ?>1<?php } ?>%;"></div>
	</div>


	<?php
		outputErrors($SYS_errors);
	?>


	<div class="row">
		<div class="span7">

<?php

	if (ISPOST)
	{
		
		// Code made by epaaj at ninjaloot.se!
		// Modifications by Bellfalasch

		validateForm();

		if (empty($SYS_errors)) {

			$site_address = $PAGE_form[0]["content"]; // To settings
			$site = $site_address; // To settings

			$check_links = array();
			$check_links[$site] = 0;

			$checked_link = "";

			crawl_page($site_address, 2);

		}

	}

//////////////////////////////////////////////////////////////
// TESTAR NY METOD
//////////////////////////////////////////////////////////////

function validStatus($href) {
	$valid = true;
	/*
	$url = $href;
	$valid = false;
	$resultat = get_headers($url, 1);

	if (is_array($resultat)) {
		if (substr($resultat[0],0,4) == "HTTP") {
			if (substr($resultat[0],-2) == "OK") {
				$valid = true;
			}
		}
	}
	*/
	//print_r(get_headers($url, 1));

	return $valid;
}

function crawl_page($url, $depth = 5)
{
	
	global $site_address;

	static $seen = array();
	if (isset($seen[$url]) || $depth === 0) {
		return;
	}

	$seen[$url] = true;

	$dom = new DOMDocument('1.0');
	@$dom->loadHTMLFile($url);

	$anchors = $dom->getElementsByTagName('a');
	
	foreach ($anchors as $element) {
		
		$href = $element->getAttribute('href');

		//echo $href . "<br />";
		//echo $site_address . "<br />";
		
		if ( substr($href,0,4) != 'http' && substr($href,0,1) != "#" && substr($href,0,3) != "../" ) {
			$href = $site_address . ltrim($href, '/');
			//echo "concat " . $href;
		}

		//$path = '/' . ltrim($href, '/');
		$path = $href;

		// Don't crawl pages outside of this directory
		if ( !strncmp($href, $site_address, strlen($site_address)) && substr($href,0,1) != "#" && substr($href,0,3) != "../" ) {

			// Add filetype crawling
			// ***

			if (validStatus($href)) {
/*
				if (extension_loaded('http')) {
					$href = http_build_url($url, array('path' => $path));
				} else {
					$parts = parse_url($url);
					$href = $parts['scheme'] . '://';
					if (isset($parts['user']) && isset($parts['pass'])) {
						$href .= $parts['user'] . ':' . $parts['pass'] . '@';
					}
					$href .= $parts['host'];
					if (isset($parts['port'])) {
						$href .= ':' . $parts['port'];
					}
					$href .= $path;
				}
*/
				crawl_page($href, $depth - 1);

			}

		}

	}

	//$html = htmlentities( $dom->saveHTML(), ENT_COMPAT, 'UTF-8', false );
	$html = "";

	echo "URL: ", $url, PHP_EOL, "<br />";

	//echo "CONTENT:<br />", PHP_EOL, $html, PHP_EOL, PHP_EOL, "<br /><br />";
}

?>
		</div>

		<div class="span4 offset1">

			<h4>Legend:</h4>
			<p>
				<span class="label label-success">OK</span> - A link has been successfully crawled.
			</p>
			<p>
				<span class="label label-info">Added</span> - Found a new link, it's added to the list of
				pages/links we will collect.
			</p>
			<p>
				<span class="label">Skipped</span> - This link is already crawled, it will not be
				crawled again.
			</p>
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


<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span12">

			<?php outputFormFields(); ?>

			<p>
				Crawling of a website can take very long time, depending on how many pages and links it has.
			</p>

			<input type="submit" name="save_crawl" value="Run crawl" class="btn btn-primary" />

			<input type="submit" name="save_crawl" value="Test crawl" class="btn" />

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>