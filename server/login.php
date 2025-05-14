<?php
session_start();
header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

include 'config.php';

$response = [
    'success' => false,
    'message' => '',
    'redirect' => '../public/juegos.html', // Redirección fija a juegos.html
    'usuario' => ''
];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response['message'] = "Método no permitido";
    echo json_encode($response);
    exit();
}

// Validar datos recibidos
if (empty($_POST["email"]) || empty($_POST["password"])) {
    $response['message'] = "Todos los campos son requeridos";
    echo json_encode($response);
    exit();
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

try {
    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['message'] = "No se encontró una cuenta con ese correo electrónico";
        echo json_encode($response);
        exit();
    }

    $user = $result->fetch_assoc();

    // Verificar la contraseña
    if (!password_verify($password, $user["password"])) {
        $response['message'] = "Contraseña incorrecta";
        echo json_encode($response);
        exit();
    }

    // Iniciar sesión
    $_SESSION["usuario"] = $user["nombre"];
    $_SESSION["usuario_id"] = $user["id"];
    
    // Configurar respuesta exitosa
    $response['success'] = true;
    $response['message'] = "Inicio de sesión exitoso";
    $response['usuario'] = $user["nombre"];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    $response['message'] = "Error en el servidor: " . $e->getMessage();
    echo json_encode($response);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>