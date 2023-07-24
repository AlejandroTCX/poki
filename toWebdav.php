<?php
if (isset($_POST['pdfFilePath'])) {
    // Obtener el contenido del archivo PDF descargado
    $pdfContent = $_POST['pdfFilePath'];

    // URL del servidor WebDAV
    $webdavUrl = "10.0.0.6"; // Reemplaza con la URL del servidor WebDAV y el nombre del archivo

    // Credenciales de autenticación (si es necesario)
    $username = "alex"; // Reemplaza con el nombre de usuario del servidor WebDAV
    $password = "123"; // Reemplaza con la contraseña del servidor WebDAV

    // Configurar la solicitud cURL
    $ch = curl_init($webdavUrl);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

    // Establecer el contenido del archivo PDF en la solicitud
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pdfContent);

    // Enviar la solicitud PUT a WebDAV
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Verificar el resultado de la solicitud
    if ($statusCode === 200) {
        echo "El archivo PDF se envió correctamente al servidor WebDAV.";
    } else {
        echo "Hubo un error al enviar el archivo PDF al servidor WebDAV. Código de estado: $statusCode";
    }

    // Cerrar la conexión cURL
    curl_close($ch);
} else {
    echo "No se recibió el contenido del archivo PDF.";
}
?>