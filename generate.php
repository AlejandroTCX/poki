<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('fpdf.php');

    // Obtener el contenido del PDF desde el formulario
    $pdf_content = $_POST['pdf_content'];

    // Generar el nombre del archivo PDF con una marca de tiempo para evitar conflictos de nombres
    $pdf_file = 'pdf_1' . time() . '.pdf';

    // Carpeta temporal en tu servidor XAMPP para guardar el PDF antes de enviarlo al servidor WebDAV
    $temp_folder = 'temp_pdf/';

    // Crear una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Contenido del PDF', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, $pdf_content, 0, 'L');

    // Guardar el PDF temporalmente en el servidor XAMPP
    $pdf->Output($temp_folder . $pdf_file, 'F');

    // Datos de conexión cURL al servidor WebDAV
    $webdav_server_url = 'http://192.168.1.202/'; // Reemplaza con la URL base del servidor WebDAV
    $webdav_username = 'alex'; // Reemplaza con el nombre de usuario para acceder al servidor WebDAV
    $webdav_password = '123'; // Reemplaza con la contraseña para acceder al servidor WebDAV

    // Ruta de destino en el servidor WebDAV
    $webdav_remote_path = '' . $pdf_file; // Reemplaza con la ruta donde deseas guardar el PDF en el servidor WebDAV

    // Configurar cURL para la transferencia del PDF
    $ch = curl_init($webdav_server_url . $webdav_remote_path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $webdav_username . ':' . $webdav_password);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_INFILE, fopen($temp_folder . $pdf_file, 'r'));
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($temp_folder . $pdf_file));

    // Ejecutar la transferencia cURL
    $response = curl_exec($ch);

    // Verificar si la transferencia fue exitosa
    if ($response !== false) {
        echo "El PDF se generó y se envió correctamente por cURL al servidor WebDAV.";
    } else {
        echo "Error al enviar el PDF por cURL al servidor WebDAV: " . curl_error($ch);
    }

    // Cerrar la sesión cURL
    curl_close($ch);

    // Eliminar el archivo temporal PDF
    unlink($temp_folder . $pdf_file);
}
?>
