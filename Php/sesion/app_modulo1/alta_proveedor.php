<?php

// Configuración de conexión a la base de datos
$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = 'Alcachofa189';

try {
    // Crea una nueva conexión PDO a la base de datos
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve resultados como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa sentencias preparadas nativas
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    // Si falla la conexión, muestra el error y termina el script
    die("Error de conexión: " . $e->getMessage());
}

// Procesa el alta de proveedor si la petición es POST y se subió un archivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['CertificadosCalidad'])) {

    // Obtiene y limpia los datos enviados por el formulario
    $RazonSocial = isset($_POST['RazonSocial']) ? trim($_POST['RazonSocial']) : '';
    $CUIT = isset($_POST['CUIT']) ? trim($_POST['CUIT']) : '';
    $idIVA = isset($_POST['idIVA']) ? trim($_POST['idIVA']) : '';
    $SaldoCuentaCorriente = isset($_POST['SaldoCuentaCorriente']) ? trim($_POST['SaldoCuentaCorriente']) : '';

    // Valida que todos los campos obligatorios estén completos y sean válidos
    if ($RazonSocial === '' || $CUIT === '' || $idIVA === '' || $SaldoCuentaCorriente === '' || !is_numeric($SaldoCuentaCorriente)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y deben ser válidos."]);
        exit;
    }

    // Valida el formato del CUIT (XX-XXXXXXXX-X)
    if (!preg_match('/^\d{2}-\d{8}-\d{1}$/', $CUIT)) {
        echo json_encode(["status" => "error", "message" => "El CUIT debe tener el formato XX-XXXXXXXX-X"]);
        exit;
    }

    // Verifica que el CUIT no exista ya en la base de datos
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM Proveedores WHERE CUIT = :CUIT");
    $stmtCheck->bindParam(':CUIT', $CUIT);
    $stmtCheck->execute();
    if ($stmtCheck->fetchColumn() > 0) {
        echo json_encode(["status" => "error", "message" => "Ya existe un proveedor con ese CUIT."]);
        exit;
    }

    // Procesa el archivo subido (certificado de calidad)
    $certificados = $_FILES['CertificadosCalidad'];
    $fileTmpName = $certificados['tmp_name'];
    $fileError = $certificados['error'];
    $fileSize = $certificados['size'];

    // Si el archivo es válido y menor a 5MB, lo lee; si no, lo deja como null
    if ($fileError === 0 && $fileSize < 5000000) {
        $fileData = file_get_contents($fileTmpName);
    } else {
        $fileData = null;
    }

    try {
        // Prepara la consulta SQL para insertar el nuevo proveedor
        $sql = "INSERT INTO Proveedores (RazonSocial, CUIT, idIVA, SaldoCuentaCorriente, CertificadosCalidad) 
                VALUES (:RazonSocial, :CUIT, :idIVA, :SaldoCuentaCorriente, :CertificadosCalidad)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':RazonSocial', $RazonSocial);
        $stmt->bindParam(':CUIT', $CUIT);
        $stmt->bindParam(':idIVA', $idIVA, PDO::PARAM_INT);
        $stmt->bindParam(':SaldoCuentaCorriente', $SaldoCuentaCorriente);
        $stmt->bindParam(':CertificadosCalidad', $fileData, PDO::PARAM_LOB);

        // Ejecuta la consulta y registra el resultado en el log
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
        // Si ocurre un error con PDO, lo registra en el log y responde con error
        registrarLog("Error de PDO: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
    }
}

// Función para registrar mensajes en el archivo debug.log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log'; // Ruta del archivo de log
    $fecha = date('Y-m-d H:i:s'); // Fecha y hora actual
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL; // Mensaje formateado
    // Intenta escribir el mensaje en el archivo de log
    try {
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        // Si ocurre un error al escribir el log, lo ignora
    }
}
?>
