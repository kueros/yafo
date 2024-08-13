<?php
function getCategorias($apiKey)
{
	$url = 'https://qa.alephmanager.com/API/get_categorias';

	$data = ['api_key' => $apiKey];

	#Headers
	$options = [
		'http' => [
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($data)
		]
	];
	#Contexto
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	if ($result === FALSE) {
		die('Error al conectarse a la API');
	}

	return json_decode($result, true);
}
function getCmdb($apiKey, $categoriaId)
{

	$url = 'https://qa.alephmanager.com/API/get_cmdb';

	$data = [
		'api_key' => $apiKey,
		'categoria_id' => (int)$categoriaId
	];

	#Headers
	$options = [
		'http' => [
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($data)
		]
	];
	#Contexto
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	if ($result === FALSE) {
		die('Error al conectarse a la API');
	}

	return json_decode($result, true);
}

$apiKey = 'X0z2dBZYP@oBP!K8R*2)Ky_YKviVkC';
$categoriaNombre = "";
if (isset($_POST['categoria'])) {
	$cadena = $_POST['categoria'];
	list($categoriaId, $categoriaNombre) = explode('|', $cadena);

	$cmdb = getCmdb($apiKey, $categoriaId + 0);

	$listaCmdb = $cmdb['cmdb'];
	#Armo el nombre del archivo
	$nombreArchivo = $categoriaNombre . "_" . date('Ymd') . ".csv";
	$rutaArchivo = "reportes/" . $nombreArchivo;
	$archivoCSV = fopen($rutaArchivo, 'w');
	fputcsv($archivoCSV, ['Identificador', 'Nombre']);

	foreach ($listaCmdb as $cmdb) {
		#Encabezados
		fputcsv($archivoCSV, [$cmdb['identificador'], $cmdb['nombre']]);
	}

	fclose($archivoCSV);
	echo "Archivo CSV creado exitosamente: <a href=\"$rutaArchivo\">$nombreArchivo</a>";
	exit;
} else {
	$categorias = getCategorias($apiKey);

	$categoriaSeleccionada = null;
	$listaCategorias = $categorias['categorias'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Consultora YAFO</title>
</head>

<body>

	<form action="#" method="post">
		<label for="categoria">Selecciona una categoría:</label>
		<select name="categoria" id="categoria">
			<?php $listaCategorias = $categorias['categorias'];
			foreach ($listaCategorias as $categoria): ?>
				<option value="<?php echo $categoria['id'].'|'.$categoria['nombre']; ?>"><?php echo $categoria['nombre']; ?></option>
∑			<?php endforeach; ?>
		</select>
		<input type="submit" value="Exportar a CSV">
	</form>

</body>

</html>