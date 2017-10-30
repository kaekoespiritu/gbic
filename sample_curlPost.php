<?php

$url = "http://localhost/gbic/sample_curlLanding.php";

$post_data = array(
	'query' => 'whatever', 
	'method' => 'post',
	'empid' => '2017-124844'
);

$ch = curl_init();

//URL to submit to
curl_setopt($ch, CURLOPT_URL, $url);

//Return output instead of outputting it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Post request
curl_setopt($ch, CURLOPT_POST, 1);

//Adding the post variables to the request
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

//Execute the request and fetch the response to check for errors
$output = curl_exec($ch);

if($output === false) {
	echo "cURL Error: ".curl_error($ch);
}

//close and free up the curl handle
curl_close($ch);

//Display the row output
print_r($output);
?>


























