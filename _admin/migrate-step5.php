<?php
	/* Set up template variables */
	$PAGE_name  = 'Step';
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
			Step 1
			<small>Description</small>
		</h1>
	</div>

	<?php
		outputErrors($_SESSION['ERRORS']);
	?>

<form class="well form-inline" action="" method="post" enctype="multipart/form-data">

	<div class="row">
		<div class="span12">

			<p>
				Information
			</p>

			<button type="submit" id="spara" name="spara" class="btn btn-primary">Run upgrade</button>

			<button type="submit" class="btn">Test upgrade</button>

		</div>
	</div>

</form>


<?php require('_footer.php'); ?>