<?php
	/* Set up template variables */
	$PAGE_name  = 'Select';
	$PAGE_title = 'Admin/' . $PAGE_name;
	$PAGE_desc = 'start your migration journey';
?>
<?php require('_global.php'); ?>
<?php require('_header.php'); ?>


	<div class="row">
		<div class="span7">

			<p>
				Select which of your projects you wanna work on:
			</p>

			<?php
				$result = db_getSites();
				
				if (!is_null($result))
				{
					echo "<ul>";
					while ( $row = $result->fetch_object() )
					{
						if ($row->id == $PAGE_siteid)
							$selected = " style=\"background-color:#ebebeb;\"";
						else
							$selected = "";

						echo "<li" . $selected . "><a href=\"" . $SYS_pageself . "?project=" . $row->id . "\">" . $row->name . "</a> (Step: <strong>" . $row->step . "</strong>)<br /><em>" . $row->url . "</em></li>";
					}
					echo "</ul>";
				}
				else
				{
					echo "<p>No projects found (<a href=\"" . $SYS_pageroot . "project.php\">create one</a>!)</p>";
				}
			?>

		</div>

		<div class="span4 offset1">

			<h4>Need more projects?</h4>
			<p>Just go to the <a href="<?= $SYS_pageroot ?>project.php">Projects-page</a> and create some more then =)</p>

		</div>
	</div>


<?php require('_footer.php'); ?>