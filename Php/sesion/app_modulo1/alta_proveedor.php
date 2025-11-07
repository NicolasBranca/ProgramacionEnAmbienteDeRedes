<?php

// Datos de conexión para tu base de datos
$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = 'Alcachofa189';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Adaptar para alta de proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['CertificadosCalidad'])) {

    $RazonSocial = $_POST['RazonSocial'];
    $CUIT = $_POST['CUIT'];
    $idIVA = $_POST['idIVA'];
    $SaldoCuentaCorriente = isset($_POST['SaldoCuentaCorriente']) ? $_POST['SaldoCuentaCorriente'] : 0.00;

    // Validar formato CUIT: XX-XXXXXXXX-X
    if (!preg_match('/^\d{2}-\d{8}-\d{1}$/', $CUIT)) {
        echo json_encode(["status" => "error", "message" => "El CUIT debe tener el formato XX-XXXXXXXX-X"]);
        exit;
    }

    $certificados = $_FILES['CertificadosCalidad'];
    $fileTmpName = $certificados['tmp_name'];
    $fileError = $certificados['error'];
    $fileSize = $certificados['size'];

    if ($fileError === 0) {
        if ($fileSize < 5000000) {
            $fileData = file_get_contents($fileTmpName);

            try {
                $sql = "INSERT INTO Proveedores (RazonSocial, CUIT, idIVA, SaldoCuentaCorriente, CertificadosCalidad) 
                        VALUES (:RazonSocial, :CUIT, :idIVA, :SaldoCuentaCorriente, :CertificadosCalidad)";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':RazonSocial', $RazonSocial);
                $stmt->bindParam(':CUIT', $CUIT);
                $stmt->bindParam(':idIVA', $idIVA, PDO::PARAM_INT);
                $stmt->bindParam(':SaldoCuentaCorriente', $SaldoCuentaCorriente);
                $stmt->bindParam(':CertificadosCalidad', $fileData, PDO::PARAM_LOB);

                if ($stmt->execute()) {
                    $logMessage = "Proveedor agregado exitosamente: CUIT $CUIT";
                    registrarLog($logMessage);

                    echo json_encode(["status" => "success", "message" => "Proveedor dado de alta exitosamente."]);
                } else {
                    $logMessage = "Error al agregar el proveedor: " . json_encode($stmt->errorInfo());
                    registrarLog($logMessage);

                    echo json_encode(["status" => "error", "message" => "Error al dar de alta el proveedor."]);
                }

            } catch (PDOException $e) {
                registrarLog("Error de PDO: " . $e->getMessage());
                echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
            }

        } else {
            echo json_encode(["status" => "error", "message" => "El archivo es demasiado grande."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error al subir el archivo."]);
    }
}

// Ajustar el log para este módulo (alta de proveedores)
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log'; 
    $fecha = date('Y-m-d H:i:s'); 
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
?>
