<?php
	define("DB_SERVER", "localhost");
	define("DB_USER", "widget_corp_user");
	define("DB_PASS", "widget_corp_password");
	define("DB_NAME", "widget_corp");
	
	// 1. Create a database connection
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	// Test if connection succedded
	if (mysqli_connect_errno()) {
		die("Database connection failed: " .
			mysqli_connect_error() . 
			" (" . mysqli_connect_errno() . ")"
		);
	}
?>