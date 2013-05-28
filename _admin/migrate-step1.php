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

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

$site_address = $PAGE_form[0]["content"]; // To settings
$site = $site_address; // To settings

$check_links = array();
$check_links[$site] = 0;

$checked_link = "";


$mysql = mysql_connect( $cleaner_dburl, $cleaner_dbuser, $cleaner_dbpass);
if (!$mysql)
	die('Could not connect: ' . mysql_error());
mysql_select_db( $cleaner_dbname, $mysql);
mysql_set_charset("UTF-8");

// At the moment only way to delete data in the table and start anew:
//mysql_query("TRUNCATE `" . $cleaner_table . "`");


// Simple insert into the database, no check if data already is there.
function savepage($site, $buffer)
{
	global $mysql;
	global $PAGE_siteid;
	global $cleaner_table;

	if ( mb_detect_encoding($buffer, "utf-8, iso-8859-1") == "UTF-8" )
		$buffer;
	else
		$buffer = iconv("iso-8859-1", "utf-8", $buffer);

//	echo mb_detect_encoding($buffer, "utf-8, iso-8859-1");
//	exit;

	mysql_query("INSERT INTO " . $cleaner_table . "(page, html, site) VALUES('".$site."', '".addslashes($buffer)."', " . $PAGE_siteid . ")");
	
}

// 
function checklink($link)
{
	global $checked_link;

	// Find every space in URLs, and replcase it with %20
	$space_search = array('/\\s/i');
	$space_replace = array('%20');
	$link = preg_replace($space_search, $space_replace, $link);

	// Find all achors ( #-sign ) and replace them with '\2' (part two of url)
	$square_search = array ('/(.*?)\#(.*?)/i');
	$square_replace = array('\\2');
	$link = preg_replace($square_search, $square_replace, $link);

	// List of page file endings to crawl for
	$endings = array('htm', 'html', 'asp', 'aspx');
	$asd = explode(".", $link);
	$asd = $asd[sizeof($asd)-1];
	$asd = explode("?", $asd);
	$asd = $asd[0];


	if(in_array($asd, $endings))
	{
		$checked_link = $link;
		//echo  "\n" . $checked_link . " ---<br />\n";
		return TRUE;
	}
	else
	{
		return FALSE;
	}

}

// 
function forsites($check_links)
{
	global $site_address;
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
				getsite($k, $site_address);
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

	echo "<p><strong>Requesting:</strong> " . $site . "";
	if ($handle = fopen($site, "r"))
	{
		echo " <span class=\"label label-success\">OK</span>";
	}
	else
	{
		$check_links[$site] = 2;
		return false;
	}
	echo "</p>";
	flush();

	//$handle = stream_get_contents($handle);

	// Different kind of link formats for this site.
	// Example from one of my old sites that had it's navigation in a select > option-list ... >_<
	$search = array ('/\<option value="(.*?)"(.*?)>(.*?)<\/option>/i',
		'/\<a href="(.*?)"(.*?)>(.*?)<\/a>/i');

	for ($i=0; $i<=count($search); $i++)
		$links[$i] = array();

	$pagebuffer = "";
	while(($buffer = fgets($handle)) !== false)
	{
		$pagebuffer .= $buffer;
		if (preg_match($search[0], $buffer, $result[0]))
		{
	#		print_r($result[0]);
			array_push($links[0], $result[0]);
		}
		if (preg_match($search[1], $buffer, $result[1]))
		{
	#		print_r($result[1]);
			array_push($links[1], $result[1]);
		}
	}
	#	print_r($links[0]);
	#	print_r($links[1]);

	$search_links = array('/^\.\.(.*?)/i',
		'/^http\:\/\/(.*)/i');

	for ($i=0; $i<=count($search); $i++)
		for ($j=0; $j<=count($links[$i]); $j++)
		{
			if (!empty($links[$i][$j][1]))
			{
	#			print_r(count($links[$i]));
	#			print_r(gettype($links[0]));
	#			print_r(gettype($links[0][1]));
#				echo "\nasd " . $i ." ". $j . "\n";
#				echo $links[$i][$j][1];
	#			echo $links[$i][$j][1][strlen($site_address)];
				if (preg_match($search_links[0], $links[$i][$j][1], $res_links))
				{
					#			print_r(".." . $res_links );
	#				echo "\n0:\n". $res_links . "\n";
	#				print_r($res_links);
				}
				else if (preg_match($search_links[1], $links[$i][$j][1], $res_links))
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
						if ( (($res_links[0][strlen($site_address)] != ".") ) )
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
#					echo "\n2: " . $links[$i][$j][1] . "\n";
#					print_r($res_links);

					// Don't collect garbage links (only # in the href, or mailto-links)
					if ($links[$i][$j][1][0] != "#" && substr( $links[$i][$j][1], 0, 7 ) != "mailto:")
					{
						$links[$i][$j][1] = $site_address . $links[$i][$j][1];
						echo "2: " . $links[$i][$j][1] . "\n";
#						$link = preg_replace($replace_search, $replace, $links[$i][$j][1]);
						if (checklink($links[$i][$j][1]))
						{
							//echo "\n" . $checked_link . " ---\n";
							if (!array_key_exists($checked_link, $check_links))
							{
								echo " <span class=\"label label-info\">Added</span>";
								$check_links[$checked_link] = 0;
							} else {
								echo " <span class=\"label\">Skipped</span>";
							}
						}
						echo "<br />";
						//flush();
					}
				}
			}
		}

	$check_links[$site] = 1;

	//print_r($check_links);
	echo "<span class=\"badge badge-inverse\">" . count($check_links) . "</span> unique links collected (so far)!";
	flush();

	// Don't save on test
	if (formGet("save_crawl") == "Run crawl") {
		savepage($site, trim($pagebuffer) );
		//echo " <span class=\"label label-success\">Saved</span>";
	} else {
		//echo " <span class=\"label label-warning\">Not saved</span>";
	}

	echo "<br /><br />";


}

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

mysql_close($mysql);


		}

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

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