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

$apiKey = 'X0z2dBZYP@oBP!K8R*2)Ky_YKviVkC';

if (isset($_POST['categoria'])) {
	$categoriaId = $_POST['categoria'];
	#Me vuelvo a traer la categoría según el POST
	$apiKey = 'X0z2dBZYP@oBP!K8R*2)Ky_YKviVkC';
	$categorias = getCategorias($apiKey);
	$categoriaSeleccionada = null;
	$listaCategorias = $categorias['categorias'];

	foreach ($listaCategorias as $categoria) {
		if ($categoria['id'] == $categoriaId) {
			$categoriaSeleccionada = $categoria;
			break;
		}
	}

	if ($categoriaSeleccionada) {
		#Armo el nombre del archivo
		$nombreArchivo = $categoriaSeleccionada['nombre'] . "_" . date('Ymd') . ".csv";
		$rutaArchivo = "reportes/" . $nombreArchivo;
		$archivoCSV = fopen($rutaArchivo, 'w');

		#Encabezados
		fputcsv($archivoCSV, ['Identificador', 'Nombre']);

		foreach ($categoriaSeleccionada['campos_cmdb'] as $campo) {
			fputcsv($archivoCSV, [$categoriaSeleccionada['id'], $campo]);
		}

		fclose($archivoCSV);

		echo "Archivo CSV creado exitosamente: <a href=\"$rutaArchivo\">$nombreArchivo</a>";
	} else {
		echo "Categoría no encontrada.";
	}
} else {
	$categorias = getCategorias($apiKey);

	#Verifico si trajo algo
	if (isset($categorias['categorias'])) {
		$categorias = $categorias['categorias'];
	} else {
		die('No se pudieron obtener las categorías');
	}
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
				<option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
			<?php endforeach; ?>
		</select>
		<input type="submit" value="Exportar a CSV">
	</form>

</body>

</html>


nombre_categoria+fecha