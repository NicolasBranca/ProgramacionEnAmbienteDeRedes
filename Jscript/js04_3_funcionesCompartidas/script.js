let acumulador = 0;

function agregarNumero(num) {
    const display = document.getElementById('display');
    display.value += num;
}

function acumular() {
    const display = document.getElementById('display');
    const valor = Number(display.value) || 0;
    acumulador += valor;
    display.value = '';
}

function mostrarAcumulador() {
    document.getElementById('display').value = acumulador;
}

function borrarAcumulador() {
    acumulador = 0;
}

function borrarDisplay() {
    document.getElementById('display').value = '';
}
