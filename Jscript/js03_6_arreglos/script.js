let arregloFrutas = ["banana", "manzana"];
alert("El arregloFrutas ya tiene dos elementos asignados por programa (banana y manzana). agregue el tercer elemento con indice numerico.");

function mostrarDatos() {
    document.getElementById("tipo").innerHTML = "Tipo para arregloFrutas: " + typeof arregloFrutas;
    document.getElementById("elemento1").innerHTML = "Primer elemento cargado desde programa: " + arregloFrutas[0];
    document.getElementById("elemento2").innerHTML = "Segundo elemento cargado desde programa: " + arregloFrutas[1];
    document.getElementById("elemento3").innerHTML = arregloFrutas[2]
        ? "Tercer elemento cargado desde teclado: " + arregloFrutas[2]
        : "";
    document.getElementById("cantidad").innerHTML = "Cantidad de elementos: " + arregloFrutas.length;
}

function agregarFruta() {
    const input = document.getElementById("frutaInput");
    if (input.value.trim() !== "") {
        arregloFrutas[2] = input.value.trim();
        mostrarDatos();
        input.disabled = true;
    }
}

window.onload = function() {
    mostrarDatos();
};