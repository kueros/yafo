<?php

$url = 'https://qa.alephmanager.com/API/insert_cmdb';
$archivoCsv = 'reportes/ejemploEntradaRegistros.csv';

if (($handle = fopen($archivoCsv, "r")) !== FALSE) {
	fgetcsv($handle, 1000, ";");
	$registros = array();

	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		#chau comillas y espacios
		$identificador = trim(str_replace('"', '', $data[0]));
		$nombre = trim(str_replace('"', '', $data[1]));

		$postDataArray = [
			'categoria_id' => 60, // categoria "test api"
			'identificador' => $identificador,
			'nombre' => $nombre
		];

		$postData = http_build_query($postDataArray);
		$api_key = 'X0z2dBZYP%40oBP!K8R*2)Ky_YKviVkC';

		$postData = 'api_key=' . $api_key . "&" . $postData;

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $postData,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/x-www-form-urlencoded',
			'Cookie: ci_session=ei1jhqvj6j292368vchqj9aj7js0trt5'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

	}
	fclose($handle);

}
