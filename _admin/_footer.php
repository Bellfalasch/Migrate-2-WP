<!-- footer -->
<?php

	// Close database (from ../inc/database.php)
	$mysqli->close();
	$mysqWP->close();

?>

	</div>

	<?php
		if (DEV_ENV) {
			printDebugger();
		}
	?>

</body>
</html>