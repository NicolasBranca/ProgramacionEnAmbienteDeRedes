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
    font-family: Arial, sans-serif;
    background-color: #F5F5DC; 
    margin: 0;
    padding: 0;
}

header {
    background-color: #F5F5DC; 
    color: black;
    text-align: center;
    padding: 20px;
    font-size: 24px;
    font-weight: bold;
    border-bottom: 2px solid #808080;
}

footer {
    background-color: #F5F5DC; 
    color: black;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
    border-top: 2px solid #808080;
}

.container {
    padding: 20px;
}

.table-input {
    width: 100%;
    background-color: #FF6347;
    color: white;
    border-collapse: collapse;
}

.table-input th {
    padding: 10px;
    font-weight: bold;
    border-bottom: 2px solid #fff;
}

.table-input input,
.table-input select {
    width: 90%;
    padding: 5px;
    margin: 5px 0;
    border: 1px solid #C0C0C0;
    border-radius: 4px;
}

.table-data {
    width: 100%;
    border-collapse: collapse;
}

.table-data th {
    background-color: #FF6347;
    color: white;
    padding: 10px;
    border: 1px solid #C0C0C0;
    text-align: center;
}

.table-data td {
    background-color: #808080;
    color: white;
    padding: 8px;
    border: 1px solid #C0C0C0;
    text-align: center;
}

.action-button {
    background-color: #D3D3D3;
    color: black;
    border: 1px solid #808080;
    padding: 5px 10px;
    margin: 5px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
}

.action-button:hover {
    background-color: #C0C0C0;
}

.modify-button {
    background-color: #4CAF50;
    color: white;
}

.delete-button {
    background-color: #F44336;
    color: white;
}

.modal {
    display: none; 
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #808080;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    color: white;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: white;
    text-decoration: none;
    cursor: pointer;
}

.modal-content input[type="text"],
.modal-content select {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.modal-content input[type="text"]:focus,
.modal-content select:focus {
    border-color: #FF6347;
    outline: none;
}

.modal-content .submit-button {
    background-color: #FF6347;
    color: white;
    border: none;
    padding: 10px 20px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 16px;
}

.modal-content .submit-button:hover {
    background-color: #D94E3B;
}

@media (max-width: 768px) {
    .modal-content {
        width: 80%;
    }

    .table-input th,
    .table-input input,
    .table-input select,
    .table-data th,
    .table-data td {
        font-size: 14px;
    }

    .action-button {
        font-size: 12px;
    }
}


    </style>
</head>

<body>
<header>
        <h1>Maestro de Proveedores</h1>
    </header>
    <main>
    <!-- Filtros adaptados a proveedores -->
    <div>
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

        <button id="cargarDatos">Cargar datos</button>
        <button id="altaDato" class="btn">Alta proveedor</button>
        <button id="limpiarFiltros" class="btn btn-secondary">Limpiar Filtros</button>
        <button id="borrarTabla" class="btn btn-danger">Borrar Datos de la Tabla</button>
        <button id="btCierraSesion">Cierra Sesión</button>
    </div>

    <table id="tablaProveedores" border="1" style="width: 100%;">
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
        </tbody>
    </table>

    <!-- Modal Alta Proveedor -->
    <div id="modalAlta" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Alta de Proveedor</h2>
            <form id="altaProveedorForm">
                <label for="RazonSocial">Razón Social:</label>
                <input type="text" id="RazonSocial" name="RazonSocial" required>

                <label for="CUIT">CUIT:</label>
                <input type="text" id="CUIT" name="CUIT" required>

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
                <input type="text" id="modificarCUIT" name="CUIT" required>

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
            <iframe id="iframeArchivo" src="" frameborder="0" style="width: 100%; height: 500px;"></iframe>
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

    $('#cargarDatos').on('click', function () {
        cargarDatos();
    });

    $('.sort').on('click', function () {
        sort_column = $(this).data('column');
        sort_direction = sort_direction === 'ASC' ? 'DESC' : 'ASC';
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
                cargarDatos();
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
                cargarDatos();
            },
            error: function () {
                alert('Error al dar de alta el proveedor');
            }
        });
    });

    // Cargar datos al iniciar
    cargarDatos();
});
</script>
</body>
</html>