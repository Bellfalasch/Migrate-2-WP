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


<?php

	if (ISPOST)
	{
		
		// Code made by epaaj at ninjaloot.se!
		// Modifications by Bellfalasch

		validateForm();

		var_dump($PAGE_form);

		if (empty($SYS_errors)) {

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

/*
 TODO:
 * Vad är skillnaden mellan två nedre variablerna?
 * Skippa mailto-länkar, de som startar på det, inte bara min adress
 * URLer och site id borde nog lätt in i egna settings (eventuellt egen project-tabell)
 * Stöd till aspx-filer, och möjliga andra formater som man kan tänkas behöva (också till settings?)
 */

$site_address = $PAGE_form[0]["content"]; // "http://www.x.y/"; // To settings
$site = $site_address . "default.asp"; // To settings
$SITEID = 9; // To settings, and database

$check_links = array();
$check_links[$site] = 0;

$checked_link = "";


$mysql = mysql_connect( $cleaner_dburl, $cleaner_dbuser, $cleaner_dbpass);
if (!$mysql)
	die('Could not connect: ' . mysql_error());
mysql_select_db( $cleaner_dbname, $mysql);

// At the moment only way to delete data in the table and start anew:
//mysql_query("TRUNCATE `" . $cleaner_table . "`");

function savepage($site, $buffer)
{
	global $mysql;
	mysql_query("INSERT INTO " . $cleaner_table . "(page, html, site) VALUES('".$site."', '".addslashes($buffer)."', " . $SITEID . ")");
	
}

function checklink($link)
{
	global $checked_link;

	$space_search = array('/\\s/i');
	$space_replace = array('%20');
	$link = preg_replace($space_search, $space_replace, $link);

	$square_search = array ('/(.*?)\#(.*?)/i');
	$square_replace = array('\\2');
	$link =preg_replace($square_search, $square_replace, $link);

	$endings = array('htm', 'html', 'asp');
	$asd = explode(".", $link);
	$asd = $asd[sizeof($asd)-1];
	$asd = explode("?", $asd);
	$asd = $asd[0];

	if(in_array($asd, $endings))
	{
		$checked_link = $link;
		echo  "\n" . $checked_link . " ---\n";
		return TRUE;
	}
	else
	{
		return FALSE;
	}

}

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

function getsite($site, $site_address)
{
	global $check_links;
	global $checked_link;

	echo "\n".$site;
	if ($handle = fopen($site, "r"))
	{
		echo "OK";
	}
	else
	{
		$check_links[$site] = 2;
		return false;
	}

	//$handle = stream_get_contents($handle);

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
	#				echo $res_links[0][strlen($site_address)] . $res_links[0][strlen($site_address)+1] . "\n";
					if ((strlen($res_links[0]) >= strlen($site_address)) && ((strlen($res_links[0]) >= strlen($site_address)) && (($res_links[0][strlen($site_address)] != ".") && ($res_links[0][strlen($site_address)+1] != "."))))
					{
						for ($k=0; $k<strlen($site_address); $k++)
						{
							if ($res_links[0][$k] != $site_address[$k])
							{
#								echo "TRUE";
								$break = true;
								break;
							}
						}
					}
					else
					{
#						echo "TRUE2";
						$break = true;
					}

					if (!$break)
					{		
						echo "1: " . $res_links[0] . "\n";
						#					print_r($res_links);
#						$link = preg_replace($replace_search, $replace, $res_links[0]);
						if (checklink($res_links[0]))
							if (!array_key_exists($checked_link, $check_links))
							{
								echo " NEW ";
								$check_links[$checked_link] = 0;
							}
					}
				}
				else
				{
#					echo "\n2: " . $links[$i][$j][1] . "\n";
#					print_r($res_links);
					if ($links[$i][$j][1][0] != "#" && $links[$i][$j][1] != "mailto:test@test.se")
					{
						$links[$i][$j][1] = $site_address . $links[$i][$j][1];
						echo "2: " . $links[$i][$j][1] . "\n";
#						$link = preg_replace($replace_search, $replace, $links[$i][$j][1]);
						if (checklink($links[$i][$j][1]))
						{
							echo "\n" . $checked_link . " ---\n";
							if (!array_key_exists($checked_link, $check_links))
							{
								$check_links[$checked_link] = 0;
							}
						}
					}
				}
			}
		}

	$check_links[$site] = 1;
	savepage($site, $pagebuffer);

	print_r($check_links);
	echo count($check_links);


}

forsites($check_links);
#getsite($site, $site_address);

print_r($check_links);
echo count($check_links);

mysql_close($mysql);


		}

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

	}

?>

	<div class="page-header">
		<h1>
			Step 1
			<small>crawl selected site</small>
		</h1>
	</div>

	<?php
		outputErrors($SYS_errors);
	?>

<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span12">

			<?php outputFormFields(); ?>

			<p>
				* Check current data (with view of it)<br />
				* Crawl site<br />
				* Able to re-crawl site
			</p>

			<button type="submit" id="spara" name="spara" class="btn btn-primary">Run crawl</button>

			<button type="submit" class="btn">Test crawl</button>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>