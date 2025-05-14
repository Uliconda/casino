function consultarIA() {
  const categoria = document.getElementById('categoria').value;
  const contenedor = document.getElementById('sugerencias');

  const recomendaciones = {
    azar: [
      { nombre: "Ruleta VIP", desc: "Giros con probabilidades aumentadas", icon: "🎡", id: "ruleta-vip" },
      { nombre: "Mega Slots", desc: "Jackpot progresivo $1,000,000", icon: "🎰", id: "mega-slots" }
    ],
    estrategia: [
      { nombre: "Blackjack Pro", desc: "Mesa de alto límite", icon: "♠️", id: "blackjack-pro" },
      { nombre: "Póker Texas", desc: "Torneo semanal", icon: "🃏", id: "poker-texas" }
    ],
    cartas: [
      { nombre: "Bacará Clásico", desc: "Modo squeeze disponible", icon: "🎴", id: "bacara-clasico" },
      { nombre: "Póquer 3 Cartas", desc: "Rápido y emocionante", icon: "♥️", id: "poker-3-cartas" }
    ]
  };

  // Validación de categoría válida
  if (!recomendaciones[categoria]) {
    contenedor.innerHTML = "<p>No hay recomendaciones disponibles.</p>";
    return;
  }

  let html = '<div class="recommendations-grid">';
  recomendaciones[categoria].forEach(juego => {
    html += `
      <div class="recommendation-item">
        <div class="recommendation-icon">${juego.icon}</div>
        <h4>${juego.nombre}</h4>
        <p>${juego.desc}</p>
        <button class="btn-recommendation" onclick="location.href='juego-detalle.html?id=${juego.id}'">
          Ver juego
        </button>
      </div>
    `;
  });
  html += '</div>';

  contenedor.innerHTML = html;
}
