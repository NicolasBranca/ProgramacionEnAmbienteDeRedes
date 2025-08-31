function mostrarZ() {
    var z = "Z es una variable String";
    document.getElementById("resultado").innerHTML =
        "Valor de z: " + z + "<br>Tipo de z: " + typeof z;
}

function mostrarQ() {
    let q = "Q tambien es una variable String";
    document.getElementById("resultado").innerHTML =
        "Valor de q: " + q + "<br>Tipo de q: " + typeof q;
}

function mostrarA() {
    var a = 4;
    document.getElementById("resultado").innerHTML =
        "Valor de a: " + a + "<br>Tipo de a: " + typeof a;
}

function mostrarB() {
    var b = "3";
    document.getElementById("resultado").innerHTML =
        "Valor de b: " + b + "<br>Tipo de b: " + typeof b;
}

function mostrarC() {
    var c = 7;
    document.getElementById("resultado").innerHTML =
        "Valor de c: " + c + "<br>Tipo de c: " + typeof c;
}

function mostrarSuma1() {
    var a = 4;
    var b = "3";
    var suma1 = a + b;
    document.getElementById("resultado").innerHTML =
        "suma1 = a + b: " + suma1 + "<br>Tipo de suma1: " + typeof suma1;
}

function mostrarSuma2() {
    var a = 4;
    var c = 7;
    var suma2 = a + c;
    document.getElementById("resultado").innerHTML =
        "suma2 = a + c: " + suma2 + "<br>Tipo de suma2: " + typeof suma2;
}
