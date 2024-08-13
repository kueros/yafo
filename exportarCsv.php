<?php

#Por las dudas vuelvo a validar la categoría enviada
if (isset($_POST['categoria'])) {
	$categoriaId = $_POST['categoria'];
	#Me vuelvo a traer la categoría según el POST
	$apiKey = 'X0z2dBZYP@oBP!K8R*2)Ky_YKviVkC';
	$categorias = getCategorias($apiKey);
	$categoriaSeleccionada = null;

	foreach ($categorias as $categoria) {
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
	echo "No se seleccionó ninguna categoría.";
}
