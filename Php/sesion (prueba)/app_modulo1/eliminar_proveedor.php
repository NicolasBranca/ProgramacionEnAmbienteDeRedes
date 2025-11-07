<?php

function registrarLog($mensaje) {
    $logFile = '/tmp/debug.log'; 
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
    $pdo = new PDO($dsn, $username, $password, $options);

    //adaptar los campos del formulario a eliminar proveedor segun los campos de mi base de datos (tambien tener en cuenta los logs)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CodProveedor'])) {
        $CodProveedor = $_POST['CodProveedor'];

        $sql = "DELETE FROM Proveedores WHERE CodProveedor = :CodProveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);

        if ($stmt->execute()) {
            registrarLog("Proveedor eliminado: CodProveedor $CodProveedor");
            echo json_encode(["status" => "success", "message" => "Proveedor eliminado exitosamente."]);
        } else {
            registrarLog("Error al eliminar el proveedor: " . json_encode($stmt->errorInfo()));
            echo json_encode(["status" => "error", "message" => "Error al eliminar el proveedor."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se recibió un identificador válido."]);
    }
} catch (PDOException $e) {
    registrarLog("Error de conexión o ejecución: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
}
?>

