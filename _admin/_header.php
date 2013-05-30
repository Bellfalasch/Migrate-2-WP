<!DOCTYPE html>
<?php

	// Push some debugging data to the footer:
	pushDebug("
			SYS_folder: $SYS_folder -
			SYS_root: $SYS_root -
			SYS_incroot: $SYS_incroot -
			SYS_script: $SYS_script
			");

	if (isset($_SESSION['username'])) {
		
		pushDebug("
				[SESSION]
				username: " . $_SESSION['username'] . "
				mail: " . $_SESSION['mail'] . "
				level: " . $_SESSION['level'] . "
				id: " . $_SESSION['id'] . "
				sessionID: " . session_id() . "
				");
	}

?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8" />
	<title><?= $PAGE_title ?> - [Migrate 2 WP]</title>
	<link rel="shortcut icon" href="<?= $SYS_root ?>/favicon.ico">
	<link rel="stylesheet" href="<?= $SYS_root . $SYS_folder ?>/assets/bootstrap.min.css" />
	<link rel="stylesheet" href="<?= $SYS_root . $SYS_folder ?>/assets/admin.css?v=<?php if (DEV_ENV) echo rand(); ?>" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				
				<a class="brand" href="http://www.github.com/Bellfalasch/">Migrate 2 WP</a>
				
				<div class="nav-collapse">

					<ul class="nav">
						<li<?php flagAsActiveOn("index") ?>><a href="<?= $SYS_root . $SYS_folder ?>/index.php">Start</a></li>
						<?php if ($SYS_adminlvl > 0) { ?>
							<li<?php flagAsActiveOn("migrate") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate.php">Migrate</a></li>
							<?php if ($SYS_adminlvl == 2) { ?>
							<li<?php flagAsActiveOn("users") ?>><a href="<?= $SYS_root . $SYS_folder ?>/users.php">Users</a></li>
							<?php } ?>
						<?php } ?>
					</ul>

				</div>
			</div>
		</div>
	</div>
	
	<div class="subnav subnav-fixed">
		<ul class="nav nav-pills">
			<?php if (isActiveOn("index")) { ?>

				<li<?php flagAsActiveOn("index") ?>><a href="<?= $SYS_root . $SYS_folder ?>/index.php">Login</a></li>
				<?php if ($SYS_adminlvl > 0) { ?>
				<li><a href="<?= $SYS_root . $SYS_folder ?>/index.php?do=logout">Sign out</a></li>
				<?php } ?>


			<?php } else if (isActiveOn("users")) { ?>

				<li<?php flagAsActiveOn("users") ?>><a href="<?= $SYS_root . $SYS_folder ?>/users.php">Users</a></li>


			<?php } else if (isActiveOn("migrate")) { ?>

				<?php if ($SYS_adminlvl > 0) { ?>
					<li<?php flagAsActiveOn("project") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-project.php">Project</a></li>

					<li>

<script type="text/javascript">
	$(document).ready(function() {
		$("#project_list").change(function() {

			location.href = location.href + "?project=" + $("#project_list option:selected").val();

		});
	});
</script>

						<select name="project" style="margin-top:4px;" id="project_list">
							<option value="">Choose Project:</option>
			<?php
				$result = db_getSites();

				if (!is_null($result))
				{
					while ( $row = $result->fetch_object() )
					{
						if ($row->id == $PAGE_siteid)
							$selected = " selected=\"selected\"";
						else
							$selected = "";

						echo "<option value=\"" . $row->id . "\"" . $selected . ">" . $row->name . "</option>";
					}
				}
				else
				{
					echo "<option value=\"\">No projects found!</option>";
				}
			?>
						</select>
					</li>

					<?php if ($PAGE_siteid > 0) { ?>

						<li<?php flagAsActiveOn("settings") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-settings.php">Settings</a></li>
						<li<?php flagAsActiveOn("step1") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step1.php">1: Eat</a></li>
						<li<?php flagAsActiveOn("step2") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step2.php">2: Strip</a></li>
						<li<?php flagAsActiveOn("step3") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step3.php">3: Clean 1st</a></li>
						<li<?php flagAsActiveOn("step3b") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step3b.php">3b: Tidy</a></li>
						<li<?php flagAsActiveOn("step3c") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step3c.php">3c: Clean 2nd</a></li>
						<li<?php flagAsActiveOn("step4") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step4.php">4: Connect</a></li>
						<li<?php flagAsActiveOn("step5") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step5.php">5: Move</a></li>

					<?php } else { ?>

						<li><a href="#" disabled="true">Settings</a></li>
						<li>Step 1</li>
						<li>Step 2</li>
						<li>Step 3</li>
						<li>Step 4</li>
						<li>Step 5</li>
						<li>Step 6</li>
						<li>Step 7</li>

					<?php } ?>

				<?php } ?>

			<?php } ?>
		</ul>
	</div>

	<div id="container">
<!-- /header -->