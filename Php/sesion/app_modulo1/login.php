<?php
// Inicia o reanuda la sesión actual
session_start();

// Configuración de conexión a la base de datos
$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = 'Alcachofa189';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Función para registrar mensajes en el archivo debug.log
function registrarLog($mensaje) {
    $logFile = __DIR__ . '/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Función para autenticar usuario y actualizar contador de sesión
function autenticacion($log, $cl) {
    global $host, $db, $user, $pass, $options;
    
    try {
        // Crea una nueva conexión PDO a la base de datos
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, $options);

        // Encripta la contraseña ingresada
        $hashedPassword = hash('sha256', $cl);

        // Consulta para buscar usuario con login y clave
        $sql = "SELECT * FROM Usuarios WHERE login = :login AND clave = :clave";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $log);
        $stmt->bindParam(':clave', $hashedPassword);
        $stmt->execute();

        // Obtiene los datos del usuario si existe
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Si existe, incrementa el contador de sesión y lo actualiza en la base
            $contadorActual = $usuario['contadorSesion'] + 1;
            $sqlUpdate = "UPDATE Usuarios SET contadorSesion = :contador WHERE login = :login";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':contador', $contadorActual, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':login', $log);
            $stmtUpdate->execute();

            registrarLog("Usuario $log autenticado exitosamente. Contador actualizado a $contadorActual.");

            // Guarda datos relevantes en la sesión
            $_SESSION['sesionParaUsuario'] = session_create_id();
            $_SESSION['login'] = $log;
            $_SESSION['contador'] = $contadorActual;

            return true;
        } else {
            // Si no existe, registra intento fallido
            registrarLog("Credenciales incorrectas para login: $log.");
            return false;
        }
    } catch (PDOException $e) {
        // Si ocurre un error con PDO, lo registra y retorna false
        registrarLog("Error en la base de datos durante la autenticación: " . $e->getMessage());
        return false;
    }
}

// Verifica si se recibieron usuario y contraseña por POST
if (!empty($_POST['login']) && !empty($_POST['password'])) {
    $log = $_POST['login'];
    $cl = $_POST['password'];

    // Registra intento de inicio de sesión
    registrarLog("Intento de inicio de sesión con login: $log");

    // Si la autenticación falla, redirige al formulario de login
    if (!autenticacion($log, $cl)) {
        registrarLog("Fallo el inicio de sesión para el usuario: $log");
        header('Location: ./formularioDeLogin.php');
        exit();
    }

    // Si la autenticación es exitosa, redirige a la página de bienvenida
    registrarLog("Sesión iniciada para el usuario: $log.");
    header('Location: ./bienvenida.php');
    exit();
} else {
    // Si la solicitud es inválida, redirige al formulario de login
    registrarLog("Solicitud de inicio de sesión inválida.");
    header('Location: ./formularioDeLogin.php');
    exit();
}
?>
