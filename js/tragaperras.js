async function cargarUsuario() {
  const res = await fetch("../server/usuario.php");
  const data = await res.json();

  if (data.success) {
    const usuarioDiv = document.getElementById("usuario-info");
    usuarioDiv.innerHTML = `
      <p>Bienvenido, <span style="font-size: 20px; font-weight: bold;">${data.usuario}</span></p>
      <button id="cerrar-sesion">Cerrar sesión</button>
    `;

    // Manejar el cierre de sesión
    document.getElementById("cerrar-sesion").addEventListener("click", async () => {
      await fetch("../server/logout.php");
      window.location.href = "../public/login.html";
    });
  }
}

document.getElementById("jugar-tragaperras").addEventListener("click", async () => {
  const res = await fetch("../server/tragaperras.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ apuesta: 50 }),
  });

  const data = await res.json();
  const resultadoDiv = document.getElementById("resultado-tragaperras");

  if (data.success) {
    resultadoDiv.innerHTML = `
      <p>Combinación: ${data.combinacion.join(" - ")}</p>
      <p>Premio: $${data.premio}</p>
    `;
  } else {
    resultadoDiv.innerHTML = `<p>Error: ${data.error}</p>`;
  }
});

document.getElementById("regresar").addEventListener("click", () => {
  window.location.href = "juegos.html";
});

window.onload = cargarUsuario;