<?php
$dsn = 'mysql:host=localhost;dbname=futbol;charset=utf8mb4';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    $query = "SELECT identificadorPartido, descripcion, estadio_id, golesTotales, fechaPartido FROM PartidoFutbol WHERE 1=1";
    
    $conditions = [];
    $params = [];
    
    if (!empty($_POST['identificadorPartido'])) {
        $conditions[] = "identificadorPartido LIKE :identificadorPartido";
        $params[':identificadorPartido'] = '%' . $_POST['identificadorPartido'] . '%';
    }

    if (!empty($_POST['descripcion'])) {
        $conditions[] = "descripcion LIKE :descripcion";
        $params[':descripcion'] = '%' . $_POST['descripcion'] . '%';
    }

    if (!empty($_POST['estadio_id'])) {
        $conditions[] = "estadio_id = :estadio_id";
        $params[':estadio_id'] = $_POST['estadio_id'];
    }

    if (!empty($_POST['golesTotales'])) {
        $conditions[] = "golesTotales = :golesTotales";
        $params[':golesTotales'] = $_POST['golesTotales'];
    }

    if (!empty($_POST['fechaPartido'])) {
        $conditions[] = "fechaPartido = :fechaPartido";
        $params[':fechaPartido'] = $_POST['fechaPartido'];
    }

    if (count($conditions) > 0) {
        $query .= " AND " . implode(' AND ', $conditions);
    }

    if (!empty($_POST['sort_column'])) {
        $query .= " ORDER BY " . $_POST['sort_column'];
    }

    $stmt = $pdo->prepare($query);

    $stmt->execute($params);

    $result = $stmt->fetchAll();

    echo json_encode($result);

} catch (PDOException $e) {
    file_put_contents('/tmp/debug.log', "[" . date('Y-m-d H:i:s') . "] Error en la base de datos: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['error' => 'Error en la base de datos']);
}
