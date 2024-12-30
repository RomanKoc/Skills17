<?php

// Permite accesos de origen cruzado desde localhost:4200
header('Access-Control-Allow-Origin: http://localhost:4200');
// Si planeas enviar solicitudes POST con credenciales, también puedes necesitar
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, X-Requested-With');


// Define el directorio de destino para las imágenes cargadas
$directorioDestino = "uploads/";

// Verifica si el directorio de destino existe, si no, intenta crearlo
if (!file_exists($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}

// Verifica si el archivo ha sido enviado
if (isset($_FILES['imagen'])) {
    $archivo = $_FILES['imagen'];
    $rutaTemporal = $archivo['tmp_name'];
    $nombreArchivo = basename($archivo['name']);
    $rutaDestino = $directorioDestino . $nombreArchivo;

    // Intenta mover el archivo desde su ubicación temporal al directorio de destino
    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
        // Si la carga es exitosa, envía una respuesta JSON con la URL de la imagen
        echo json_encode(["urlImagen" => $rutaDestino]);
    } else {
        // Si falla la carga, envía un mensaje de error
        echo json_encode(["error" => "Error al subir el archivo."]);
    }
} else {
    // Si no se encuentra el archivo en la petición, envía un mensaje de error
    echo json_encode(["error" => "Archivo no enviado."]);
}
?>
