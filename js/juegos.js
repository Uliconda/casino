async function cargarJuegos(categoria = "") {
  const url = categoria
    ? `../server/juegos.php?categoria=${categoria}`
    : `../server/juegos.php`;

  const res = await fetch(url);
  const juegos = await res.json();
  const contenedor = document.getElementById("lista-juegos");
  contenedor.innerHTML = "<h2>Juegos Disponibles</h2>";

  juegos.forEach(juego => {
    const div = document.createElement("div");
    div.innerHTML = `<h3>${juego.nombre}</h3><p>${juego.descripcion}</p>`;
    contenedor.appendChild(div);
  });
}

async function consultarIA() {
  const categoria = document.getElementById("categoria").value;

  // Cargar juegos filtrados
  await cargarJuegos(categoria);

  // Consultar IA
  const res = await fetch(`../server/ia.php?categoria=${categoria}`);
  const sugerencias = await res.json();

  const contenedorIA = document.getElementById("sugerencias");
  contenedorIA.innerHTML = "<h3>La IA te sugiere:</h3>";
  sugerencias.forEach(juego => {
    const p = document.createElement("p");
    p.textContent = juego;
    contenedorIA.appendChild(p);
  });
}

// Mostrar el nombre del usuario
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
  } else {
    console.error("No se pudo cargar el usuario.");
  }
}

window.onload = () => {
  cargarUsuario();
  cargarJuegos();

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
};