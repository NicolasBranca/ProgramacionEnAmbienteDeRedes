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
/* Reset y estructura básica */
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: #f5f5dc;
    height: 100vh;
    box-sizing: border-box;
    /* Quitar overflow: hidden para permitir scroll si es necesario */
}
header {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 60px;
    background: #f5f5dc;
    border-bottom: 2px solid #808080;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}
header h1 {
    margin: 0;
    font-size: 2em;
    font-weight: bold;
}
main {
    position: relative;
    padding-top: 60px;
    padding-bottom: 40px;
    height: calc(100vh - 60px - 40px); /* Ajuste: ocupa todo el espacio entre header y footer */
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    min-height: 0; /* Permite que los hijos usen todo el espacio */
}
.filtros-container {
    background: #f5f5dc;
    border-bottom: 1px solid #ccc;
    padding: 8px 6px 0 6px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
}
.filtros-campos label {
    font-weight: bold;
    margin-right: 2px;
    font-size: 0.97em;
}
.filtros-campos input, .filtros-campos select {
    margin-right: 6px;
    padding: 2px 4px;
    border-radius: 2px;
    border: 1px solid #ccc;
    font-size: 0.97em;
    width: 120px;
    max-width: 140px;
    min-width: 60px;
}
.filtros-botones {
    display: flex;
    gap: 6px;
    margin-top: 0;
    flex-wrap: wrap;
}
.filtros-botones button {
    padding: 4px 8px;
    border-radius: 2px;
    border: 1px solid #808080;
    background: #e0e0e0;
    cursor: pointer;
    font-size: 0.97em;
}
.filtros-botones button:hover {
    background: #d0d0d0;
}
.table-wrapper {
    flex: 1 1 0;
    min-height: 0; /* Permite que el tbody crezca hasta el footer */
    overflow: hidden;
    background: #f5f5dc;
    display: flex;
    flex-direction: column;
    width: 100vw;
}
#tablaProveedores {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    background: #f5f5dc;
    height: 100%;
    min-height: 0;
    /* Quitar display: flex y flex-direction */
}
#tablaProveedores thead, #tablaProveedores tfoot {
    display: table;
    width: 100%;
    table-layout: fixed;
}
#tablaProveedores tbody {
    display: block;
    height: 100%;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    width: 100%;
}
#tablaProveedores tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}
#tablaProveedores th, #tablaProveedores td {
    border: 1px solid #c0c0c0;
    padding: 4px;
    text-align: center;
    font-size: 0.97em;
    word-break: break-word;
}
footer {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    height: 40px;
    background: #f5f5dc;
    border-top: 2px solid #808080;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1em;
    z-index: 10;
}
/* Modal simple */
.modal {
    display: none;
    position: fixed;
    z-index: 100;
    left: 0; top: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}
.modal-content {
    background: #808080;
    color: #fff;
    margin: 5% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    position: relative;
    /* Para la ventana de ver certificado, que ocupe más espacio */
}
#modalArchivo .modal-content {
    width: 95vw;
    max-width: 900px;
    height: 90vh;
    max-height: 90vh;
    padding: 0;
    display: flex;
    flex-direction: column;
}
#modalArchivo .close {
    position: absolute;
    top: 10px; right: 20px;
    z-index: 2;
}
#iframeArchivo {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
    /* Para que PDF o imagen ocupe todo el espacio */
}
.filtros-orden {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: 10px;
}
.filtros-orden input[type="text"] {
    width: 130px;
    padding: 2px 4px;
    border-radius: 2px;
    border: 1px solid #ccc;
    font-size: 0.97em;
    background: #eee;
}
.filtros-orden select {
    padding: 2px 4px;
    border-radius: 2px;
    border: 1px solid #ccc;
    font-size: 0.97em;
    background: #eee;
}
.filtros-orden input[readonly] {
    background: #e9e9e9;
    color: #333;
    cursor: default;
}
@media (max-width: 900px) {
    .filtros-container, .filtros-botones, .filtros-campos {
        flex-direction: column;
        align-items: stretch;
        gap: 4px;
    }
    .filtros-orden {
        margin-left: 0;
        margin-top: 6px;
    }
    #tablaProveedores th, #tablaProveedores td {
        font-size: 0.93em;
        padding: 3px;
    }
    /* Oculta columnas CUIT y Saldo Cuenta Corriente en pantallas chicas */
    #tablaProveedores th:nth-child(3),
    #tablaProveedores td:nth-child(3),
    #tablaProveedores th:nth-child(5),
    #tablaProveedores td:nth-child(5) {
        display: none;
    }
    .modal-content { width: 98%; }
}
    </style>
</head>
<body>
<header>
    <h1>Maestro de Proveedores</h1>
</header>
<main>
    <!-- Filtros adaptados a proveedores -->
    <div class="filtros-container">
        <div class="filtros-campos">
            <label for="CodProveedorFiltro">Código Proveedor:</label>
            <input type="text" id="CodProveedorFiltro" name="CodProveedorFiltro">

            <label for="RazonSocialFiltro">Razón Social:</label>
            <input type="text" id="RazonSocialFiltro" name="RazonSocialFiltro">

            <label for="CUITFiltro">CUIT:</label>
            <input type="text" id="CUITFiltro" name="CUITFiltro">

            <label for="idIVAFiltro">Condición IVA:</label>
            <select id="idIVAFiltro" name="idIVAFiltro">
                <option value="">Todos</option>
            </select>

            <label for="SaldoCuentaCorrienteFiltro">Saldo Cuenta Corriente:</label>
            <input type="number" id="SaldoCuentaCorrienteFiltro" name="SaldoCuentaCorrienteFiltro">

            <div class="filtros-orden">
                <label for="ordenColumna">Ordenar por:</label>
                <input type="text" id="ordenColumna" name="ordenColumna" readonly placeholder="Columna" tabindex="-1">
                <select id="ordenTipo" name="ordenTipo" disabled>
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                </select>
            </div>
        </div>
        <div class="filtros-botones">
            <button id="cargarDatos">Cargar datos</button>
            <button id="altaDato" class="btn">Alta proveedor</button>
            <button id="limpiarFiltros" class="btn btn-secondary">Limpiar Filtros</button>
            <button id="borrarTabla" class="btn btn-danger">Borrar Datos de la Tabla</button>
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
            <tbody>
                <!-- La tabla inicia vacía -->
            </tbody>
        </table>
    </div>

    <!-- Modal Alta Proveedor -->
    <div id="modalAlta" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Alta de Proveedor</h2>
            <form id="altaProveedorForm">
                <label for="RazonSocial">Razón Social:</label>
                <input type="text" id="RazonSocial" name="RazonSocial" required>

                <label for="CUIT">CUIT:</label>
                <input type="text" id="CUIT" name="CUIT" required placeholder="XX-XXXXXXXX-X">

                <label for="idIVA">Condición IVA:</label>
                <select id="idIVA" name="idIVA" required>
                    <option value="">Seleccione condición IVA</option>
                </select>

                <label for="SaldoCuentaCorriente">Saldo Cuenta Corriente:</label>
                <input type="number" id="SaldoCuentaCorriente" name="SaldoCuentaCorriente" step="0.01" required>

                <label for="CertificadosCalidad">Subir Certificado Calidad (PDF, JPG, PNG):</label>
                <input type="file" id="CertificadosCalidad" name="CertificadosCalidad" accept=".pdf, .jpg, .jpeg, .png">

                <br><br>
                <button type="submit">Dar de alta Proveedor</button>
            </form>
        </div>
    </div>

    <!-- Modal Modificar Proveedor -->
    <div id="modalModificar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modificar Proveedor</h2>
            <form id="modificarProveedorForm">
                <input type="hidden" id="modificarCodProveedor" name="CodProveedor">

                <label for="modificarRazonSocial">Razón Social:</label>
                <input type="text" id="modificarRazonSocial" name="RazonSocial" required>

                <label for="modificarCUIT">CUIT:</label>
                <input type="text" id="modificarCUIT" name="CUIT" required placeholder="XX-XXXXXXXX-X">

                <label for="modificarIdIVA">Condición IVA:</label>
                <select id="modificarIdIVA" name="idIVA" required>
                </select>

                <label for="modificarSaldoCuentaCorriente">Saldo Cuenta Corriente:</label>
                <input type="number" id="modificarSaldoCuentaCorriente" name="SaldoCuentaCorriente" step="0.01" required>

                <label for="modificarCertificadosCalidad">Actualizar Certificado Calidad (opcional):</label>
                <input type="file" id="modificarCertificadosCalidad" name="CertificadosCalidad" accept=".pdf, .jpg, .jpeg, .png">

                <br><br>
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <div id="modalArchivo" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <iframe id="iframeArchivo" src="" frameborder="0"></iframe>
        </div>
    </div>
</main>
<footer>
    <p id="contadorRegistros">Cantidad de registros: 0</p>
</footer>
<script>
$(document).ready(function () {
    let sort_column = '';
    let sort_direction = 'ASC';

    // Cargar condiciones IVA en los selects
    function cargarIVASelects() {
        $.ajax({
            url: 'load_iva.php',
            method: 'GET',
            success: function (data) {
                const ivas = JSON.parse(data);
                $('#idIVAFiltro, #idIVA, #modificarIdIVA').empty().append('<option value="">Todos</option>');
                ivas.forEach(function (iva) {
                    $('#idIVAFiltro').append(`<option value="${iva.idIVA}">${iva.tipoIVA} (${iva.porcentaje}%)</option>`);
                    $('#idIVA').append(`<option value="${iva.idIVA}">${iva.tipoIVA} (${iva.porcentaje}%)</option>`);
                    $('#modificarIdIVA').append(`<option value="${iva.idIVA}">${iva.tipoIVA} (${iva.porcentaje}%)</option>`);
                });
            }
        });
    }
    cargarIVASelects();

    // Nuevo: manejo de selección de columna y tipo de orden
    $('.sort').on('click', function () {
        const col = $(this).data('column');
        let colLabel = $(this).text().trim();
        $('#ordenColumna').val(colLabel).data('column', col);
        $('#ordenTipo').prop('disabled', false);
    });

    $('#ordenTipo').on('change', function () {
        sort_direction = $(this).val();
    });

    $('#cargarDatos').on('click', function () {
        // Si hay columna seleccionada, usarla
        const col = $('#ordenColumna').data('column');
        if (col) {
            sort_column = col;
            sort_direction = $('#ordenTipo').val();
        } else {
            sort_column = '';
        }
        cargarDatos();
    });

    $("#btCierraSesion").click(function() {
        location.href = "./destruirsesion.php";
    });

    function actualizarContador() {
        const cantidadRegistros = $('#tablaProveedores tbody tr').length;
        $('#contadorRegistros').text('Cantidad de registros: ' + cantidadRegistros);
    }

    $('#limpiarFiltros').on('click', function () {
        $('#CodProveedorFiltro').val('');
        $('#RazonSocialFiltro').val('');
        $('#CUITFiltro').val('');
        $('#idIVAFiltro').val('');
        $('#SaldoCuentaCorrienteFiltro').val('');
        $('#ordenColumna').val('').removeData('column');
        $('#ordenTipo').prop('disabled', true).val('ASC');
        alert('Filtros limpiados.');
    });

    $('#borrarTabla').on('click', function () {
        $('#tablaProveedores tbody').html('');
    });

    function cargarDatos() {
        const CodProveedorFiltro = $('#CodProveedorFiltro').val();
        const RazonSocialFiltro = $('#RazonSocialFiltro').val();
        const CUITFiltro = $('#CUITFiltro').val();
        const idIVAFiltro = $('#idIVAFiltro').val();
        const SaldoCuentaCorrienteFiltro = $('#SaldoCuentaCorrienteFiltro').val();

        $('#loading').show();
        $('#tablaProveedores tbody').html('');

        $.ajax({
            url: 'load_data.php',
            method: 'POST',
            data: {
                sort_column: sort_column,
                sort_direction: sort_direction,
                CodProveedor: CodProveedorFiltro,
                RazonSocial: RazonSocialFiltro,
                CUIT: CUITFiltro,
                idIVA: idIVAFiltro,
                SaldoCuentaCorriente: SaldoCuentaCorrienteFiltro
            },
            success: function (data) {
                $('#loading').hide();
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
                            <button class="btn btn-primary ver-archivo" data-id="${prov.CodProveedor}">Ver Certificado</button>
                            <button class="btn btn-warning modificar-proveedor" data-id="${prov.CodProveedor}">Modificar</button>
                            <button class="btn btn-danger eliminar-proveedor" data-id="${prov.CodProveedor}">Eliminar</button>
                        </td>
                    </tr>`;
                });

                $('#tablaProveedores tbody').html(html);
                bindActionsToButtons();
                actualizarContador();
            },
            error: function () {
                $('#loading').hide();
                alert('Error al cargar los datos');
            }
        });
    }

    function bindActionsToButtons() {
        $('.ver-archivo').on('click', function () {
            var CodProveedor = $(this).data('id');
            $('#iframeArchivo').attr('src', 'ver_archivo.php?CodProveedor=' + CodProveedor);
            $('#modalArchivo').show();
        });

        $(document).on('click', '.close', function () {
            $('#modalArchivo').fadeOut();
            $('#iframeArchivo').attr('src', '');
        });

        $(window).on('click', function (event) {
            if ($(event.target).is('#modalArchivo')) {
                $('#modalArchivo').fadeOut();
                $('#iframeArchivo').attr('src', '');
            }
        });

        $('.modificar-proveedor').on('click', function () {
            const fila = $(this).closest('tr');
            const CodProveedor = fila.find('td').eq(0).text();
            const RazonSocial = fila.find('td').eq(1).text();
            const CUIT = fila.find('td').eq(2).text();
            const idIVA = fila.find('td').eq(3).text();
            const SaldoCuentaCorriente = fila.find('td').eq(4).text();

            $('#modificarCodProveedor').val(CodProveedor);
            $('#modificarRazonSocial').val(RazonSocial);
            $('#modificarCUIT').val(CUIT);
            $('#modificarIdIVA').val(idIVA);
            $('#modificarSaldoCuentaCorriente').val(SaldoCuentaCorriente);

            $('#modalModificar').show();
        });

        $('.eliminar-proveedor').on('click', function () {
            const CodProveedor = $(this).data('id');
            if (confirm('¿Estás seguro de que deseas eliminar este proveedor?')) {
                $.ajax({
                    url: 'eliminar_proveedor.php',
                    method: 'POST',
                    data: { CodProveedor: CodProveedor },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('tr[data-id="' + CodProveedor + '"]').remove();
                            alert(res.message);
                        } else {
                            alert('Error: ' + res.message);
                        }
                    },
                    error: function () {
                        alert('Error al conectar con el servidor.');
                    }
                });
            }
        });
    }

    $('.close').on('click', function () {
        $('#modalModificar').fadeOut();
    });

    $('#modificarProveedorForm').on('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'modificar_proveedor.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert('Proveedor modificado exitosamente');
                $('#modalModificar').hide();
            },
            error: function () {
                alert('Error al modificar el proveedor.');
            }
        });
    });

    $('#altaDato').on('click', function () {
        $('#modalAlta').show();
    });

    $('.close').on('click', function () {
        $('#modalAlta').hide();
    });

    $('#altaProveedorForm').on('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'alta_proveedor.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert('Proveedor dado de alta exitosamente');
                $('#altaProveedorForm')[0].reset();
                $('#modalAlta').hide();
            },
            error: function () {
                alert('Error al dar de alta el proveedor');
            }
        });
    });
});
</script>
</body>
</html>