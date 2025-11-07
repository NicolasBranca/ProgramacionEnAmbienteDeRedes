<?php
header('Content-Type: application/json');

function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

//adaptar los datos de conexion a la base de datos
$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    //adaptar los campos del formulario a modificar proveedor segun el formato de la base de datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CodProveedor'])) {
        $CodProveedor = isset($_POST['CodProveedor']) ? trim($_POST['CodProveedor']) : '';
        $RazonSocial = isset($_POST['RazonSocial']) ? trim($_POST['RazonSocial']) : '';
        $CUIT = isset($_POST['CUIT']) ? trim($_POST['CUIT']) : '';
        $idIVA = isset($_POST['idIVA']) ? trim($_POST['idIVA']) : '';
        $SaldoCuentaCorriente = isset($_POST['SaldoCuentaCorriente']) ? trim($_POST['SaldoCuentaCorriente']) : '';

        // Validar campos obligatorios
        if ($CodProveedor === '' || $RazonSocial === '' || $CUIT === '' || $idIVA === '' || $SaldoCuentaCorriente === '' || !is_numeric($SaldoCuentaCorriente)) {
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y deben ser válidos."]);
            exit;
        }

        // Validar formato CUIT: XX-XXXXXXXX-X
        if (!preg_match('/^\d{2}-\d{8}-\d{1}$/', $CUIT)) {
            echo json_encode(["status" => "error", "message" => "El CUIT debe tener el formato XX-XXXXXXXX-X"]);
            exit;
        }

        registrarLog("Modificación iniciada para CodProveedor: $CodProveedor");
        registrarLog("idIVA seleccionado para modificar: $idIVA");

        if (isset($_FILES['CertificadosCalidad']) && $_FILES['CertificadosCalidad']['error'] === 0) {
            $fileTmpName = $_FILES['CertificadosCalidad']['tmp_name'];
            $fileSize = $_FILES['CertificadosCalidad']['size'];

            // Validar archivo obligatorio si se envía
            if ($fileTmpName === '' || $fileSize === 0) {
                echo json_encode(["status" => "error", "message" => "Debe adjuntar un archivo de certificado válido."]);
                exit;
            }

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
