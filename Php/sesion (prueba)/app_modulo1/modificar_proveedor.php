<?php
header('Content-Type: application/json');

function registrarLog($mensaje) {
    $logFile = '/tmp/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

//adaptar los datos de conexion a la base de datos
$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'rootu162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    //adaptar los campos del formulario a modificar proveedor segun el formato de la base de datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CodProveedor'])) {
        $CodProveedor = $_POST['CodProveedor'];
        $RazonSocial = $_POST['RazonSocial'];
        $CUIT = $_POST['CUIT'];
        $idIVA = $_POST['idIVA'];
        $SaldoCuentaCorriente = isset($_POST['SaldoCuentaCorriente']) ? $_POST['SaldoCuentaCorriente'] : 0.00;

        registrarLog("Modificación iniciada para CodProveedor: $CodProveedor");
        registrarLog("idIVA seleccionado para modificar: $idIVA");

        if (isset($_FILES['CertificadosCalidad']) && $_FILES['CertificadosCalidad']['error'] === 0) {
            $fileTmpName = $_FILES['CertificadosCalidad']['tmp_name'];
            $fileData = file_get_contents($fileTmpName);

            registrarLog("Archivo recibido para CertificadosCalidad, tamaño: " . strlen($fileData) . " bytes");

            $sql = "UPDATE Proveedores SET RazonSocial = :RazonSocial, CUIT = :CUIT, idIVA = :idIVA, 
                    SaldoCuentaCorriente = :SaldoCuentaCorriente, CertificadosCalidad = :CertificadosCalidad 
                    WHERE CodProveedor = :CodProveedor";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':CertificadosCalidad', $fileData, PDO::PARAM_LOB);
            registrarLog("Actualizando el proveedor con el archivo CertificadosCalidad.");
        } else {
            $sql = "UPDATE Proveedores SET RazonSocial = :RazonSocial, CUIT = :CUIT, idIVA = :idIVA, 
                    SaldoCuentaCorriente = :SaldoCuentaCorriente 
                    WHERE CodProveedor = :CodProveedor";
            $stmt = $pdo->prepare($sql);

            registrarLog("Actualizando el proveedor sin modificar el archivo CertificadosCalidad.");
        }

        $stmt->bindParam(':RazonSocial', $RazonSocial, PDO::PARAM_STR);
        $stmt->bindParam(':CUIT', $CUIT, PDO::PARAM_STR);
        $stmt->bindParam(':idIVA', $idIVA, PDO::PARAM_INT);
        $stmt->bindParam(':SaldoCuentaCorriente', $SaldoCuentaCorriente);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);

        if ($stmt->execute()) {
            registrarLog("Proveedor modificado exitosamente para CodProveedor: $CodProveedor");
            echo json_encode(["status" => "success", "message" => "Proveedor modificado exitosamente."]);
        } else {
            registrarLog("Error al modificar el proveedor: " . json_encode($stmt->errorInfo()));
            echo json_encode(["status" => "error", "message" => "Error al modificar el proveedor."]);
        }
    } else {
        registrarLog("Solicitud no válida o falta CodProveedor.");
        echo json_encode(["status" => "error", "message" => "Solicitud no válida."]);
    }
} catch (PDOException $e) {
    registrarLog("Error en la base de datos: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
}
?>
