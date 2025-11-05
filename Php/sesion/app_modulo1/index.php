<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'u162024603_miBaseDeDatos';
$user = 'u162024603_NicolasBranca';
$pass = 'Alcachofa189';
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
<header class="header-fijo">
    <div class="header-content">
        <h1>Proveedores</h1>
        <div class="header-botones">
            <button class="btn-header" id="btnCargarDatos">Cargar datos</button>
            <button class="btn-header" id="btnVaciarDatos">Vaciar datos</button>
            <button class="btn-header" id="btnCargarForm">CargarForm</button>
        </div>
    </div>
</header>

<!-- Colgroup para igualar anchos -->
<colgroup id="tabla-colgroup">
    <col style="width: 10%;">
    <col style="width: 25%;">
    <col style="width: 15%;">
    <col style="width: 15%;">
    <col style="width: 15%;">
    <col style="width: 20%;">
</colgroup>

<div class="tabla-fija-encabezado">
    <table class="tabla-proveedores">
        <colgroup>
            <col style="width: 10%;">
            <col style="width: 25%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 20%;">
        </colgroup>
        <thead>
            <tr>
                <th>Código</th>
                <th>Razón Social</th>
                <th>CUIT</th>
                <th>Condición IVA</th>
                <th>Saldo Cta. Cte.</th>
                <th>Certificados Calidad</th>
            </tr>
        </thead>
    </table>
</div>
<div class="contenedor-tabla-scroll">
    <table class="tabla-proveedores">
        <colgroup>
            <col style="width: 10%;">
            <col style="width: 25%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 20%;">
        </colgroup>
        <tbody id="tbodyProveedores">
        <!-- La tabla inicia vacía -->
        </tbody>
    </table>
</div>
<div class="tabla-fija-pie">
    <table class="tabla-proveedores">
        <colgroup>
            <col style="width: 10%;">
            <col style="width: 25%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 15%;">
            <col style="width: 20%;">
        </colgroup>
        <tfoot>
            <tr>
                <td>Código</td>
                <td>Razón Social</td>
                <td>CUIT</td>
                <td>Condición IVA</td>
                <td>Saldo Cta. Cte.</td>
                <td>Certificados Calidad</td>
            </tr>
        </tfoot>
    </table>
</div>
<footer class="footer-fijo">
    <span>Pie de página</span>
</footer>

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
const datosOriginales = <?php echo json_encode($proveedores); ?>;
const ivas = <?php echo json_encode($ivas); ?>;

function renderTabla(datos) {
    const tbody = document.getElementById('tbodyProveedores');
    tbody.innerHTML = '';
    datos.forEach(p => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${p.CodProveedor ? escapeHtml(p.CodProveedor) : ''}</td>
            <td>${p.RazonSocial ? escapeHtml(p.RazonSocial) : ''}</td>
            <td>${p.CUIT ? escapeHtml(p.CUIT) : ''}</td>
            <td>${ivas[p.idIVA]?.tipoIVA || ''}</td>
            <td>${Number(p.SaldoCuentaCorriente).toLocaleString('es-AR', {minimumFractionDigits:2})}</td>
            <td></td>
        `;
        tbody.appendChild(tr);
    });
}

function escapeHtml(text) {
    return text.replace(/[&<>"']/g, function(m) {
        return ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        })[m];
    });
}

function cargaTabla() {
    const tbody = document.getElementById('tbodyProveedores');
    tbody.innerHTML = '';

    // Mensaje de espera
    const trMsg = document.createElement('tr');
    const tdMsg = document.createElement('td');
    tdMsg.colSpan = 6;
    tdMsg.textContent = 'Esperando respuesta del servidor';
    trMsg.appendChild(tdMsg);
    tbody.appendChild(trMsg);

    // Datos a enviar (puedes agregar filtros si tienes)
    const objDatosOrdenFiltros = new URLSearchParams();

    fetch('./salidaJsonProveedoresConPrepare.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: objDatosOrdenFiltros
    })
    .then(response => {
        if (!response.ok) throw new Error('Respuesta de red no OK');
        return response.json();
    })
    .then(proveedores => {
        tbody.innerHTML = '';
        if (!Array.isArray(proveedores) || proveedores.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 6;
            td.textContent = 'No se encontraron proveedores';
            tr.appendChild(td);
            tbody.appendChild(tr);
            return;
        }
        // Columnas a mostrar y su mapeo a campos del objeto
        const columnas = [
            { campo: 'CodProveedor', label: 'Código' },
            { campo: 'RazonSocial', label: 'Razón Social' },
            { campo: 'CUIT', label: 'CUIT' },
            { campo: 'tipoIVA', label: 'Condición IVA' },
            { campo: 'SaldoCuentaCorriente', label: 'Saldo Cta. Cte.' },
            { campo: 'tieneCertificado', label: 'Certificados Calidad' }
        ];
        proveedores.forEach(objProveedor => {
            var objTr = document.createElement('tr');
            columnas.forEach(col => {
                var objTd = document.createElement('td');
                objTd.setAttribute('campo-dato', col.campo);
                let valor = objProveedor[col.campo];
                if (col.campo === 'SaldoCuentaCorriente' && valor !== undefined && valor !== null) {
                    valor = Number(valor).toLocaleString('es-AR', {minimumFractionDigits:2});
                } else if (col.campo === 'tieneCertificado') {
                    if (valor) {
                        valor = `<a href="descargarCertificado.php?cod=${objProveedor.CodProveedor}" target="_blank" title="Ver PDF">📄</a>`;
                    } else {
                        valor = '';
                    }
                }
                objTd.innerHTML = valor !== undefined && valor !== null ? valor : '';
                objTr.appendChild(objTd);
            });
            tbody.appendChild(objTr);
        });
    })
    .catch(error => {
        tbody.innerHTML = '';
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 6;
        td.textContent = 'Error producido: ' + error;
        tr.appendChild(td);
        tbody.appendChild(tr);
    });
}

// Puedes llamar cargaTabla() desde un botón o al cargar la página si lo deseas
document.getElementById('btnCargarDatos').onclick = function() {
    cargaTabla();
};
document.getElementById('btnVaciarDatos').onclick = function() {
    document.getElementById('tbodyProveedores').innerHTML = '';
};
document.getElementById('btnCargarForm').onclick = function() {
    abrirModal();
};

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

// Al cargar la página, la tabla aparece vacía (ya está vacío el tbody en el HTML)
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tbodyProveedores').innerHTML = '';
});
</script>
</body>
</html>
