function asignacionConDeclaracion() {
    var valor = prompt("Ingrese un valor para la variable global (declaración + asignación):");
    var variableGlobal = valor;
    document.getElementById("resultado").innerHTML =
        "El valor de la variable local ingresada es: " + variableGlobal + "<br>" +
        "El tipo de la variable local ingresada es: " + typeof variableGlobal;
}

function asignacionSinDeclaracion() {
    variableGlobal = prompt("Ingrese un valor para la variable global (asignación forzada):");
    document.getElementById("resultado").innerHTML =
        "El valor de la variable global ingresada es: " + variableGlobal + "<br>" +
        "El tipo de la variable global ingresada es: " + typeof variableGlobal;
}

function mostrarVariableGlobal() {
    const div = document.getElementById("resultado");
    if (typeof variableGlobal === "undefined" || variableGlobal === "") {
        alert("La variable global no ha sido asignada");
        div.innerHTML = "";
    } else {
        alert("El valor de la variableGlobal es: " + variableGlobal);
        div.innerHTML =
            "El valor de la variableGlobal es: " + variableGlobal + "<br>" +
            "La variableGlobal es de tipo: " + typeof variableGlobal;
    }
}

function mostrarCartel(texto) {
    const cartel = document.getElementById("cartelito");
    cartel.innerText = texto;
    cartel.style.display = "inline";
    document.body.onmousemove = function(e) {
        cartel.style.left = (e.pageX + 10) + "px";
        cartel.style.top = (e.pageY + 10) + "px";
    };
}

function ocultarCartel() {
    const cartel = document.getElementById("cartelito");
    cartel.style.display = "none";
    document.body.onmousemove = null;
}
