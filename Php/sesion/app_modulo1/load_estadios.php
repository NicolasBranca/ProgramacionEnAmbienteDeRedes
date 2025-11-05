<?php
$dsn = 'mysql:host=localhost;dbname=futbol;charset=utf8';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT DISTINCT estadio_id FROM PartidoFutbol WHERE estadio_id IS NOT NULL AND estadio_id != ''";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $estadios = $stmt->fetchAll();
    
    echo json_encode($estadios);

} catch (PDOException $e) {
    file_put_contents('/tmp/debug.log', '[' . date('Y-m-d H:i:s') . '] Error al obtener estadios: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['error' => 'Error al obtener los estadios']);
}
?>
