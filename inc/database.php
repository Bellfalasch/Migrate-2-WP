<?php
// Database setup (MySQL)
// ****************************************************************************	
	
	// Set constants for db-access after environment
	if ($_SERVER['SERVER_NAME'] == 'localhost')
	{	// LOCAL
		DEFINE('DB_USER', 'root');				// Username for database
		DEFINE('DB_PASS', '');					// Password for database
		DEFINE('DB_HOST', 'localhost');			// Server for database
		DEFINE('DB_NAME', 'test');				// Select database on server
	} else {
		// LIVE (change to your settings)
		DEFINE('DB_USER', 'xxx');
		DEFINE('DB_PASS', 'xxx');
		DEFINE('DB_HOST', 'localhost');
		DEFINE('DB_NAME', 'test');
	}
	
	// Set up database class
	global $mysqli;
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
	if (mysqli_connect_errno()) { die("<p>" . mysqli_connect_errno() . " - Can't connect to this database or server =/</p>"); }
	$mysqli->set_charset('utf8');

	global $mysqWP;
	global $wp_dburl;
	global $wp_dbname;
	global $wp_table;
	global $wp_dbuser;
	global $wp_dbpass;

	if (!isset($wp_dburl)) {
		$wp_dburl = DB_HOST;
		$wp_dbuser = DB_USER;
		$wp_dbpass = DB_PASS;
		$wp_dbname = "wordpress";
		$wp_table = "wp";
	}

	global $mysqWP;
	$mysqWP = new mysqli( $wp_dburl, $wp_dbuser, $wp_dbpass, $wp_dbname );
	$mysqWP->set_charset('utf8');


// Database main functions (does all the talking to the database class and handling of errors)
// ****************************************************************************	

	// Simple bridge that will choose which database-function to use based on the SQL you sent in.
	// Doesn't support mixing of types (SELECT INTO for example is not supported).
	// The reason for this is that the different functions return different data, with a SELECT you want
	//   your selected data, with an INSERT you want the new ID, etc.
	function db_MAIN($sql)
	{
		switch(substr(trim($sql),0,6))
		{
			case "SELECT":
				return db_FIND($sql);
				break;

			case "UPDATE":
				return db_EXEC($sql);
				break;

			case "DELETE":
				return db_EXEC($sql);
				break;

			case "INSERT": 
				return db_INSERT($sql);
				break;

			// If none of these SQL-types are used, then try the normal FIND-one that will return a set of data.
			default:
				return db_FIND($sql);
				break;
		}
	}

	// Run SQL and return a dataset
	function db_FIND($sql)
	{
		global $mysqli;
		$result = $mysqli->query( $sql );
		if ( $result )
		{
			if ($result->num_rows > 0) {
				return $result;
			} else {
				return null;
			}
		} else {
			db_printError($mysqli->error, $mysqli->errno, $sql);
			return null;
		}
	}

	// Run SQL that doesn't return a dataset, and return inserted id
	function db_INSERT($sql)
	{
		global $mysqli;
		$result = $mysqli->query($sql);
		if ( $result )
			return $mysqli->insert_id;
		else {
			db_printError($mysqli->error, $mysqli->errno, $sql);
			return -1;
		}
	}

	// Run SQL that doesn't return a dataset, and return affected rows
	function db_EXEC($sql)
	{
		global $mysqli;
		$result = $mysqli->query($sql);
		if ( $result )
			return $mysqli->affected_rows;
		else {
			db_printError($mysqli->error, $mysqli->errno, $sql);
			return -1;
		}
	}

	function wp_MAIN($sql)
	{
		global $mysqWP;
		$result = $mysqWP->query( $sql );
		if ( $result )
		{
			return $result;
		} else {
			printf("<div class='error'>There has been an error from MySQL: %s<br /><br />%s</div>", $mysqWP->error, nl2br($sql));
			exit;
		}
	}

	function wp_EXEC($sql)
	{
		global $mysqWP;
		$result = $mysqWP->query($sql);
		if ( $result )
			return $mysqWP->affected_rows;
		else {
			printf("$mysqWP->error, $mysqWP->errno, $sql");
			return -1;
		}
	}



// Helper functions for SQL
// ****************************************************************************	

	// Activate transaction-handling
	function db_doBeginTran()
	{
		global $mysqli;
		global $SYS_errors_tran;
		$mysqli->autocommit(false);

		$SYS_errors_tran = array();
	}

	// Do commit or rollback based on current number of errors, and then shuts down transaction handling.
	function db_doEndTran()
	{
		global $mysqli;
		global $SYS_errors_tran;

		if (!empty($SYS_errors_tran)) {
			$mysqli->rollback();
		}
		$mysqli->commit();
		
		// Reset autocommit to true (only the SQL's just before needed transaction support)
		$mysqli->autocommit(true);

		$SYS_errors_tran = null;
	}

	// db_EXEC-functions sends in an array that is to be laundried with quote_smart
	function cleanup(&$in) 
	{
		foreach($in as $key => $value) {
			$in[$key] = quote_smart($value);
		}
	}

	// Smart string-handling for integers and strings going into the database/SQL's.
	// http://norskwebforum.no/viewtopic.php?p=243716
	function quote_smart($value)
	{
		global $mysqli;
		
		if ( get_magic_quotes_gpc() && !is_null($value) ) {
			$value = stripslashes($value);
		}
		if ( is_numeric($value) && strpos($value,',') !== false ) {
			$value = str_replace(',', '.', $value);
		}
		if ( is_null($value) ) {
			$value = 'NULL';
		}
		elseif ( !is_numeric($value) ) {
			$value = "'" . $mysqli->real_escape_string($value) . "'";
		}
		return $value;
	}

	// Simple error output from database
	function db_printError($error_msg, $error_no, $sql)
	{
		echo "
		<div class='errors'>
			<p>
				There has been an error from MySQL: (", $error_no, ") ", $error_msg, ".
			</p>
			<code>", nl2br($sql), "</code>
		</div>";
	}

?>