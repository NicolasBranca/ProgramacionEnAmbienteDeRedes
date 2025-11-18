<?php
// Incluye el archivo para manejo de sesión
include('../manejoSesion.inc');
?>
<?php

// Obtiene el identificador de la sesión actual
$identificacionSesion = $_SESSION['sesionParaUsuario'];
// Obtiene el nombre de usuario de la sesión
$login = $_SESSION['login'];
// Obtiene el contador de accesos de la sesión para el usuario
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
/* Estilos generales para centrar el contenido y dar formato */
body {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #eaf7f7;
}

/* Contenedor principal de bienvenida */
.welcome-container {
    background-color: #fff;
    padding: 30px 50px;
    border-radius: 8px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    max-width: 400px;
    width: 100%;
}

/* Estilo para el título */
.welcome-container h1 {
    margin-bottom: 20px;
    font-size: 26px;
    color: #007B7F;
}

/* Estilo para los párrafos */
.welcome-container p {
    margin: 10px 0;
    font-size: 16px;
    color: #333;
    text-align: left;
}

/* Resalta información importante */
.welcome-container .highlight {
    font-weight: bold;
    color: #007B7F;
}

/* Estilo para los botones */
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

/* Efecto hover en los botones */
.welcome-container button:hover {
    background-color: #005f60;
}

/* Estilos responsivos para pantallas pequeñas */
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
    <!-- Contenedor de bienvenida con información de la sesión -->
    <div class="welcome-container">
        <h1>Acceso permitido</h1>
        <!-- Muestra el identificador de la sesión -->
        <p><strong class="highlight">Identificación de sesión:</strong> <?php echo $identificacionSesion; ?></p>
        <!-- Muestra el nombre de usuario -->
        <p><strong class="highlight">Usuario:</strong> <?php echo $login; ?></p>
        <!-- Muestra el contador de accesos de la sesión -->
        <p><strong class="highlight">Contador de sesión para este usuario:</strong> <?php echo $contadorSesion; ?></p>

        <!-- Botón para ingresar a la aplicación -->
        <button onClick="location.href='./index.php'">Ingresar a la aplicación</button>
        <!-- Botón para cerrar la sesión -->
        <button onClick="location.href='./destruirsesion.php'">Terminar sesión</button>
    </div>
</body>
</html>
