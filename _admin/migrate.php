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
				Start by creating your project on the <a href="<?= $SYS_root . $SYS_folder ?>/migrate-project.php">manage project</a>-page.
				After the project is create you can select it in the drop down-list up top. Do that and start stepping through the migration
				process.
			</p>

		</div>


		<div class="span4 offset1">

			Help column

		</div>
	</div>


<?php require('_footer.php'); ?>