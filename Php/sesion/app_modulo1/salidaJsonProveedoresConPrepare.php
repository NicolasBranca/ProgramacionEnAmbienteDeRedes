<?php
header('Content-Type: application/json');

$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = 'Alcachofa189';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT 
            p.CodProveedor, 
            p.RazonSocial, 
            p.CUIT, 
            c.tipoIVA, 
            p.SaldoCuentaCorriente, 
            (p.CertificadosCalidad IS NOT NULL AND LENGTH(p.CertificadosCalidad) > 0) AS tieneCertificado
        FROM Proveedores p
        LEFT JOIN CondicionIVA c ON p.idIVA = c.idIVA";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$proveedores = $stmt->fetchAll();

echo json_encode($proveedores);
