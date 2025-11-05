<?php
$pdo = new PDO('mysql:host=localhost;dbname=futbol', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partidoId = $_POST['partidoId'];
    
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['archivo']['tmp_name'];
        $archivoNombre = $_FILES['archivo']['name'];
        $archivoRuta = 'uploads/' . $archivoNombre;
        
        if (move_uploaded_file($archivoTmp, $archivoRuta)) {
            $stmt = $pdo->prepare("UPDATE PartidoFutbol SET archivo = :archivo WHERE idPartido = :idPartido");
            $stmt->execute([':archivo' => $archivoRuta, ':idPartido' => $partidoId]);
            echo 'Archivo subido exitosamente.';
        } else {
            echo 'Error al mover el archivo.';
        }
    } else {
        echo 'No se subió ningún archivo.';
    }
}
?>
