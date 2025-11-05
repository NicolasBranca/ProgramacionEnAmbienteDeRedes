<?php
include('../manejoSesion.inc');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Partidos de Fútbol</title>
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
        <h1>Gestión de Partidos de Fútbol</h1>
    </header>
    <main>
    <div>
        <label for="identificadorFiltro">Identificador:</label>
        <input type="text" id="identificadorFiltro" name="identificadorFiltro">

        <label for="descripcionFiltro">Descripción:</label>
        <input type="text" id="descripcionFiltro" name="descripcionFiltro">

        <label for="estadioFiltro">Estadio:</label>
        <select id="estadioFiltro" name="estadioFiltro">
            <option value="">Todos</option>
        </select>

        <label for="golesTotalesFiltro">Goles Totales:</label>
        <input type="number" id="golesTotalesFiltro" name="golesTotalesFiltro">

        <label for="fechaFiltro">Fecha del Partido:</label>
        <input type="date" id="fechaFiltro" name="fechaFiltro">

        <button id="cargarDatos">Cargar datos</button>
        <button id="altaDato" class="btn">Alta dato</button>
        <button id="limpiarFiltros" class="btn btn-secondary">Limpiar Filtros</button>
        <button id="borrarTabla" class="btn btn-danger">Borrar Datos de la Tabla</button>
        <button id="btCierraSesion">Cierra Sesión</button>

    </div>

    <table id="tablaFutbol" border="1" style="width: 100%;">
        <thead>
            <tr>
                <th><button class="sort" data-column="identificadorPartido">Identificador</button></th>
                <th><button class="sort" data-column="descripcion">Descripción</button></th>
                <th><button class="sort" data-column="estadio_id">Estadio</button></th>
                <th><button class="sort" data-column="golesTotales">Goles Totales</button></th>
                <th><button class="sort" data-column="fechaPartido">Fecha</button></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div id="modalAlta" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Alta de Partido y Subida de Resumen</h2>
            <form id="altaPartidoForm">
                <label for="identificadorPartido">Identificador del partido:</label>
                <input type="text" id="identificadorPartido" name="identificadorPartido" required>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required>

                <label for="estadio_id">Estadio:</label>
                <select id="estadio_id" name="estadio_id" required>
                    <option value="">Seleccione un estadio</option>
                </select>

                <label for="golesTotales">Goles Totales:</label>
                <input type="number" id="golesTotales" name="golesTotales" required>

                <label for="fechaPartido">Fecha del partido:</label>
                <input type="date" id="fechaPartido" name="fechaPartido" required>

                <label for="resumen">Subir resumen (PDF, JPG, PNG):</label>
                <input type="file" id="resumen" name="resumen" accept=".pdf, .jpg, .jpeg, .png">

                <br><br>
                <button type="submit">Dar de alta Partido</button>
            </form>
        </div>
    </div>

    <div id="modalModificar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modificar Partido</h2>
            <form id="modificarPartidoForm">
                <input type="hidden" id="modificarIdentificadorPartido" name="identificadorPartido">

                <label for="modificarDescripcion">Descripción:</label>
                <input type="text" id="modificarDescripcion" name="descripcion" required>

                <label for="modificarEstadio">Estadio:</label>
                <select id="modificarEstadio" name="estadio_id" required>
                </select>

                <label for="modificarGolesTotales">Goles Totales:</label>
                <input type="number" id="modificarGolesTotales" name="golesTotales" required>

                <label for="modificarFechaPartido">Fecha del Partido:</label>
                <input type="date" id="modificarFechaPartido" name="fechaPartido" required>

                <label for="modificarResumen">Actualizar Resumen (opcional):</label>
                <input type="file" id="modificarResumen" name="resumen" accept=".pdf, .jpg, .jpeg, .png">

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

    <div class="modal fade" id="modalArchivo" tabindex="-1" aria-labelledby="modalArchivoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalArchivoLabel">Ver Archivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="iframeArchivo" src="" style="width: 100%; height: 500px; border: none;"></iframe>
                </div>
            </div>
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
        const cantidadRegistros = $('#tablaFutbol tbody tr').length;
        $('#contadorRegistros').text('Cantidad de registros: ' + cantidadRegistros);
    }

        $('#limpiarFiltros').on('click', function () {
        $('#identificadorFiltro').val('');
        $('#descripcionFiltro').val('');
        $('#estadioFiltro').val('');
        $('#golesTotalesFiltro').val('');
        $('#fechaFiltro').val('');

        alert('Filtros limpiados.');
    });

    $('#borrarTabla').on('click', function () {
        $('#tablaFutbol tbody').html(''); 
    });

    function cargarDatos() {
        const identificadorFiltro = $('#identificadorFiltro').val();
        const descripcionFiltro = $('#descripcionFiltro').val();
        const estadioFiltro = $('#estadioFiltro').val();
        const golesTotalesFiltro = $('#golesTotalesFiltro').val();
        const fechaFiltro = $('#fechaFiltro').val();

        $('#loading').show();
        $('#tablaFutbol tbody').html(''); 

        $.ajax({
            url: 'load_data.php',
            method: 'POST',
            data: {
                sort_column: sort_column,
                sort_direction: sort_direction,
                identificadorPartido: identificadorFiltro,
                descripcion: descripcionFiltro,
                estadio_id: estadioFiltro,
                golesTotales: golesTotalesFiltro,
                fechaPartido: fechaFiltro
            },
            success: function (data) {
                $('#loading').hide();
                const partidos = JSON.parse(data);
                let html = '';
                partidos.forEach(function (partido) {
                    html += `<tr data-id="${partido.identificadorPartido}">
                        <td>${partido.identificadorPartido}</td>
                        <td>${partido.descripcion}</td>
                        <td>${partido.estadio_id}</td>
                        <td>${partido.golesTotales}</td>
                        <td>${partido.fechaPartido}</td>
                        <td>
                            <button class="btn btn-primary ver-archivo" data-id="${partido.identificadorPartido}">Ver Archivo</button>
                            <button class="btn btn-warning modificar-partido" data-id="${partido.identificadorPartido}">Modificar</button>
                            <button class="btn btn-danger eliminar-partido" data-id="${partido.identificadorPartido}">Eliminar</button>
                        </td>
                    </tr>`;
                });

                $('#tablaFutbol tbody').html(html);
                bindActionsToButtons(); 
                actualizarContador();
            },
            error: function () {
                $('#loading').hide();
                alert('Error al cargar los datos');
            }
        });
    }
});


function bindActionsToButtons() {
    /*$('.ver-archivo').on('click', function () {
        const identificadorPartido = $(this).data('id');
        window.location.href = 'ver_archivo.php?identificadorPartido=' + identificadorPartido;
    });*/
    $('.ver-archivo').on('click', function () {
            var identificadorPartido = $(this).data('id');
            $('#iframeArchivo').attr('src', 'ver_archivo.php?identificadorPartido=' + identificadorPartido);            $('#modalArchivo').show(); // Mostrar el modal
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

    $('.modificar-partido').on('click', function () {
        const fila = $(this).closest('tr');

        const identificadorPartido = fila.find('td').eq(0).text();
        const descripcion = fila.find('td').eq(1).text();
        const estadio_id = fila.find('td').eq(2).text();
        const golesTotales = fila.find('td').eq(3).text();
        const fechaPartido = fila.find('td').eq(4).text();

        $('#modificarIdentificadorPartido').val(identificadorPartido);
        $('#modificarDescripcion').val(descripcion);
        $('#modificarGolesTotales').val(golesTotales);
        $('#modificarFechaPartido').val(fechaPartido);

        $('#modificarEstadio').empty();

        $.ajax({
            url: 'load_estadios.php',
            method: 'GET',
            success: function (data) {
                const estadios = JSON.parse(data); 
                $('#modificarEstadio').append('<option value="">Seleccione un estadio</option>');

                estadios.forEach(function (estadio) {
                    const selected = estadio.estadio_id === estadio_id ? 'selected' : '';
                    $('#modificarEstadio').append(`<option value="${estadio.estadio_id}" ${selected}>${estadio.estadio_id}</option>`);
                });

                $('#modalModificar').show();
            },
            error: function () {
                alert('Error al cargar los estadios.');
            }
        });
    });

    $('.eliminar-partido').on('click', function () {
        const identificadorPartido = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este partido?')) {
            $.ajax({
                url: 'eliminar_partido.php',
                method: 'POST',
                data: { identificadorPartido: identificadorPartido },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('tr[data-id="' + identificadorPartido + '"]').remove();
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

$('#modificarPartidoForm').on('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    $.ajax({
        url: 'modificar_partido.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            alert('Partido modificado exitosamente');
            $('#modalModificar').hide();
            location.reload(); 
        },
        error: function () {
            alert('Error al modificar el partido.');
        }
    });
});

$('#altaDato').on('click', function () {
    $('#modalAlta').show(); 
});

$('.close').on('click', function () {
    $('#modalAlta').hide(); 
});

$('#altaPartidoForm').on('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this); 

    $.ajax({
        url: 'alta_partido.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            alert('Partido dado de alta exitosamente');
            $('#altaPartidoForm')[0].reset();
            $('#modalAlta').hide();
            cargarDatos();
        },
        error: function () {
            alert('Error al dar de alta el partido');
        }
    });
});


    </script>
</body>

</html>