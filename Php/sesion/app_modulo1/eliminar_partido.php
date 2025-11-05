<?php

function registrarLog($mensaje) {
    $logFile = '/tmp/debug.log'; 
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

$dsn = 'mysql:host=localhost;dbname=futbol;charset=utf8mb4';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['identificadorPartido'])) {
        $identificadorPartido = $_POST['identificadorPartido'];

        $sql = "DELETE FROM PartidoFutbol WHERE identificadorPartido = :identificadorPartido";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':identificadorPartido', $identificadorPartido);

        if ($stmt->execute()) {
            registrarLog("Partido eliminado: ID $identificadorPartido");
            echo json_encode(["status" => "success", "message" => "Partido eliminado exitosamente."]);
        } else {
            registrarLog("Error al eliminar el partido: " . json_encode($stmt->errorInfo()));
            echo json_encode(["status" => "error", "message" => "Error al eliminar el partido."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se recibió un identificador válido."]);
    }
} catch (PDOException $e) {
    registrarLog("Error de conexión o ejecución: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
}
$conn = null;
?>

