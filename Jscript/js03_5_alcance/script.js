function asignacionConDeclaracion() {
    const div = document.getElementById("resultado");
    div.innerHTML = `
        <label>Ingrese un valor para la variable global:</label><br>
        <input type="text" id="inputLocal">
        <button onclick="guardarLocal()">Aceptar</button>
    `;
}

function guardarLocal() {
    var valor = document.getElementById("inputLocal").value;
    var variableGlobal = valor;
    document.getElementById("resultado").innerHTML =
        "El valor de la variable local ingresada es: " + variableGlobal + "<br>" +
        "El tipo de la variable local ingresada es: " + typeof variableGlobal;
}

function asignacionSinDeclaracion() {
    const div = document.getElementById("resultado");
    div.innerHTML = `
        <label>Ingrese un valor para la variable global:</label><br>
        <input type="text" id="inputGlobal">
        <button onclick="guardarGlobal()">Aceptar</button>
    `;
}

function guardarGlobal() {
    variableGlobal = document.getElementById("inputGlobal").value;
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
