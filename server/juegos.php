<?php
header("Content-Type: application/json"); // Indicar que la respuesta es JSON
include 'config.php';

$categoria = $_GET['categoria'] ?? null;

if ($categoria) {
    $stmt = $conn->prepare("SELECT * FROM juegos WHERE categoria = ?");
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM juegos");
}

$juegos = [];

while ($row = $result->fetch_assoc()) {
    $juegos[] = $row;
}

// Devolver la respuesta en formato JSON
echo json_encode($juegos);

// Liberar recursos y cerrar la conexión
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>