<!-- footer -->
<?php

	// Close database (from ../inc/database.php)
	$mysqli->close();

	if (isset($wp_dburl)) {
		$mysqWP->close();
	}

?>

	</div>

	<?php
		if (DEV_ENV) {
			printDebugger();
		}
	?>

</body>
</html>