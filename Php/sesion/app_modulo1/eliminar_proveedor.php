<?php

// Función para registrar mensajes en un archivo de log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Configuración de la base de datos (adaptada a tu base de datos)
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
        $CodProveedor = $_POST['CodProveedor'];

        // Prepara la consulta SQL para eliminar el proveedor por su código
        $sql = "DELETE FROM Proveedores WHERE CodProveedor = :CodProveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);

        // Ejecuta la consulta y verifica si fue exitosa
        if ($stmt->execute()) {
            registrarLog("Proveedor eliminado: CodProveedor $CodProveedor");
            echo json_encode(["status" => "success", "message" => "Proveedor eliminado exitosamente."]);
        } else {
            // Si falla la consulta, registra el error y muestra mensaje
            registrarLog("Error al eliminar el proveedor: " . json_encode($stmt->errorInfo()));
            echo json_encode(["status" => "error", "message" => "Error al eliminar el proveedor."]);
        }
    } else {
        // Si no se recibió un identificador válido, muestra mensaje de error
        echo json_encode(["status" => "error", "message" => "No se recibió un identificador válido."]);
    }
} catch (PDOException $e) {
    // Si ocurre un error con PDO, lo registra y muestra mensaje de error
    registrarLog("Error de conexión o ejecución: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
}
?>

