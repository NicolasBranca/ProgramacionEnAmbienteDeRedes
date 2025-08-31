function obtenerValores() {
    const v1 = Number(document.getElementById('entrada1').value);
    const v2 = Number(document.getElementById('entrada2').value);
    const v3 = Number(document.getElementById('entrada3').value);
    return [v1, v2, v3];
}

function sumar() {
    const [v1, v2, v3] = obtenerValores();
    document.getElementById('resultado').value = v1 + v2 + v3;
}

function promediar() {
    const [v1, v2, v3] = obtenerValores();
    document.getElementById('resultado').value = ((v1 + v2 + v3) / 3).toFixed(2);
}

function mayor() {
    const [v1, v2, v3] = obtenerValores();
    document.getElementById('resultado').value = Math.max(v1, v2, v3);
}
