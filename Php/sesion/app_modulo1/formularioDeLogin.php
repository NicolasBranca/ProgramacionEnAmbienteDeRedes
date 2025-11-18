<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Encabezado con metadatos y título de la página -->
</head>
<style>
/* Estilos generales para centrar el formulario y dar formato */
body {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
}

/* Contenedor principal del formulario de login */
.form-container {
    background-color: #fff;
    padding: 20px 40px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
}

/* Estilo para el título del formulario */
.form-container h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #007B7F; 
}

/* Estilo para las etiquetas de los campos */
.form-container label {
    display: block;
    margin-bottom: 5px;
    text-align: left;
    font-weight: bold;
    color: #333;
}

/* Estilo para los campos de entrada de texto y contraseña */
.form-container input[type="text"],
.form-container input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Estilo para el botón de enviar */
.form-container button {
    background-color: #007B7F;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    margin-top: 10px;
}

/* Efecto hover en el botón */
.form-container button:hover {
    background-color: #005f60; 
}

/* Estilo para mensajes de error */
.message {
    margin-top: 10px;
    font-size: 14px;
    color: red;
}

/* Estilos responsivos para pantallas pequeñas */
@media (max-width: 600px) {
    .form-container {
        width: 90%;
        padding: 20px;
    }
}

</style>
<body>
<!-- Contenedor del formulario de inicio de sesión -->
<div class="form-container">
        <h1>Iniciar Sesión</h1>
        <!-- Formulario que envía los datos a login.php por POST -->
        <form action="login.php" method="POST">
            <!-- Campo para el usuario -->
            <label for="login">Usuario: usuario1</label>
            <input type="text" id="login" name="login" required>
            
            <!-- Campo para la contraseña -->
            <label for="password">Contraseña: root</label>
            <input type="password" id="password" name="password" required>
            
            <!-- Botón para enviar el formulario -->
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
