<?php
include('../manejoSesion.inc');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maestro de proveedores</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f5dc;
    height: 100vh;
    box-sizing: border-box;
}
header {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 60px;
    background: #f5f5dc;
    border-bottom: 1px solid #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}
header h1 { margin: 0; font-size: 1.7em; }
main {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 60px - 40px);
    padding-top: 60px;
    min-height: 0;
}
.filtros-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    background: #f5f5dc;
    border-bottom: 1px solid #ccc;
    padding: 8px;
}
.filtros-campos label { font-weight: bold; margin-right: 2px; font-size: 1em; }
.filtros-campos input, .filtros-campos select {
    margin-right: 8px;
    padding: 2px 4px;
    border-radius: 2px;
    border: 1px solid #ccc;
    font-size: 1em;
}
.filtros-botones { display: flex; gap: 8px; }
.filtros-botones button {
    padding: 4px 10px;
    border-radius: 2px;
    border: 1px solid #888;
    background: #e0e0e0;
    cursor: pointer;
}
.filtros-botones button:hover { background: #d0d0d0; }
.table-wrapper {
    flex: 1 1 0;
    min-height: 0;
    overflow: hidden;
    background: #f5f5dc;
    display: flex;
    flex-direction: column;
    height: 100%;
}
#tablaProveedores {
    width: 100%;
    border-collapse: collapse;
    background: #f5f5dc;
    height: 100%;
}
#tablaProveedores thead {
    background: #ff6347;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 2;
}
#tablaProveedores th, #tablaProveedores td {
    border: 1px solid #bbb;
    padding: 4px;
    text-align: center;
    font-size: 1em;
    word-break: break-word;
}
#tablaProveedores tbody {
    display: block;
    height: 100%;
    min-height: 0;
    overflow-y: auto;
    width: 100%;
    margin: 0;
    padding: 0;
}
#tablaProveedores thead, #tablaProveedores tfoot {
    display: table;
    width: 100%;
    table-layout: fixed;
}
#tablaProveedores tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}
footer {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    height: 40px;
    background: #f5f5dc;
    border-top: 1px solid #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1em;
    z-index: 10;
}
@media (max-width: 900px) {
    .filtros-container, .filtros-botones, .filtros-campos {
        flex-direction: column;
        align-items: stretch;
        gap: 4px;
    }
    #tablaProveedores th, #tablaProveedores td {
        font-size: 0.93em;
        padding: 3px;
    }
}
    </style>
</head>
<body>
<header><h1>Maestro de Proveedores</h1></header>
<main>
    <div class="filtros-container">
        <div class="filtros-campos">
            <label for="CodProveedorFiltro">Código Proveedor:</label>
            <input type="text" id="CodProveedorFiltro">
            <label for="RazonSocialFiltro">Razón Social:</label>
            <input type="text" id="RazonSocialFiltro">
            <label for="CUITFiltro">CUIT:</label>
            <input type="text" id="CUITFiltro">
            <label for="idIVAFiltro">Condición IVA:</label>
            <select id="idIVAFiltro"><option value="">Todos</option></select>
            <label for="SaldoCuentaCorrienteFiltro">Saldo Cuenta Corriente:</label>
            <input type="number" id="SaldoCuentaCorrienteFiltro">
            <div class="filtros-orden">
                <label for="ordenColumna">Ordenar por:</label>
                <input type="text" id="ordenColumna" readonly placeholder="Columna">
                <select id="ordenTipo" disabled>
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                </select>
            </div>
        </div>
        <div class="filtros-botones">
            <button id="cargarDatos">Cargar datos</button>
            <button id="altaDato">Alta proveedor</button>
            <button id="limpiarFiltros">Limpiar Filtros</button>
            <button id="borrarTabla">Borrar Datos de la Tabla</button>
            <button id="btCierraSesion">Cierra Sesión</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="tablaProveedores" border="1">
            <thead>
                <tr>
                    <th><button class="sort" data-column="CodProveedor">Código</button></th>
                    <th><button class="sort" data-column="RazonSocial">Razón Social</button></th>
                    <th><button class="sort" data-column="CUIT">CUIT</button></th>
                    <th><button class="sort" data-column="idIVA">Condición IVA</button></th>
                    <th><button class="sort" data-column="SaldoCuentaCorriente">Saldo Cuenta Corriente</button></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="modalAlta" class="modal" style="display:none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Alta de Proveedor</h2>
            <form id="altaProveedorForm">
                <label>Razón Social:</label>
                <input type="text" id="RazonSocial" required>
                <label>CUIT:</label>
                <input type="text" id="CUIT" required placeholder="XX-XXXXXXXX-X">
                <label>Condición IVA:</label>
                <select id="idIVA" required></select>
                <label>Saldo Cuenta Corriente:</label>
                <input type="number" id="SaldoCuentaCorriente" step="0.01" required>
                <label>Certificado Calidad:</label>
                <input type="file" id="CertificadosCalidad" accept=".pdf, .jpg, .jpeg, .png">
                <br><br>
                <button type="submit" id="btnAltaProveedor">Dar de alta Proveedor</button>
            </form>
        </div>
    </div>

    <div id="modalModificar" class="modal" style="display:none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modificar Proveedor</h2>
            <form id="modificarProveedorForm">
                <input type="hidden" id="modificarCodProveedor">
                <label>Razón Social:</label>
                <input type="text" id="modificarRazonSocial" required>
                <label>CUIT:</label>
                <input type="text" id="modificarCUIT" required placeholder="XX-XXXXXXXX-X">
                <label>Condición IVA:</label>
                <select id="modificarIdIVA" required></select>
                <label>Saldo Cuenta Corriente:</label>
                <input type="number" id="modificarSaldoCuentaCorriente" step="0.01" required>
                <label>Certificado Calidad (opcional):</label>
                <input type="file" id="modificarCertificadosCalidad" accept=".pdf, .jpg, .jpeg, .png">
                <br><br>
                <button type="submit" id="btnModificarProveedor">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <div id="modalArchivo" class="modal" style="display:none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <iframe id="iframeArchivo" src="" frameborder="0"></iframe>
        </div>
    </div>
</main>
<footer><p id="contadorRegistros">Cantidad de registros: 0</p></footer>

<script>
$(document).ready(function () {
    let sort_column = '';
    let sort_direction = 'ASC';

    function cargarIVASelects() {
        $.ajax({
            url: 'load_iva.php',
            method: 'GET',
            success: function (data) {
                const ivas = JSON.parse(data);
                $('#idIVAFiltro, #idIVA, #modificarIdIVA').empty().append('<option value="">Todos</option>');
                ivas.forEach(function (iva) {
                    const option = `<option value="${iva.idIVA}">${iva.tipoIVA} (${iva.porcentaje}%)</option>`;
                    $('#idIVAFiltro, #idIVA, #modificarIdIVA').append(option);
                });
            }
        });
    }
    cargarIVASelects();

    // Ordenamiento
    $('.sort').on('click', function () {
        const col = $(this).data('column');
        const colLabel = $(this).text().trim();
        $('#ordenColumna').val(colLabel).data('column', col);
        $('#ordenTipo').prop('disabled', false);
    });
    $('#ordenTipo').on('change', function () {
        sort_direction = $(this).val();
    });

    // Botones principales
    $('#cargarDatos').on('click', function () {
        const col = $('#ordenColumna').data('column');
        sort_column = col || '';
        sort_direction = $('#ordenTipo').val();
        cargarDatos();
    });
    $('#btCierraSesion').on('click', function () {
        location.href = './destruirsesion.php';
    });
    $('#limpiarFiltros').on('click', function () {
        $('#CodProveedorFiltro, #RazonSocialFiltro, #CUITFiltro, #SaldoCuentaCorrienteFiltro').val('');
        $('#idIVAFiltro').val('');
        $('#ordenColumna').val('').removeData('column');
        $('#ordenTipo').prop('disabled', true).val('ASC');
        alert('Filtros limpiados.');
    });
    $('#borrarTabla').on('click', function () {
        $('#tablaProveedores tbody').empty();
        actualizarContador();
    });

    function actualizarContador() {
        const cantidad = $('#tablaProveedores tbody tr').length;
        $('#contadorRegistros').text('Cantidad de registros: ' + cantidad);
    }

    function cargarDatos() {
        $.ajax({
            url: 'load_data.php',
            method: 'POST',
            data: {
                sort_column,
                sort_direction,
                CodProveedor: $('#CodProveedorFiltro').val(),
                RazonSocial: $('#RazonSocialFiltro').val(),
                CUIT: $('#CUITFiltro').val(),
                idIVA: $('#idIVAFiltro').val(),
                SaldoCuentaCorriente: $('#SaldoCuentaCorrienteFiltro').val()
            },
            success: function (data) {
                const proveedores = JSON.parse(data);
                let html = '';
                proveedores.forEach(function (prov) {
                    html += `<tr data-id="${prov.CodProveedor}">
                        <td>${prov.CodProveedor}</td>
                        <td>${prov.RazonSocial}</td>
                        <td>${prov.CUIT}</td>
                        <td>${prov.idIVA}</td>
                        <td>${prov.SaldoCuentaCorriente}</td>
                        <td>
                            <button class="ver-archivo" data-id="${prov.CodProveedor}">Ver Certificado</button>
                            <button class="modificar-proveedor" data-id="${prov.CodProveedor}">Modificar</button>
                            <button class="eliminar-proveedor" data-id="${prov.CodProveedor}">Eliminar</button>
                        </td>
                    </tr>`;
                });
                $('#tablaProveedores tbody').html(html);
                actualizarContador();
            },
            error: function () {
                alert('Error al cargar los datos');
            }
        });
    }

    // VALIDACIÓN Y ENVÍO DE ALTA DE PROVEEDOR
    $('#altaDato').on('click', function () {
        $('#modalAlta').show();
    });

    const regexCUIT = /^\d{2}-\d{8}-\d{1}$/;
    const formAlta = $('#altaProveedorForm');

    formAlta.on('input change', 'input, select', function () {
        const razon = $('#RazonSocial').val().trim();
        const cuit = $('#CUIT').val().trim();
        const idIVA = $('#idIVA').val().trim();
        const saldo = $('#SaldoCuentaCorriente').val().trim();
        const cuitValido = regexCUIT.test(cuit);
        const saldoValido = saldo !== '' && !isNaN(saldo);
        $('#btnAltaProveedor').prop('disabled', !(razon && cuitValido && idIVA && saldoValido));
        $('#CUIT').css('borderColor', cuit && !cuitValido ? 'red' : '');
    });

    formAlta.on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: 'alta_proveedor.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                let res;
                try { res = JSON.parse(response); } catch { res = {status: 'error', message: 'Error inesperado'}; }
                if (res.status === 'success') {
                    alert('Proveedor dado de alta exitosamente');
                    $('#modalAlta').hide();
                    formAlta[0].reset();
                    cargarDatos();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function () {
                alert('Error al dar de alta el proveedor');
            }
        });
    });

    // VALIDACIÓN Y ENVÍO DE MODIFICACIÓN DE PROVEEDOR
    const formModificar = $('#modificarProveedorForm');

    formModificar.on('input change', 'input, select', function () {
        const razon = $('#modificarRazonSocial').val().trim();
        const cuit = $('#modificarCUIT').val().trim();
        const idIVA = $('#modificarIdIVA').val().trim();
        const saldo = $('#modificarSaldoCuentaCorriente').val().trim();
        const cuitValido = regexCUIT.test(cuit);
        const saldoValido = saldo !== '' && !isNaN(saldo);
        $('#btnModificarProveedor').prop('disabled', !(razon && cuitValido && idIVA && saldoValido));
        $('#modificarCUIT').css('borderColor', cuit && !cuitValido ? 'red' : '');
    });

    formModificar.on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: 'modificar_proveedor.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                let res;
                try { res = JSON.parse(response); } catch { res = {status: 'error', message: 'Error inesperado'}; }
                if (res.status === 'success') {
                    alert('Proveedor modificado exitosamente');
                    $('#modalModificar').hide();
                    cargarDatos();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function () {
                alert('Error al modificar el proveedor');
            }
        });
    });

    // Cerrar modales
    $('.close').on('click', function () {
        $(this).closest('.modal').hide();
    });
});
</script>
</body>
</html>