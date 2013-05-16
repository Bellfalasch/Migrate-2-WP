<?php
	/* Set up template variables */
	$PAGE_name  = 'Settings';
	$PAGE_title = 'Admin/' . $PAGE_name;
?>
<?php require('_global.php'); ?>

<?php

	// See README.md in root for more information about how to set up and use the form-generator!

	/*
	 Needed fields:
	 * database cleaner
	 * table-name cleaner
	 * database WP
	 * table-name WP
	 * Database password cleaner
	 * Database password WP
	 * db URL cleaner
	 * db URL WP

	 Spara detta i en cookie så att man slipper fylla i dem om och om.
	 Så klart inte så säkert live sen, men då kan funktionen tas bort. 
	 T.ex. kanske låta den sniffa localhost och bara aktiveras då.
	 */

	addField( array(
		"label" => "Wordpress &gt; Database:",
		"id" => "wp_dbname",
		"type" => "text(3)",
		"description" => "Database name for where your current Wordpress-installation resides (set up Wordpress first).",
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
			if (empty($_SESSION['ERRORS'])) {
				
				echo "<div class='alert alert-block alert-success'><h4>Success</h4><p><strong>Your posted data validated!</strong> (we have not set this up yet to save to your database =/)</p></div>";

				// UPDATE
				if ( $PAGE_dbid > 0 )
				{
					// CALL YOUR DATABASE AND UPDATE WITH THIS NEW DATA ... (TODO)
/*
					$result = db2_updateCampaign( array(
								'title' => $formTitle,
								'url' => $formUrl,
								'start' => $formStart . ' 00:00:00',
								'stop' => $formStop . ' 23:59:59',
								'short_info' => $formShortInfo,
								'verv_step1' => $formVervStep1,
								'verv_step2' => $formVervStep2,
								'verv_takk' => $formVervTakk,
								'give_step1' => $formGiveStep1,
								'give_takk' => $formGiveTakk,
								'image' => $formImage,
								'id' => $PAGE_dbid
							) );

					if ($result >= 0) {
						echo "<div class='alert alert-success'><h4>Save successful</h4><p>$PAGE_name updated</p></div>";
					} else {
						pushError("NOT saved");
					}
*/
				// CREATE
				} else {

					// CALL YOUR DATABASE AND INSERT THIS NEW DATA ... (TODO)
/*
					$result = db2_createCampaign( array(
								'title' => $formTitle,
								'url' => $formUrl,
								'start' => $formStart . ' 00:00:00',
								'stop' => $formStop . ' 23:59:59',
								'short_info' => $formShortInfo,
								'verv_step1' => $formVervStep1,
								'verv_step2' => $formVervStep2,
								'verv_takk' => $formVervTakk,
								'give_step1' => $formGiveStep1,
								'give_takk' => $formGiveTakk,
								'image' => $formImage
							) );

					if ($result > 0) {
						
						echo "<div class='alert alert-success'><h4>Save successful</h4><p>New $PAGE_name saved, id: $result</p></div>";

						// After save we have to reset all variabels so that we get a new clean form
						$PAGE_dbid = -1;

						foreach ($PAGE_form as $field) {
							$field["content"] = '';
						}

						// If you don't wanna show the message, you could just redirect back to this page instead of "cleaning" all the variables.
						//ob_clean();
						//header('Location: ' . $SYS_folder . '/campaign.php');

					} else {
						pushError("NOT saved");
					}
*/
				}
			}

		}


		// TODO: If we have a given id, fetch form data from database.
		if ( $PAGE_dbid > 0 )
		{
			// Pseudo: Run SQL, get result, loop through it and put each data in the correct "content" of all the arrays.
			// 		   Maybe time for that setting in the arrays with name of field in database? Hmm ... TODO =)
			/*
			$result = db2_getCampaign( array('id' => $PAGE_dbid) );

			if (!is_null($result))
			{
				$row = $result->fetch_object();

				$formTitle = $row->title;
				$formUrl = $row->url;
				$formStart = substr($row->start,0,10);
				$formStop = substr($row->stop,0,10);
				$formShortInfo = $row->shortinfo;
				$formVervStep1 = $row->verv_step1;
				$formVervStep2 = $row->verv_step2;
				$formVervTakk = $row->verv_takk;
				$formGiveStep1 = $row->give_step1;
				$formGiveTakk = $row->give_takk;
				$formImage = $row->image;

			} else {
				pushError("Couldn't find the requested $PAGE_name");
			}
			*/
		}

	?>

	<script language="javascript" type="text/javascript" src="<?= $SYS_root . $SYS_folder ?>/assets/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
			// General options
			mode : "textareas",
			theme : "advanced",
			plugins : "spellchecker,iespell,inlinepopups,paste,nonbreaking",

			// Theme options
//			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
//			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
//			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo",
			theme_advanced_buttons2 : "outdent,indent,link,unlink,|,cut,copy,paste,pastetext,pasteword,|,cleanup",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
        	editor_selector : "mceEditor",
        	editor_deselector : "mceNoEditor",
			width: "100%",
			height: "300"
		});
	</script>

	<div class="page-header">
		<h1>
			<?= $PAGE_name ?>s
			<small>create and manage <?= $PAGE_name ?>s</small>
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

			<a class="btn btn-success" href="?"><i class="icon-plus-sign icon-white"></i> Add new <?= $PAGE_name ?></a>

			<hr />

			<h4>Select <?= $PAGE_name ?></h4>
			<?php
				$result = db2_getCampaignsActive();

				if (!is_null($result))
				{
					while ( $row = $result->fetch_object() )
					{
						echo "<a href='?id=" . $row->id . "'>" . $row->title . "</a><br />";
					}
				}
				else
				{
					echo "<p>No active $PAGE_name found</p>";
				}
			?>
			<hr />

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

		<?php if ($PAGE_dbid > 0 && 1 == 2) { ?>
		<a href="?del=<?= $PAGE_dbid ?>" class="btn btn-mini btn-danger">Delete</a>
		<?php } ?>
	</div>

</form>


<?php require('_footer.php'); ?>