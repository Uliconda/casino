<?php
<?php
session_start();
require_once '../tp/bingo.php';
require_once 'config.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["success" => false, "error" => "Usuario no autenticado"]);
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];
$apuesta = 100; // Apuesta fija para este ejemplo

$bingo = new Bingo();
$resultado = $bingo->jugar($id_usuario, $apuesta);

echo json_encode([
    "success" => true,
    "carton" => $resultado['carton'],
    "numeros_sorteados" => $resultado['numeros_sorteados'],
    "aciertos" => $resultado['aciertos'],
    "premio" => $resultado['premio']
]);
?>