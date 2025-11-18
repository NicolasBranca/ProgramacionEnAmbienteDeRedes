<?php

// Configuración de la conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Función para registrar mensajes en el archivo debug.log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

try {
    // Crea una nueva conexión PDO a la base de datos
    $pdo = new PDO($dsn, $username, $password, $options);

    // Consulta para obtener los tipos de IVA disponibles
    $sql = "SELECT idIVA, tipoIVA, porcentaje FROM CondicionIVA";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtiene todos los resultados y los devuelve en formato JSON
    $ivas = $stmt->fetchAll();
    
    echo json_encode($ivas);

} catch (PDOException $e) {
    // Si ocurre un error, lo registra en el log y devuelve un error en JSON
    registrarLog('Error al obtener IVA: ' . $e->getMessage());
    echo json_encode(['error' => 'Error al obtener los tipos de IVA']);
}
?>
