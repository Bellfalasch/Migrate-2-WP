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
/*
		// Fix old images in content:
		UPDATE `" . $wp_table . "_posts` SET
		post_content = REPLACE(post_content, '<img src="b/', '<img src="http://guide.ffuniverse.nu/ff7/wp-content/themes/ffu2_ff7/assets/_old/')

		// Fix old image icons to be a simple classed span instead:
		UPDATE `" . $wp_table . "_posts` SET post_content = REPLACE( post_content, '<img src="http://guide.ffuniverse.nu/ff7/wp-content/themes/ffu2_ff7/assets/_old/L2.jpg" />','<span class="psx_button l2">L2</span>' )

		// Fix ugly html and bad semantics:
		UPDATE `" . $wp_table . "_posts` SET
		post_content = REPLACE(post_content, '<td><strong>LEVEL</strong></td>', '<th>Level</th>')

		// And more advanced replacement spanning many rows:
		UPDATE `" . $wp_table . "_posts` SET
		post_content = REPLACE(post_content, '</td>
		</tr>
		</tbody>
		</table>
		<table>
		<tbody>
		<tr>
		<td><strong>LEVEL</strong></td>', '</p><table>
		<tbody>
		<tr>
		<td><strong>LEVEL</strong></td>')
*/

	}

?>

	<div class="page-header">
		<h1>
			Step 7
			<small>extra replacing of old strings</small>
		</h1>
	</div>

	<div class="progress progress-striped">
		<div class="bar" style="width: <?php if (ISPOST) { ?>99<?php } else { ?>90<?php } ?>%;"></div>
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