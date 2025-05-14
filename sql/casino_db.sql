CREATE DATABASE IF NOT EXISTS casino;
USE casino;

CREATE TABLE IF NOT EXISTS juegos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria VARCHAR(50)
);

INSERT INTO juegos (nombre, descripcion, categoria) VALUES
('Ruleta', 'Juego clásico de azar', 'azar'),
('Blackjack', 'Juego de cartas contra la banca', 'cartas'),
('Poker', 'Juego de estrategia y cartas', 'estrategia'),
('Tragamonedas', 'Máquina de azar con giros', 'azar'),
('Texas Hold'em', 'Variante popular del poker', 'cartas'),
('Ajedrez de apuestas', 'Juego de estrategia adaptado al casino', 'estrategia');