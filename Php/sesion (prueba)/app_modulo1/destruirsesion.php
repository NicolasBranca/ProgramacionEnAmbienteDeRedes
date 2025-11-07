<?php
include('../manejoSesion.inc');
session_start();
session_unset();
session_destroy();
header('Location: ./formularioDeLogin.php');
exit();
?>
