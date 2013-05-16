<?php
	/* Set up template variables */
	$PAGE_name  = 'Settings';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>

<?php

	// See README.md in root for more information about how to set up and use the form-generator!

	/*
	 Spara detta i en cookie så att man slipper fylla i dem om och om.
	 Så klart inte så säkert live sen, men då kan funktionen tas bort. 
	 T.ex. kanske låta den sniffa localhost och bara aktiveras då.
	 */

	// WP-data

	addField( array(
		"label" => "Wordpress &gt; DB url:",
		"id" => "wp_dburl",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Wordpress &gt; DB name:",
		"id" => "wp_dbname",
		"type" => "text(3)",
		"description" => "Database name for where your current Wordpress-installation resides (set up Wordpress first).",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Wordpress &gt; Table name:",
		"id" => "wp_tablename",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Wordpress &gt; DB user:",
		"id" => "wp_dbuser",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Wordpress &gt; DB password:",
		"id" => "wp_dbpass",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	// CLEANER-data

	addField( array(
		"label" => "Cleaner &gt; DB url:",
		"id" => "cleaner_dburl",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Cleaner &gt; DB name:",
		"id" => "cleaner_dbname",
		"type" => "text(3)",
		"description" => "Database name for where your current Wordpress-installation resides (set up Wordpress first).",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Cleaner &gt; Table name:",
		"id" => "cleaner_tablename",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Cleaner &gt; DB user:",
		"id" => "cleaner_dbuser",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Cleaner &gt; DB password:",
		"id" => "cleaner_dbpass",
		"type" => "text(3)",
		"description" => "xxx",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );
?>
<?php require('_header.php'); ?>


<?php

		// User has posted (trying to save changes)
		if (ISPOST)
		{
			validateForm();
			
			var_dump($PAGE_form);

			// If no errors:
			if (empty(SYS_errors)) {
				
				echo "<div class='alert alert-block alert-success'><h4>Success</h4><p><strong>Your posted data validated!</strong> (we have not set this up yet to save to your database =/)</p></div>";

				// Stupid way of getting all the form data into variables for use to save the data.
				$wp_dburl  = $PAGE_form[0]["content"];
				$wp_dbname = $PAGE_form[1]["content"];
				$wp_table  = $PAGE_form[2]["content"];
				$wp_dbuser = $PAGE_form[3]["content"];
				$wp_dbpass = $PAGE_form[4]["content"];
				$cleaner_dburl  = $PAGE_form[5]["content"];
				$cleaner_dbname = $PAGE_form[6]["content"];
				$cleaner_table  = $PAGE_form[7]["content"];
				$cleaner_dbuser = $PAGE_form[8]["content"];
				$cleaner_dbpass = $PAGE_form[9]["content"];

				// Do some simple cleaning of data
				// Todo, if not washed when validated - answer: not washed
				// * Wash data

				// Save data in cookies
				$expire = time() + (60*60*24*31);
				setcookie( "wp_dburl",       $wp_dburl,       $expire );
				setcookie( "wp_dbname",      $wp_dbname,      $expire );
				setcookie( "wp_table",       $wp_table,       $expire );
				setcookie( "wp_dbuser",      $wp_dbuser,      $expire );
				setcookie( "wp_dbpass",      $wp_dbpass,      $expire );
				setcookie( "cleaner_dburl",  $cleaner_dburl,  $expire );
				setcookie( "cleaner_dbname", $cleaner_dbname, $expire );
				setcookie( "cleaner_table",  $cleaner_table,  $expire );
				setcookie( "cleaner_dbuser", $cleaner_dbuser, $expire );
				setcookie( "cleaner_dbpass", $cleaner_dbpass, $expire );

				// $_COOKIES['wp_dburl'] 

				// * Read this data into sessions, if exist (in header)
				// * Then read data into constants (in header)

			}

		} else if (isset($wp_url)) {

			// Data from form already saved, so set it from our variables.

			$PAGE_form[0]["content"] = $wp_dburl;
			$PAGE_form[1]["content"] = $wp_dbname;
			$PAGE_form[2]["content"] = $wp_table;
			$PAGE_form[3]["content"] = $wp_dbuser;
			$PAGE_form[4]["content"] = $wp_dbpass;

			$PAGE_form[5]["content"] = $cleaner_dburl;
			$PAGE_form[6]["content"] = $cleaner_dbname;
			$PAGE_form[7]["content"] = $cleaner_table;
			$PAGE_form[8]["content"] = $cleaner_dbuser;
			$PAGE_form[9]["content"] = $cleaner_dbpass;

		}

	?>

	<div class="page-header">
		<h1>
			<?= $PAGE_name ?>
			<small>create and manage your migration</small>
		</h1>
	</div>

<?php

	// Now that we are just before the form starts, we can output any errors we might have pushed into the error-array.
	// Calling this function outputs every error, earlier pushes to the error-array also stops the saving of the form.

	outputErrors($SYS_errors);

?>

<form class="form-horizontal" action="" method="post">

	<div class="row">
		<div class="span7">

	<?php

		// This is the output area, where all the field's html should be generated for empty field's SQL inserts, and already filled in field's SQL updates.
		// The fields data/content is generated in the upper parts of this document. Just call this function to get the html out.

		outputFormFields();

	?>

		</div>


		<div class="span4 offset1">

			<h4>Help</h4>
			<p>
				<strong>Help info</strong> just some random gibberish about this admin page that could be useful for somebody.
				I sometimes use screenshots here to connect these back-end fields to the front-end (clients love this because
				after your first show-and-tell of this amdin with them they WILL forget it, mainly because most clients log in
				maybe 4 times a year, and those times are right after getting the product).
			</p>

		</div>
	</div>


	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save</button>
	</div>

</form>


<?php require('_footer.php'); ?>