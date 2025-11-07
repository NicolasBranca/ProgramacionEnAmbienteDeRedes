<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4',
    'u162024603_NicolasBranca',
    'Alcachofa189'
);

function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CodProveedor = $_POST['CodProveedor'];
    
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['archivo']['tmp_name'];
        $archivoData = file_get_contents($archivoTmp);

        $stmt = $pdo->prepare("UPDATE Proveedores SET CertificadosCalidad = :archivo WHERE CodProveedor = :CodProveedor");
        $stmt->execute([':archivo' => $archivoData, ':CodProveedor' => $CodProveedor]);
        echo 'Archivo subido exitosamente.';
    } else {
        echo 'No se subió ningún archivo.';
    }
}
?>
