<?php
	/* Set up template variables */
	$PAGE_name  = 'Migrate';
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'migrating to Wordpress in 1, 2, ... 7';
?>
<?php require('_global.php'); ?>
<?php require('_header.php'); ?>


	<div class="row">
		<div class="span7">

			<p>
				Start by creating your project on the <a href="<?= $SYS_pageroot ?>project.php">manage project</a>-page.
				After the project is create you can select it in the drop down-list up top. Do that and start stepping through the migration
				process.
			</p>
			<p>
				If you already have a project, <a href="<?= $SYS_pageroot ?>migrate-select.php">go select it</a> and start the migration process!
			</p>

		</div>

		<div class="span4 offset1">

			<h4>Help column</h4>
			<p>Put awesome help here =)</p>

		</div>
	</div>


<?php require('_footer.php'); ?>