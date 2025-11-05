<?php
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

if (!isset($_GET['cod'])) {
    http_response_code(400);
    echo "Falta el parámetro cod";
    exit;
}

$cod = $_GET['cod'];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo "Error de conexión";
    exit;
}

$stmt = $pdo->prepare("SELECT CertificadosCalidad FROM Proveedores WHERE CodProveedor = ?");
$stmt->execute([$cod]);
$row = $stmt->fetch();

if (!$row || empty($row['CertificadosCalidad'])) {
    http_response_code(404);
    echo "No se encontró el certificado";
    exit;
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="certificado_' . $cod . '.pdf"');
echo $row['CertificadosCalidad'];
