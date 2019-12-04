<?php

const TPP_URL = 'http://aeronav.faa.gov/d-tpp';

// DB info
define('host', '');
define('dbname', '');
define('username', '');
define('password', '');


function connect() {
	$conn = new mysqli(constant('host'), constant('username'), constant('password'), constant('dbname'));

	if ($conn->connect_error)
	    die('Connection failed: ' . $conn->connect_error);

	return $conn;
}

?>