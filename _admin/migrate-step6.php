<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 6';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


	<div class="page-header">
		<h1>
			Step 6
			<small>replace old incorrect links ("ffu_replacer")</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>88<?php } else { ?>75<?php } ?>%;"></div>
	</div>

<form class="well form-inline" action="" method="post">

	<div class="row">
		<div class="span12">

<?php

/*
	WordPress data ser ut såhär: Tabellen wp_posts har kolumnen “post_content” för sin html-kod, och 
	kolumnen “post_title” för titel (skall inte ändras). Kolumnen “post_status” skall vara “publish”, 
	“post_type” skall vara “page” eller “ffu_characters” (eller annan CPT). “post_name” innehåller 
	url/slug till sidan, och kan användas för att underlätta mappningen.

*/

// Settings
// ****************************************************************************	
	$site = 9;
	$guide = "ff9";
	$new_site = "http://guide.ffuniverse.nu/" . $guide . "/";
	$wp_table = $wp_table;


// The actual code
// ****************************************************************************	

	/*
		* Öppna upp Wordpress och ersätt all data i hela wp_post där med ny länkarna.
		* Loopa först igenom allt i ffucleaner med länkat innehåll.
		* Behåll hash-taggar för oftast har a name-taggen följt med i flytten.

		Exempel på hur det ser ut i Wordpress nu:

		<a class="fix" href="event4.asp">Event Square&nbsp;</a><br /><br />

		<a class="fix" href="round4.asp">Round Square</a><br /><br />

		<a class="fix" href="skiva1.asp">Skiva 1</a> | - | <a class="fix" href="del1.asp">del 2</a>

		<a class="fix" href="vapen.asp#cloud">ULTIMA WEAPON</a>
		
		Alla hans <a class="fix" href="limit.asp#A">limit</a> och hur man får hans bästa.

	*/


	// Öppna alla sidor i ffucleaner som har kopplats till WP
	$result = db_getDataFromSite($site);

	if ( isset( $result ) ) {

		while ( $row = $result->fetch_object() ) {
			
			$newlink = $row->wp_guid;
			$oldlink = $row->page;

			$mapparArr = explode('/', $oldlink);
			$fil = $mapparArr[count($mapparArr) - 1];
			$mapp = $mapparArr[count($mapparArr) - 2];

			$newlink = str_replace('http://guide.ffuniverse.nu','',$newlink);

			echo "Byt länkar från '" . $fil . "' till '" . $newlink . "' - ";


			// Ta samtidigt bort fix-classen om den finns:
			$fixWP = db_updateWPwithNewLinks($wp_table, '<a class="fix" href="' . $fil, '<a href="' . $newlink);
			
			// Alla har kanske inte fixklassen, uppdatera dem med:
			$fixWP2 = db_updateWPwithNewLinks($wp_table, ' href="' . $oldlink, ' href="' . $newlink);

			//$fixWP = 0;
			//$fixWP2 = 0;

			if ($fixWP >= 0 OR $fixWP2 >= 0) {
				
				echo "$fixWP st ändrade (med class) och $fixWP2 utan!";
			}

			echo "<br />";

		}

	}

// END FILE
// ****************************************************************************

?>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>