<?php

	// Output infoboxes with heading and text in different colors/formats (Bootstrap style)
	function fn_infobox($heading,$text,$type) {

		if ($type == '') {
			$type = " alert-success";
		} elseif ($type == 'error') {
			$type = " alert-error";
		}

		$final = "<div class='alert" . $type . "'>";
		if ($heading != '') {
			$final .= "<h4>" . $heading . "</h4>";
		}
		$final .= "<p>" . $text . "</p>";
		$final .= "</div>";

		echo $final;
	}

	// Get correct ip even if user is on a proxy
	// - http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html
	function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		} 
		return $ip;
	}

	// If the array sent in contains any items an error-block will be printed out.
	function outputErrors($errors)
	{
		if (!empty($errors)) {
			echo "
				<div class='errors'>
					<h4>Something went wrong:</h4>
					<ul>";
			foreach( $errors as $err_row ) {
				echo "
						<li><i>&times;</i> ", $err_row, "</li>";
			}
			echo "
					</ul>
				</div>";
		}
	}

	// If the array sent in contains any items an error-block will be printed out.
	function printDebugger()
	{
		global $SYS_debug;
		$debug = $SYS_debug;

		if (!empty($debug)) {
			echo "
				<div class='debugger'>
					<h4>DEBUGGER:</h4>
					<ul>";
			foreach( $debug as $debug_row ) {
				echo "
						<li><i>&times;</i> ", $debug_row, "</li>";
			}
			echo "
					</ul>
				</div>";
		}
	}

	// Fire an error which easily can be checked for and/or printed out.
	function pushError($string)
	{
		global $SYS_errors;
		array_push($SYS_errors, $string);
	}

	// Put a post in the debug-array
	function pushDebug($string)
	{
		global $SYS_debug;
		array_push($SYS_debug, $string);
	}

	// TRANSACTIONS:

	// Print any transactions-error from the database.
	function printError_tran()
	{
		global $SYS_errors_tran;
		outputErrors($SYS_errors_tran);
	}

	// Unique function for filling up on MySQL TRANSACTON-errors
	function pushError_tran($string)
	{
		global $SYS_errors_tran;
		array_push($SYS_errors_tran, $string);
	}


	function randomAlphaNum($length)
	{
		$rangeMin = pow(36, $length-1); //smallest number to give length digits in base 36 
		$rangeMax = pow(36, $length)-1; //largest number to give length digits in base 36 
		$base10Rand = mt_rand($rangeMin, $rangeMax); //get the random number 
		$newRand = base_convert($base10Rand, 10, 36); //convert it 

		return $newRand; //spit it out 
	}

	// Native PHP 5 validation of e-mail. If on older system use the commented out line.
	function isValidEmail($email)
	{
		//if(preg_match("/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0)
		if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
			return true;
		else
			return false;
	}

	// Simple fetch form-field with isset-check. Always returns a string. Never empty or null.
	function formGet($field)
	{
		if (isset($_POST[$field]))
			return removeHtmlEntities( trim($_POST[$field]) );
		else
			return '';
	}

	// Simple fetch get/url-parameter with isset-check. Always returns a string. Never empty or null.
	function qsGet($field)
	{
		if (isset($_GET[$field]))
			return removeHtmlEntities( trim($_GET[$field]) );
		else
			return '';
	}

	// Simple validation of length of a string (support for form handling).
	function isValidLength($string, $min, $max)
	{
		if (mb_strlen($string,'UTF-8') >= $min && mb_strlen($string,'UTF-8') <= $max)
			return true;
		else
			return false;
	}

	// Return true if positive number, or false if negative number!
	function sign( $number ) { 
		return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
	}

	// Just somethink quick I used for some special SQL-need where I need to remove a lot of html formating that was common.
	function removeHtmlEntities($string) {
		$tmp = $string;
		$tmp = str_replace('&amp;','&',$tmp);
		$tmp = str_replace('&quot;','"',$tmp);
		$tmp = str_replace('&#039;',"'",$tmp);
		$tmp = str_replace('&apos;',"'",$tmp);
		$tmp = str_replace('&lt;','<',$tmp);
		$tmp = str_replace('&gt;','>',$tmp);

		return $tmp;
	}

?>
