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
	<script src="<?= $SYS_root . $SYS_folder ?>/assets/admin.js"></script>
</head>
<body class="<?= $SYS_script ?>">

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				
				<a class="brand" href="http://www.github.com/Bellfalasch/Migrate-2-WP">Migrate 2 WP</a>
				
				<div class="nav-collapse">

					<ul class="nav">
						<li<?php flagAsActiveOn("index") ?>><a href="<?= $SYS_root . $SYS_folder ?>/index.php">Start</a></li>
						<?php if ($SYS_adminlvl > 0) { ?>
							<li<?php flagAsActiveOn("project") ?>><a href="<?= $SYS_root . $SYS_folder ?>/project.php">Projects</a></li>
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


			<?php } else if (isActiveOn("project")) { ?>

				<li<?php flagAsActiveOn("project") ?>><a href="<?= $SYS_root . $SYS_folder ?>/project.php">Projects</a></li>

			<?php } else if (isActiveOn("migrate")) { ?>

				<?php if ($SYS_adminlvl > 0) { ?>

					<li<?php flagAsActiveOn("select") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-select.php">0: Select</a></li>

					<?php if ($PAGE_siteid > 0) { ?>
						<li class="disabled"><a href="#0">"<?= $PAGE_sitename ?>"</a></li>
					<?php } ?>

					<?php if ($PAGE_siteid > 0 && $PAGE_sitestep >= 0) { ?>
						<li<?php flagAsActiveOn("step1") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step1.php">1: Eat</a></li>
					<?php } else { ?>
						<li class="disabled"><a href="#0">1: Eat</a></li>
					<?php } ?>

					<?php if ($PAGE_siteid > 0 && $PAGE_sitestep >= 1) { ?>
						<li<?php flagAsActiveOn("step2") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step2.php">2: Strip</a></li>
					<?php } else { ?>
						<li class="disabled"><a href="#0">2: Strip</a></li>
					<?php } ?>

					<?php if ($PAGE_siteid > 0 && $PAGE_sitestep >= 2) { ?>
						<li<?php flagAsActiveOn("step3") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step3.php">3: Manage</a></li>
					<?php } else { ?>
						<li class="disabled"><a href="#0">3: Manage</a></li>
					<?php } ?>

					<?php if ($PAGE_siteid > 0 && $PAGE_sitestep >= 2) { ?>
						<li<?php flagAsActiveOn("step4") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step4.php">4: Wash</a></li>
						<li<?php flagAsActiveOn("step5") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step5.php">5: Tidy</a></li>
						<li<?php flagAsActiveOn("step6") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step6.php">6: Clean</a></li>
					<?php } else { ?>
						<li class="disabled"><a href="#0">4: Wash</a></li>
						<li class="disabled"><a href="#0">5: Tidy</a></li>
						<li class="disabled"><a href="#0">6: Clean</a></li>
					<?php } ?>

					<?php if ($PAGE_siteid > 0 && $PAGE_sitestep >= 2) { ?>
						<li<?php flagAsActiveOn("step7") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step7.php">7: Connect</a></li>
					<?php } else { ?>
						<li class="disabled"><a href="#0">7: Connect</a></li>
					<?php } ?>

					<?php if ($PAGE_siteid > 0 && $PAGE_sitestep >= 7) { ?>
						<li<?php flagAsActiveOn("step8") ?>><a href="<?= $SYS_root . $SYS_folder ?>/migrate-step8.php">8: Move</a></li>
					<?php } else { ?>
						<li class="disabled"><a href="#0">8: Move</a></li>
					<?php } ?>

				<?php } ?>

			<?php } ?>
		</ul>
	</div>

	<div id="container">

		<div class="page-header">
			<h1>
				<?= $PAGE_name ?>
				<small><?= $PAGE_desc ?></small>
			</h1>
		</div>
		
		<?php
			// Generate the progress indicator automatically
			if ( substr($SYS_script,0,12) == 'migrate-step' ) {

				$percentage = 0;
				$total_steps = 8;
				$perstep = 100 / $total_steps;
				$notpost = 0.9 * $perstep;

				$percentage = $PAGE_step * $perstep;

				if (!ISPOST) {
					$percentage -= $notpost;
				}
		?>
		
			<div class="progress progress-striped">
				<div class="bar" style="width: <?= $percentage ?>%;"></div>
			</div>

		<?php } ?>
<!-- /header -->
