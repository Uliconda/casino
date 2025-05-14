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

document.getElementById("jugar-bingo").addEventListener("click", async () => {
  const res = await fetch("../server/bingo.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ apuesta: 100 }),
  });

  const data = await res.json();
  const resultadoDiv = document.getElementById("resultado-bingo");

  if (data.success) {
    resultadoDiv.innerHTML = `
      <p>Cartón: ${data.carton.join(", ")}</p>
      <p>Números sorteados: ${data.numeros_sorteados.join(", ")}</p>
      <p>Aciertos: ${data.aciertos}</p>
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