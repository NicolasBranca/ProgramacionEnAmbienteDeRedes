<?php
// Inicia o reanuda la sesión actual
session_start();

// Verifica si existe una sesión válida para el usuario
if (!isset($_SESSION['sesionParaUsuario'])) {
    // Si no hay sesión, redirige al formulario de login
    header('Location: ./formularioDeLogin.php');
    exit();
}
?>
