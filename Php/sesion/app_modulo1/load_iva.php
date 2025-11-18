<?php

$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // Consulta para obtener los tipos de IVA disponibles
    $sql = "SELECT idIVA, tipoIVA, porcentaje FROM CondicionIVA";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtiene todos los resultados y los devuelve en formato JSON
    $ivas = $stmt->fetchAll();
    
    echo json_encode($ivas);

} catch (PDOException $e) {
    registrarLog('Error al obtener IVA: ' . $e->getMessage());
    echo json_encode(['error' => 'Error al obtener los tipos de IVA']);
}
?>
