<?php
$host = 'localhost';
$db = 'futbol';
$user = 'root';
$pass = '';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resumen'])) {

    $identificadorPartido = $_POST['identificadorPartido'];
    $descripcion = $_POST['descripcion'];
    $estadio_id = $_POST['estadio_id'];
    $golesTotales = $_POST['golesTotales'];
    $fechaPartido = $_POST['fechaPartido'];

    $resumen = $_FILES['resumen'];
    $fileTmpName = $resumen['tmp_name'];
    $fileError = $resumen['error'];
    $fileSize = $resumen['size'];

    if ($fileError === 0) {
        if ($fileSize < 5000000) {
            $fileData = file_get_contents($fileTmpName);

            try {
                $sql = "INSERT INTO PartidoFutbol (identificadorPartido, descripcion, estadio_id, golesTotales, fechaPartido, resumen) 
                        VALUES (:identificadorPartido, :descripcion, :estadio_id, :golesTotales, :fechaPartido, :resumen)";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':identificadorPartido', $identificadorPartido);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':estadio_id', $estadio_id, PDO::PARAM_INT);
                $stmt->bindParam(':golesTotales', $golesTotales, PDO::PARAM_INT);
                $stmt->bindParam(':fechaPartido', $fechaPartido);
                $stmt->bindParam(':resumen', $fileData, PDO::PARAM_LOB);

                if ($stmt->execute()) {
                    $logMessage = "Partido agregado exitosamente: ID $identificadorPartido";
                    registrarLog($logMessage);

                    echo json_encode(["status" => "success", "message" => "Partido dado de alta exitosamente."]);
                } else {
                    $logMessage = "Error al agregar el partido: " . json_encode($stmt->errorInfo());
                    registrarLog($logMessage);

                    echo json_encode(["status" => "error", "message" => "Error al dar de alta el partido."]);
                }

            } catch (PDOException $e) {
                registrarLog("Error de PDO: " . $e->getMessage());
                echo json_encode(["status" => "error", "message" => "Error en la base de datos."]);
            }

        } else {
            echo json_encode(["status" => "error", "message" => "El archivo es demasiado grande."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error al subir el archivo."]);
    }
}

function registrarLog($mensaje) {
    $logFile = '/tmp/alta_partido.log'; 
    $fecha = date('Y-m-d H:i:s'); 
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

?>
