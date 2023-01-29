<?php

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://hesabicorporateapis.jawwal.ps/api/Tank/AddTankReader',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"DeviceMSISDN": "592831998",
		"RecieverMSISDN": "597547859",
		"TankHeightInCm": 190
	}',
	CURLOPT_HTTPHEADER => array(
		'Authorization: Basic VGFua01hbmFnZW1lbnQ6dSEmQUM3SnBvQjZSNkEyQF4=',
		'Content-Type: application/json',
		'Origin: https://hesabicorporateapis.jawwal.ps/api/Tank/AddTankReader'
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo $response;
