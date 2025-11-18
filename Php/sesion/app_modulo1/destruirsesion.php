<?php
// Inicia o reanuda la sesión actual
session_start();
// Elimina todas las variables de sesión
session_unset();
// Destruye la sesión actual
session_destroy();
// Redirige al usuario al formulario de login
header('Location: ./formularioDeLogin.php');
// Finaliza el script
exit();
?>
