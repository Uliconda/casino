<?php
class Tragaperras {
    private $simbolos = ['🍒', '🍋', '🍇', '🍊', '🔔', '7️⃣'];
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->conectar();
    }

    public function girar($id_usuario, $apuesta) {
        try {
            $resultado = $this->generarCombinacion();
            $premio = $this->calcularPremio($resultado, $apuesta);

            $stmt = $this->conn->prepare("INSERT INTO tragaperras_resultados (id_usuario, combinacion, monto_apostado, monto_ganado, resultado) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $id_usuario, 
                implode(',', $resultado), 
                $apuesta, 
                $premio, 
                $premio >= 0 ? 'ganancia' : 'perdida'
            ]);

            return [
                'combinacion' => $resultado,
                'premio' => $premio
            ];
        } catch (PDOException $e) {
            error_log("Error en tragaperras: " . $e->getMessage());
            return [
                'combinacion' => [],
                'premio' => -$apuesta
            ];
        }
    }

    private function generarCombinacion() {
        $combinacion = [];
        for ($i = 0; $i < 3; $i++) {
            $combinacion[] = $this->simbolos[array_rand($this->simbolos)];
        }
        return $combinacion;
    }

    private function calcularPremio($combinacion, $apuesta) {
        $unico = count(array_unique($combinacion));
        switch ($unico) {
            case 1: return $apuesta * 5; // Todos iguales
            case 2: return $apuesta * 2; // Dos iguales
            default: return -$apuesta; // No hay premio
        }
    }
}