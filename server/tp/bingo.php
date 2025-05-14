<?php
class Bingo {
    private $carton = [];
    private $numeros_sorteados = [];
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->conectar();
        $this->generarCarton();
    }

    private function generarCarton() {
        $numeros = range(1, 90);
        shuffle($numeros);
        $this->carton = array_slice($numeros, 0, 15);
    }

    public function jugar($id_usuario, $apuesta) {
        if (!is_numeric($id_usuario) || !is_numeric($apuesta) || $apuesta <= 0) {
            throw new InvalidArgumentException("Parámetros inválidos para jugar.");
        }

        try {
            $this->numeros_sorteados = $this->sortearNumeros();
            $aciertos = count(array_intersect($this->carton, $this->numeros_sorteados));
            $premio = $this->calcularPremio($aciertos, $apuesta);

            $stmt = $this->conn->prepare("INSERT INTO bingo_resultados (id_usuario, carton, numeros_sorteados, aciertos, monto_apostado, monto_ganado, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "isssdds",
                $id_usuario,
                implode(',', $this->carton),
                implode(',', $this->numeros_sorteados),
                $aciertos,
                $apuesta,
                $premio,
                $premio >= 0 ? 'ganado' : 'perdido'
            );
            $stmt->execute();

            return [
                'carton' => $this->carton,
                'numeros_sorteados' => $this->numeros_sorteados,
                'aciertos' => $aciertos,
                'premio' => $premio
            ];
        } catch (PDOException $e) {
            error_log("Error en bingo: " . $e->getMessage());
            return [
                'carton' => $this->carton,
                'numeros_sorteados' => [],
                'aciertos' => 0,
                'premio' => -$apuesta
            ];
        }
    }

    private function sortearNumeros() {
        $numeros = range(1, 90);
        shuffle($numeros);
        return array_slice($numeros, 0, 20);
    }

    private function calcularPremio($aciertos, $apuesta) {
        $premios = [
            0 => -$apuesta,
            5 => $apuesta * 2,
            10 => $apuesta * 5,
            15 => $apuesta * 10
        ];
        return $premios[$aciertos] ?? -$apuesta;
    }
}