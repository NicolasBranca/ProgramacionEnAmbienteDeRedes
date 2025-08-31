const suma = (a, b) => {
  let c = a + b;
  return c;
};

function calcularSuma() {
  let n1 = parseFloat(document.getElementById("num1").value) || 0;
  let n2 = parseFloat(document.getElementById("num2").value) || 0;

  document.getElementById("resultado").value = suma(n1, n2);
}
