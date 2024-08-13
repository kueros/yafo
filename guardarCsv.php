<?php

$apiKey = 'X0z2dBZYP@oBP!K8R*2)Ky_YKviVkC';
$url = 'https://subdominioentidad.alephmanager.com/API/insert_cmdb/';
$categoriaNombre = 'test api';
$archivoCsv = 'reportes/ejemploEntradaRegistros.csv';

if (($handle = fopen($archivoCsv, "r")) !== FALSE) {
	fgetcsv($handle, 1000, ";");
	$registros = array();

	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		#chau comillas y espacios
		$identificador = trim(str_replace('"', '', $data[0]));
		$nombre = trim(str_replace('"', '', $data[1]));

		$registros[] = array(
			'Identificador' => $identificador,
			'Nombre' => $nombre
		);
	}
	fclose($handle);

	$postData = array(
		'api_key' => $apiKey,
		'categoria' => $categoriaNombre,
		'registros' => $registros
	);

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json'
	));

	$response = curl_exec($ch);

	if ($response === false) {
		echo 'Error en la solicitud: ' . curl_error($ch);
	} else {
		echo 'Respuesta de la API: ' . $response;
	}

	curl_close($ch);
}
