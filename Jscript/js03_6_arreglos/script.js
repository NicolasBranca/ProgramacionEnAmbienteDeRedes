let arregloFrutas = ["banana", "manzana"];
let tercerElemento = null;
window.onload = function() {
    alert("El arregloFrutas ya tiene dos elementos asignados por programa (banana y manzana). agregue el tercer elemento con indice numerico.");
    tercerElemento = prompt("Ingrese el tercer elemento para el arregloFrutas:");
    if (tercerElemento && tercerElemento.trim() !== "") {
        arregloFrutas[2] = tercerElemento.trim();
        document.getElementById("frutaInput").value = tercerElemento.trim();
        document.getElementById("frutaInput").disabled = true;
    }
    mostrarDatos();
};

function mostrarDatos() {
    document.getElementById("tipo").innerHTML = "Tipo para arregloFrutas: " + typeof arregloFrutas;
    document.getElementById("elemento1").innerHTML = "Primer elemento cargado desde programa: " + arregloFrutas[0];
    document.getElementById("elemento2").innerHTML = "Segundo elemento cargado desde programa: " + arregloFrutas[1];
    document.getElementById("elemento3").innerHTML = arregloFrutas[2]
        ? "Tercer elemento cargado desde teclado: " + arregloFrutas[2]
        : "";
    document.getElementById("cantidad").innerHTML = "Cantidad de elementos: " + arregloFrutas.length;
}
