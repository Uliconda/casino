<?php
class Usuario {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->conectar();
    }

    public function registrar($username, $email, $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO usuarios (username, email, password, saldo) VALUES (?, ?, ?, 1000)");
            return $stmt->execute([$username, $email, $hash]);
        } catch (PDOException $e) {
            // Manejar errores de duplicados o conexión
            error_log("Error de registro: " . $e->getMessage());
            return false;
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password'])) {
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error de login: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarSaldo($id_usuario, $monto) {
        try {
            $stmt = $this->conn->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id_usuario = ?");
            return $stmt->execute([$monto, $id_usuario]);
        } catch (PDOException $e) {
            error_log("Error al actualizar saldo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerSaldo($id_usuario) {
        try {
            $stmt = $this->conn->prepare("SELECT saldo FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $resultado = $stmt->fetch();
            return $resultado ? $resultado['saldo'] : 0;
        } catch (PDOException $e) {
            error_log("Error al obtener saldo: " . $e->getMessage());
            return 0;
        }
    }
}

class Tragaperras {
    private $simbolos = ['🍒', '🍋', '🍇', '🍊', '🔔', '7️⃣'];
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->conectar();
    }

    public function girar($id_usuario, $apuesta) {
        if (!is_numeric($id_usuario) || !is_numeric($apuesta) || $apuesta <= 0) {
            throw new InvalidArgumentException("Parámetros inválidos para jugar.");
        }

        try {
            $resultado = $this->generarCombinacion();
            $premio = $this->calcularPremio($resultado, $apuesta);

            $stmt = $this->conn->prepare("INSERT INTO tragaperras_resultados (id_usuario, combinacion, monto_apostado, monto_ganado, resultado) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $id_usuario, 
                implode(',', $resultado), 
                $apuesta, 
                $premio, 
                $premio > 0 ? 'ganancia' : 'perdida'
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