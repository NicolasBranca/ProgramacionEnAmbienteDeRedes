<?php
include('../manejoSesion.inc');

$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT idIVA, tipoIVA, porcentaje FROM CondicionIVA";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $ivas = $stmt->fetchAll();
    
    echo json_encode($ivas);

} catch (PDOException $e) {
    file_put_contents('debug.log', '[' . date('Y-m-d H:i:s') . '] Error al obtener IVA: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['error' => 'Error al obtener los tipos de IVA']);
}
?>
