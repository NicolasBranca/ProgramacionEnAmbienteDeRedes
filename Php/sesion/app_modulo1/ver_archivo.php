<?php

// Configuración de conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Crea una nueva conexión PDO a la base de datos
    $pdo = new PDO($dsn, $username, $password, $options);

    // Verifica si la petición es GET y si se recibió el identificador del proveedor
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['CodProveedor'])) {
        $CodProveedor = $_GET['CodProveedor'];

        // Consulta para obtener el certificado y la razón social del proveedor
        $sql = "SELECT CertificadosCalidad, RazonSocial FROM Proveedores WHERE CodProveedor = :CodProveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);
        $stmt->execute();

        $proveedor = $stmt->fetch();

        if ($proveedor && !empty($proveedor['CertificadosCalidad'])) {
            $certificado = $proveedor['CertificadosCalidad'];
            $razonSocial = $proveedor['RazonSocial'];

            // Limpia el buffer de salida si es necesario
            if (ob_get_length()) {
                ob_end_clean();
            }

            // Detecta el tipo MIME del archivo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $certificado);
            finfo_close($finfo);

            // Envía las cabeceras para mostrar el archivo en el navegador
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . $razonSocial . '.pdf"');
            header('Content-Length: ' . strlen($certificado));

            // Muestra el archivo
            echo $certificado;
            exit;
        } else {
            // Si no se encuentra el archivo, muestra error 404
            header('HTTP/1.0 404 Not Found');
            echo "Error: No se encontró el certificado de calidad para este proveedor.";
        }
    } else {
        // Si la solicitud no es válida, muestra error 400
        header('HTTP/1.0 400 Bad Request');
        echo "Error: Solicitud no válida.";
    }
} catch (PDOException $e) {
    // Si ocurre un error con PDO, muestra error 500
    header('HTTP/1.0 500 Internal Server Error');
    echo "Error en la base de datos.";
}
?>
