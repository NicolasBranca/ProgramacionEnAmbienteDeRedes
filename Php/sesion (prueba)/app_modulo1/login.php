<?php
session_start();

$host = 'localhost';
$db = 'futbol';
$user = 'root';
$pass = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

function registrarLog($mensaje) {
    $logFile = '/tmp/debug.log';
    $fecha = date('Y-m-d H:i:s');
    $logMessage = "[$fecha] - $mensaje" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

function autenticacion($log, $cl) {
    global $host, $db, $user, $pass, $options;
    
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, $options);

        $hashedPassword = hash('sha256', $cl);

        $sql = "SELECT * FROM Usuario WHERE loginUsuario = :login AND passwordUsuario = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $log);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        $usuario = $stmt->fetch();

        if ($usuario) {
            $contadorActual = $usuario['contadorSesiones'] + 1;
            $sqlUpdate = "UPDATE Usuario SET contadorSesiones = :contador WHERE loginUsuario = :login";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':contador', $contadorActual, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':login', $log);
            $stmtUpdate->execute();

            registrarLog("Usuario $log autenticado exitosamente. Contador actualizado a $contadorActual.");

            $_SESSION['sesionParaUsuario'] = session_create_id();
            $_SESSION['login'] = $log;
            $_SESSION['contador'] = $contadorActual;

            return true;
        } else {
            registrarLog("Credenciales incorrectas para login: $log.");
            return false;
        }
    } catch (PDOException $e) {
        registrarLog("Error en la base de datos durante la autenticación: " . $e->getMessage());
        return false;
    }
}

if (!empty($_POST['login']) && !empty($_POST['password'])) {
    $log = $_POST['login'];
    $cl = $_POST['password'];

    registrarLog("Intento de inicio de sesión con login: $log");

    if (!autenticacion($log, $cl)) {
        registrarLog("Fallo el inicio de sesión para el usuario: $log");
        header('Location: ./formularioDeLogin.php');
        exit();
    }

    registrarLog("Sesión iniciada para el usuario: $log.");
    header('Location: ./bienvenida.php');
    exit();
} else {
    registrarLog("Solicitud de inicio de sesión inválida.");
    header('Location: ./formularioDeLogin.php');
    exit();
}
?>
