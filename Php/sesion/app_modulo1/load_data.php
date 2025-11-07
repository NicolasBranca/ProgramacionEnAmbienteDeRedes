<?php

$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    $query = "SELECT CodProveedor, RazonSocial, CUIT, idIVA, SaldoCuentaCorriente FROM Proveedores WHERE 1=1";

    $conditions = [];
    $params = [];

    if (!empty($_POST['CodProveedor'])) {
        $conditions[] = "CodProveedor LIKE :CodProveedor";
        $params[':CodProveedor'] = '%' . $_POST['CodProveedor'] . '%';
    }

    if (!empty($_POST['RazonSocial'])) {
        $conditions[] = "RazonSocial LIKE :RazonSocial";
        $params[':RazonSocial'] = '%' . $_POST['RazonSocial'] . '%';
    }

    if (!empty($_POST['CUIT'])) {
        $conditions[] = "CUIT LIKE :CUIT";
        $params[':CUIT'] = '%' . $_POST['CUIT'] . '%';
    }

    if (!empty($_POST['idIVA'])) {
        $conditions[] = "idIVA = :idIVA";
        $params[':idIVA'] = $_POST['idIVA'];
    }

    if (!empty($_POST['SaldoCuentaCorriente'])) {
        $conditions[] = "SaldoCuentaCorriente = :SaldoCuentaCorriente";
        $params[':SaldoCuentaCorriente'] = $_POST['SaldoCuentaCorriente'];
    }

    if (count($conditions) > 0) {
        $query .= " AND " . implode(' AND ', $conditions);
    }

    if (!empty($_POST['sort_column'])) {
        $allowedSort = ['CodProveedor', 'RazonSocial', 'CUIT', 'idIVA', 'SaldoCuentaCorriente'];
        if (in_array($_POST['sort_column'], $allowedSort)) {
            $query .= " ORDER BY " . $_POST['sort_column'];
            // Nuevo: soporte para ASC/DESC
            $direction = 'ASC';
            if (!empty($_POST['sort_direction']) && in_array(strtoupper($_POST['sort_direction']), ['ASC', 'DESC'])) {
                $direction = strtoupper($_POST['sort_direction']);
            }
            $query .= " " . $direction;
        }
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $result = $stmt->fetchAll();

    echo json_encode($result);

} catch (PDOException $e) {
    registrarLog("Error en la base de datos: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la base de datos']);
}

function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
