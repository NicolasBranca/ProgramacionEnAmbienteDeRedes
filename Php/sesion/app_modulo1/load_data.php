<?php

// Configuración de la conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca';
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Crea una nueva conexión PDO a la base de datos
    $pdo = new PDO($dsn, $username, $password, $options);

    // Consulta base para obtener proveedores
    $query = "SELECT CodProveedor, RazonSocial, CUIT, idIVA, SaldoCuentaCorriente FROM Proveedores WHERE 1=1";

    $conditions = []; // Almacena condiciones de filtrado
    $params = [];     // Almacena parámetros para la consulta

    // Agrega condición si se filtra por código de proveedor
    if (!empty($_POST['CodProveedor'])) {
        $conditions[] = "CodProveedor LIKE :CodProveedor";
        $params[':CodProveedor'] = '%' . $_POST['CodProveedor'] . '%';
    }

    // Agrega condición si se filtra por razón social
    if (!empty($_POST['RazonSocial'])) {
        $conditions[] = "RazonSocial LIKE :RazonSocial";
        $params[':RazonSocial'] = '%' . $_POST['RazonSocial'] . '%';
    }

    // Agrega condición si se filtra por CUIT
    if (!empty($_POST['CUIT'])) {
        $conditions[] = "CUIT LIKE :CUIT";
        $params[':CUIT'] = '%' . $_POST['CUIT'] . '%';
    }

    // Agrega condición si se filtra por idIVA
    if (!empty($_POST['idIVA'])) {
        $conditions[] = "idIVA = :idIVA";
        $params[':idIVA'] = $_POST['idIVA'];
    }

    // Agrega condición si se filtra por saldo de cuenta corriente
    if (!empty($_POST['SaldoCuentaCorriente'])) {
        $conditions[] = "SaldoCuentaCorriente = :SaldoCuentaCorriente";
        $params[':SaldoCuentaCorriente'] = $_POST['SaldoCuentaCorriente'];
    }

    // Si hay condiciones, las agrega a la consulta
    if (count($conditions) > 0) {
        $query .= " AND " . implode(' AND ', $conditions);
    }

    // Si se solicita ordenamiento, lo agrega a la consulta
    if (!empty($_POST['sort_column'])) {
        $allowedSort = ['CodProveedor', 'RazonSocial', 'CUIT', 'idIVA', 'SaldoCuentaCorriente'];
        if (in_array($_POST['sort_column'], $allowedSort)) {
            $query .= " ORDER BY " . $_POST['sort_column'];
            // Soporte para dirección ASC/DESC
            $direction = 'ASC';
            if (!empty($_POST['sort_direction']) && in_array(strtoupper($_POST['sort_direction']), ['ASC', 'DESC'])) {
                $direction = strtoupper($_POST['sort_direction']);
            }
            $query .= " " . $direction;
        }
    }

    // Prepara y ejecuta la consulta con los parámetros
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Obtiene todos los resultados y los devuelve en formato JSON
    $result = $stmt->fetchAll();

    echo json_encode($result);

} catch (PDOException $e) {
    // Si ocurre un error, lo registra en el log y devuelve un error en JSON
    registrarLog("Error en la base de datos: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la base de datos']);
}

// Función para registrar mensajes en el archivo debug.log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
