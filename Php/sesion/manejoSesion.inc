<?php
session_start();

if (!isset($_SESSION['sesionParaUsuario'])) {
    header('Location: ./formularioDeLogin.php');
    exit();
}
?>
