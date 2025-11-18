<?php

// Datos de conexión a la base de datos
$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = 'Alcachofa189';

// Función para registrar mensajes en un archivo de log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Verifica si la petición es POST (envío del formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos enviados por el formulario
    $login = $_POST['login'];
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $clave = $_POST['clave'];

    // Registra en el log los datos recibidos (sin la clave)
    registrarLog("Datos recibidos para crear usuario: login = $login, apellido = $apellido, nombre = $nombre");

    // Encripta la contraseña usando SHA-256
    $hashedPassword = hash('sha256', $clave);
    registrarLog("Contraseña encriptada: $hashedPassword");

    try {
        try {
            // Crea la conexión PDO a la base de datos
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, $user, $pass, $options);
            registrarLog("Conexión a la base de datos exitosa.");
        } catch (PDOException $e) {
            // Si falla la conexión, lo registra y termina el script
            registrarLog("Error de conexión: " . $e->getMessage());
            die("Error de conexión: " . $e->getMessage());
        }
        
        // Prepara la consulta SQL para insertar el nuevo usuario
        $sql = "INSERT INTO Usuarios (login, apellido, nombre, clave) 
                VALUES (:login, :apellido, :nombre, :clave)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':clave', $hashedPassword);

        // Ejecuta la consulta y verifica si fue exitosa
        if ($stmt->execute()) {
            registrarLog("Usuario $login creado exitosamente.");
            echo "<p>Usuario registrado exitosamente.</p>";
            echo "<p><button onClick=\"location.href='./index.php'\">Ir a la aplicación</button></p>";
        } else {
            // Si falla la consulta, registra el error y muestra mensaje
            $errorInfo = $stmt->errorInfo();
            registrarLog("Error al ejecutar la consulta: " . json_encode($errorInfo));
            echo "<p>Error al registrar el usuario.</p>";
            echo "<p><button onClick=\"location.href='./formularioDeRegistro.php'\">Intentar de nuevo</button></p>";
        }
    } catch (PDOException $e) {
        // Si ocurre un error con PDO, lo registra y muestra mensaje
        registrarLog("Error en la base de datos: " . $e->getMessage());
        echo 'Error en la base de datos: ' . $e->getMessage();
    }
} else {
    // Si la petición no es POST, redirige al formulario de registro
    registrarLog("Solicitud no válida para crear usuario.");
    header('Location: ./formularioDeRegistro.php');
    exit();
}
?>
