<?php
	/* Set up template variables */
	$PAGE_name  = 'Project';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>

<?php

	// See README.md in root for more information about how to set up and use the form-generator!

	addField( array(
		"label" => "Project Name:",
		"id" => "name",
		"type" => "text(3)",
		"description" => "Name this project (for your own convinience).",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

	addField( array(
		"label" => "Project URL:",
		"id" => "url",
		"type" => "text(5)",
		"description" => "The complete URL to the site to be crawled!",
		"min" => "2",
		"errors" => array(
						"min" => "Please keep number of character's on at least [MIN].",
					)
	) );

?>
<?php require('_header.php'); ?>


<?php

		////////////////////////////////////////////////////////
		// DELETE DATA-SUPPORT

		// Deletion of content (comment out if not to be allowed)
		if (isset($_GET['del']) && !ISPOST)
		{
			$del_id = trim( $_GET['del'] );

			$del = db_delSite( array(
						'id' => $del_id
					) );

			if ($del >= 0)
				echo "<div class='alert alert-success'><h4>Delete successful</h4><p>The data is now deleted</p></div>";
			else
				pushError("Delete of data failed, please try again.");
		}

		////////////////////////////////////////////////////////
		// HANDLE POST AND SAVE CHANGES

		if (ISPOST)
		{
			// This line is needed to call the validation-process of your form!
			validateForm();

			//var_dump($PAGE_form); // For debugging

			// Stupid way of getting all the form data into variables for use to save the data.
			$formName     = $PAGE_form[0]["content"];
			$formURL      = $PAGE_form[1]["content"];
			

			// If no errors:
			if (empty($SYS_errors)) {
				
				echo "<div class='alert alert-block alert-success'><h4>Success</h4><p><strong>Your posted data validated!</strong></p></div>";

				// UPDATE
				if ( $PAGE_dbid > 0 )
				{
					// Call function in "_database.php" that does the db-handling, send in an array with data
					$result = db_setUpdateSite( array(
								'name' => $formName,
								'url' => $formURL,
								'id' => $PAGE_dbid
							) );

					// This is the result from the db-handling in my files.
					// (On update they return -1 on error, and 0 on "no new text added, but the SQL worked", and > 0 for the updated posts id.)
					if ($result >= 0) {
						echo '<div class="alert alert-success"><h4>Save successful</h4><p>Data updated</p></div>';
					} else {
						pushError("Data could not be saved, do retry.");
					}

				// INSERT
				} else {

					// Call insert-function from our database-file for admin.
					$result = db_setSite( array(
								'name' => $formName,
								'url' => $formURL
							) );

					// If the insert worked we will now have the created id in this variable, otherwhise we will have 0 or -1.
					if ($result > 0) {
						
						echo '<div class="alert alert-success"><h4>Save successful</h4><p>New data saved, id: ' . $result . '</p></div>';

						// Reset all the data so we get a clean form after an insert.
						$PAGE_dbid = -1;

						// Stupid way of reseting the PAGE_form
						$PAGE_form[0]["content"] = '';
						$PAGE_form[1]["content"] = '';

					} else {
						pushError("Data could not be saved, do retry.");
					}

				}
			}

		}


		if ( $PAGE_dbid > 0 && !ISPOST )
		{
			
			// Call _database.php function for getting any selected data.
			$result = db_getSite( array('id' => $PAGE_dbid) );

			// If anything was found, put it into pur PAGE_form
			if (!is_null($result))
			{
				$row = $result->fetch_object();

				// Stupid way of doing it ... no function yet to bind database table to the form, sorry =P
				$PAGE_form[0]["content"] = $row->name;
				$PAGE_form[1]["content"] = $row->url;

			} else {
				pushError("Couldn't find the requested data");
			}
			
		}

	?>

	<div class="page-header">
		<h1>
			<?= $PAGE_name ?>
			<small>create and manage your projects</small>
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

			<a class="btn btn-success" href="?"><i class="icon-plus-sign icon-white"></i> Add new Project</a>

			<hr />

			<h4>Project list</h4>
			<?php
				$result = db_getSites();

				if (!is_null($result))
				{
					while ( $row = $result->fetch_object() )
					{
						echo "<a href='?id=" . $row->id . "'>" . $row->name . "</a> (Step: <strong>" . $row->step . "</strong>)<br />";
						echo "<em>" . $row->url . "</em><br /><br />";
					}
				}
				else
				{
					echo "<p>No projects found</p>";
				}
			?>

		</div>

	</div>


	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save project</button>

		<?php if ($PAGE_dbid > 0) { ?>
			<a href="?del=<?= $PAGE_dbid ?>" class="btn btn-mini btn-danger">Delete this</a>
		<?php } ?>
	</div>

</form>


<?php require('_footer.php'); ?>