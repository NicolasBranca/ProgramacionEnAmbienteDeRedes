<?php
header('Content-Type: application/json');

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

        $sql = "SELECT * FROM PartidoFutbol WHERE identificadorPartido = :identificadorPartido";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':identificadorPartido', $identificadorPartido);
        $stmt->execute();

        $partido = $stmt->fetch();

        if ($partido) {
            echo json_encode($partido);
        } else {
            echo json_encode(["error" => "No se encontró el partido."]);
        }
    } else {
        echo json_encode(["error" => "Solicitud no válida."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
