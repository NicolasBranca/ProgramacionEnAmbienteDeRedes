<?php
header('Content-Type: application/json');

// Función para registrar mensajes en el archivo debug.log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

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

    // Verifica si la petición es POST y si se recibió el identificador del proveedor
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CodProveedor'])) {
        $CodProveedor = isset($_POST['CodProveedor']) ? trim($_POST['CodProveedor']) : '';
        $RazonSocial = isset($_POST['RazonSocial']) ? trim($_POST['RazonSocial']) : '';
        $CUIT = isset($_POST['CUIT']) ? trim($_POST['CUIT']) : '';
        $idIVA = isset($_POST['idIVA']) ? trim($_POST['idIVA']) : '';
        $SaldoCuentaCorriente = isset($_POST['SaldoCuentaCorriente']) ? trim($_POST['SaldoCuentaCorriente']) : '';

        // Valida que todos los campos obligatorios estén completos y sean válidos
        if ($CodProveedor === '' || $RazonSocial === '' || $CUIT === '' || $idIVA === '' || $SaldoCuentaCorriente === '' || !is_numeric($SaldoCuentaCorriente)) {
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y deben ser válidos."]);
            exit;
        }

        // Valida el formato del CUIT (XX-XXXXXXXX-X)
        if (!preg_match('/^\d{2}-\d{8}-\d{1}$/', $CUIT)) {
            echo json_encode(["status" => "error", "message" => "El CUIT debe tener el formato XX-XXXXXXXX-X"]);
            exit;
        }

        registrarLog("Modificación iniciada para CodProveedor: $CodProveedor");
        registrarLog("idIVA seleccionado para modificar: $idIVA");

        // Si se envía un archivo de certificado, lo procesa y actualiza
        if (isset($_FILES['CertificadosCalidad']) && $_FILES['CertificadosCalidad']['error'] === 0) {
            $fileTmpName = $_FILES['CertificadosCalidad']['tmp_name'];
            $fileSize = $_FILES['CertificadosCalidad']['size'];

            // Valida que el archivo sea válido
            if ($fileTmpName === '' || $fileSize === 0) {
                echo json_encode(["status" => "error", "message" => "Debe adjuntar un archivo de certificado válido."]);
                exit;
            }

            $fileData = file_get_contents($fileTmpName);

            registrarLog("Archivo recibido para CertificadosCalidad, tamaño: " . strlen($fileData) . " bytes");

            // Consulta para actualizar proveedor incluyendo el archivo
            $sql = "UPDATE Proveedores SET RazonSocial = :RazonSocial, CUIT = :CUIT, idIVA = :idIVA, 
                    SaldoCuentaCorriente = :SaldoCuentaCorriente, CertificadosCalidad = :CertificadosCalidad 
                    WHERE CodProveedor = :CodProveedor";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':CertificadosCalidad', $fileData, PDO::PARAM_LOB);
            registrarLog("Actualizando el proveedor con el archivo CertificadosCalidad.");
        } else {
            // Consulta para actualizar proveedor sin modificar el archivo
            $sql = "UPDATE Proveedores SET RazonSocial = :RazonSocial, CUIT = :CUIT, idIVA = :idIVA, 
                    SaldoCuentaCorriente = :SaldoCuentaCorriente 
                    WHERE CodProveedor = :CodProveedor";
            $stmt = $pdo->prepare($sql);

            registrarLog("Actualizando el proveedor sin modificar el archivo CertificadosCalidad.");
        }

        // Asigna los parámetros a la consulta
        $stmt->bindParam(':RazonSocial', $RazonSocial, PDO::PARAM_STR);
        $stmt->bindParam(':CUIT', $CUIT, PDO::PARAM_STR);
        $stmt->bindParam(':idIVA', $idIVA, PDO::PARAM_INT);
        $stmt->bindParam(':SaldoCuentaCorriente', $SaldoCuentaCorriente);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);

        // Ejecuta la consulta y verifica si fue exitosa
        if ($stmt->execute()) {
            registrarLog("Proveedor modificado exitosamente para CodProveedor: $CodProveedor");
            echo json_encode(["status" => "success", "message" => "Proveedor modificado exitosamente."]);
        } else {
            // Si falla la consulta, registra el error y muestra mensaje
            registrarLog("Error al modificar el proveedor: " . json_encode($stmt->errorInfo()));
            echo json_encode(["status" => "error", "message" => "Error al modificar el proveedor."]);
        }
    } else {
        // Si la solicitud no es válida o falta el identificador, muestra mensaje de error
        registrarLog("Solicitud no válida o falta CodProveedor.");
        echo json_encode(["status" => "error", "message" => "Solicitud no válida."]);
    }
} catch (PDOException $e) {
    // Si ocurre un error con PDO, lo registra y muestra mensaje de error
    registrarLog("Error en la base de datos: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
}
?>
