<?php
include('../manejoSesion.inc');
?>
<?php

$identificacionSesion = $_SESSION['sesionParaUsuario'];
$usuario = $_SESSION['login'];
$contadorSesion = $_SESSION['contador'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
</head>
<style>
body {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #eaf7f7;
}

.welcome-container {
    background-color: #fff;
    padding: 30px 50px;
    border-radius: 8px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    max-width: 400px;
    width: 100%;
}

.welcome-container h1 {
    margin-bottom: 20px;
    font-size: 26px;
    color: #007B7F;
}

.welcome-container p {
    margin: 10px 0;
    font-size: 16px;
    color: #333;
    text-align: left;
}

.welcome-container .highlight {
    font-weight: bold;
    color: #007B7F;
}

.welcome-container button {
    background-color: #007B7F;
    color: white;
    padding: 10px 20px;
    margin: 10px 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.welcome-container button:hover {
    background-color: #005f60;
}

@media (max-width: 600px) {
    .welcome-container {
        padding: 20px;
        max-width: 90%;
    }

    .welcome-container h1 {
        font-size: 22px;
    }

    .welcome-container p {
        font-size: 14px;
    }
}

</style>
<body>
    <div class="welcome-container">
        <h1>Acceso permitido</h1>
        <p><strong class="highlight">Identificación de sesión:</strong> <?php echo $identificacionSesion; ?></p>
        <p><strong class="highlight">Usuario:</strong> <?php echo $usuario; ?></p>
        <p><strong class="highlight">Contador de sesión para este usuario:</strong> <?php echo $contadorSesion; ?></p>

        <button onClick="location.href='./index.php'">Ingresar a la aplicación</button>
        <button onClick="location.href='./destruirsesion.php'">Terminar sesión</button>
    </div>
</body>
</html>
