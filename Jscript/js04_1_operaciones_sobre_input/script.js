function mostrarValor() {
    const input = document.getElementById("valorInput");
    alert(input.value);
}

function sumarUno() {
    const input = document.getElementById("valorInput");
    let valor = parseFloat(input.value) || 0;
    valor += 1;
    input.value = valor;
}

function potenciaDos() {
    const input = document.getElementById("valorInput");
    let valor = parseFloat(input.value) || 0;
    input.value = valor ** 2;
}

function multiplicaDos() {
    const input = document.getElementById("valorInput");
    let valor = parseFloat(input.value) || 0;
    input.value = valor * 2;
}

function dosPotenciaX() {
    const input = document.getElementById("valorInput");
    let valor = parseFloat(input.value) || 0;
    input.value = 2 ** valor;
}