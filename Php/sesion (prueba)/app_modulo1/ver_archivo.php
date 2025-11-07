<?php
$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['CodProveedor'])) {
        $CodProveedor = $_GET['CodProveedor'];

        $sql = "SELECT CertificadosCalidad, RazonSocial FROM Proveedores WHERE CodProveedor = :CodProveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);
        $stmt->execute();

        $proveedor = $stmt->fetch();

        if ($proveedor && !empty($proveedor['CertificadosCalidad'])) {
            $certificado = $proveedor['CertificadosCalidad'];
            $razonSocial = $proveedor['RazonSocial'];

            if (ob_get_length()) {
                ob_end_clean();
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $certificado);
            finfo_close($finfo);

            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . $razonSocial . '.pdf"');
            header('Content-Length: ' . strlen($certificado));

            echo $certificado;
            exit;
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Error: No se encontró el certificado de calidad para este proveedor.";
        }
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo "Error: Solicitud no válida.";
    }
} catch (PDOException $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo "Error en la base de datos.";
}
?>
