<?php
	/* Set up template variables */
	$PAGE_name  = 'Example';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>
<?php require('_header.php'); ?>


	<div class="page-header">
		<h1>
			<?= $PAGE_name ?>
			<small>migrating to Wordpress in 1, 2, ... 7</small>
		</h1>
	</div>

	<div class="row">
		<div class="span7">

			<p>
				Introduction text here!
			</p>

		</div>


		<div class="span4 offset1">

			Help column

		</div>
	</div>


<?php require('_footer.php'); ?>