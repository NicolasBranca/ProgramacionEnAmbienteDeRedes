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
        }
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $result = $stmt->fetchAll();

    echo json_encode($result);

} catch (PDOException $e) {
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Error en la base de datos: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['error' => 'Error en la base de datos']);
}
