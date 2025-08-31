function blanquear() {
  document.getElementById("nombre").value = "";
  document.getElementById("apellido").value = "";
}

function enviar() {
  let nombre = document.getElementById("nombre").value.trim();
  let apellido = document.getElementById("apellido").value.trim();

  if (confirm("¿Está seguro de enviar?")) {
    location.href = "respuestaFormulario.html?nombre=" + encodeURIComponent(nombre) + "&apellido=" + encodeURIComponent(apellido);
  }
}
