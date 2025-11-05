<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razon = $_POST['RazonSocial'] ?? '';
    $cuit = $_POST['CUIT'] ?? '';
    $idIVA = $_POST['idIVA'] ?? '';
    $saldo = $_POST['SaldoCuentaCorriente'] ?? '';
    $cod = $_POST['CodProveedor'] ?? '';

    if ($razon && $cuit && $idIVA && $saldo !== '') {
        if ($cod === '') {
            // Insertar
            $stmt = $pdo->prepare("INSERT INTO Proveedores (RazonSocial, CUIT, idIVA, SaldoCuentaCorriente) VALUES (?, ?, ?, ?)");
            $stmt->execute([$razon, $cuit, $idIVA, $saldo]);
        } else {
            // Actualizar
            $stmt = $pdo->prepare("UPDATE Proveedores SET RazonSocial=?, CUIT=?, idIVA=?, SaldoCuentaCorriente=? WHERE CodProveedor=?");
            $stmt->execute([$razon, $cuit, $idIVA, $saldo, $cod]);
        }
    }
    header('Location: index.php');
    exit;
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $cod = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM Proveedores WHERE CodProveedor=?");
    $stmt->execute([$cod]);
    header('Location: index.php');
    exit;
}

// Obtener proveedores
$proveedores = $pdo->query("SELECT * FROM Proveedores")->fetchAll();

// Obtener condiciones IVA
$ivas = $pdo->query("SELECT * FROM CondicionIVA")->fetchAll(PDO::FETCH_UNIQUE);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4 principal">
    <h2>Proveedores</h2>
    <button class="btn btn-primary mb-3" onclick="abrirModal()">Agregar Proveedor</button>
    <div class="contenedor-tabla">
    <table class="table table-bordered tabla-proveedores">
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>CUIT</th>
                <th>Condición IVA</th>
                <th>Saldo Cta. Cte.</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($proveedores as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['RazonSocial']) ?></td>
                <td><?= htmlspecialchars($p['CUIT']) ?></td>
                <td><?= htmlspecialchars($ivas[$p['idIVA']]['tipoIVA'] ?? '') ?></td>
                <td><?= number_format($p['SaldoCuentaCorriente'], 2, ',', '.') ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick='abrirModal(<?= json_encode($p) ?>)'>Editar</button>
                    <a href="?eliminar=<?= $p['CodProveedor'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" id="formProveedor">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFormularioLabel">Agregar/Editar Proveedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="CodProveedor" id="CodProveedor">
            <div class="mb-3">
                <label for="RazonSocial" class="form-label">Razón Social</label>
                <input type="text" class="form-control" name="RazonSocial" id="RazonSocial" required>
            </div>
            <div class="mb-3">
                <label for="CUIT" class="form-label">CUIT</label>
                <input type="text" class="form-control" name="CUIT" id="CUIT" required>
            </div>
            <div class="mb-3">
                <label for="idIVA" class="form-label">Condición IVA</label>
                <select class="form-control" name="idIVA" id="idIVA" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($ivas as $id => $iva): ?>
                        <option value="<?= $id ?>"><?= htmlspecialchars($iva['tipoIVA']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="SaldoCuentaCorriente" class="form-label">Saldo Cuenta Corriente</label>
                <input type="number" step="0.01" class="form-control" name="SaldoCuentaCorriente" id="SaldoCuentaCorriente" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function abrirModal(proveedor = null) {
    const modal = new bootstrap.Modal(document.getElementById('modalFormulario'));
    document.getElementById('formProveedor').reset();
    document.getElementById('CodProveedor').value = '';
    if (proveedor) {
        document.getElementById('CodProveedor').value = proveedor.CodProveedor;
        document.getElementById('RazonSocial').value = proveedor.RazonSocial;
        document.getElementById('CUIT').value = proveedor.CUIT;
        document.getElementById('idIVA').value = proveedor.idIVA;
        document.getElementById('SaldoCuentaCorriente').value = proveedor.SaldoCuentaCorriente;
    }
    modal.show();
}
</script>
</body>
</html>
