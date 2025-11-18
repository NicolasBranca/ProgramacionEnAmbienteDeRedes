<?php
header('Content-Type: application/json'); 


$dsn = 'mysql:host=localhost;dbname=u162024603_miBaseDeDatos;charset=utf8mb4';
$username = 'u162024603_NicolasBranca'; 
$password = 'Alcachofa189';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CodProveedor'])) {
        $CodProveedor = $_POST['CodProveedor'];
        $sql = "SELECT * FROM Proveedores WHERE CodProveedor = :CodProveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':CodProveedor', $CodProveedor, PDO::PARAM_INT);
        $stmt->execute();

        // Obtiene los datos del proveedor
        $proveedor = $stmt->fetch();

        if ($proveedor) {
            // Si se encuentra el proveedor, lo devuelve en formato JSON
            echo json_encode($proveedor);
        } else {
            // Si no se encuentra, devuelve un mensaje de error
            echo json_encode(["error" => "No se encontró el proveedor."]);
        }
    } else {
        echo json_encode(["error" => "Solicitud no válida."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
