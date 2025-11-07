<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
</head>
<body>
    <h1>Crear Nuevo Usuario</h1>
    <form action="crear_usuario.php" method="POST">
        <label for="login">Usuario:</label>
        <input type="text" id="login" name="login" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required><br><br>

        <button type="submit">Registrar Usuario</button>
    </form>
</body>
</html>
