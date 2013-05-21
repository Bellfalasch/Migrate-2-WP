<?php
	/* Set up template variables */
	$PAGE_name  = 'Step 7';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php include('_header.php'); ?>


<?php

	if (ISPOST)
	{
		
		// code

	}

?>

	<div class="page-header">
		<h1>
			Step 7
			<small>extra replacing of old strings</small>
		</h1>
	</div>

	<?php
		outputErrors($SYS_errors);
	?>

<form class="well form-inline" action="" method="post" enctype="multipart/form-data">

	<div class="row">
		<div class="span12">

			<p>
				Old code you manually found out you want to get replaced (a lot can be done in step 3
				before Tidy if you'd prefer to hard code it).
			</p>

			<button type="submit" id="spara" name="spara" class="btn btn-primary">Run upgrade</button>

			<button type="submit" class="btn">Test upgrade</button>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>