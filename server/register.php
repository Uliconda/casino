<?php
session_start();
header('Content-Type: application/json'); // Establecer el tipo de contenido como JSON

include 'config.php';

$response = ['success' => false, 'error' => '', 'redirect' => ''];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar datos recibidos
    if (empty($_POST["nombre"])) {
        $response['error'] = "El nombre completo es requerido";
        echo json_encode($response);
        exit();
    }

    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $response['error'] = "Ingrese un correo electrónico válido";
        echo json_encode($response);
        exit();
    }

    if (empty($_POST["password"]) || strlen($_POST["password"]) < 8) {
        $response['error'] = "La contraseña debe tener al menos 8 caracteres";
        echo json_encode($response);
        exit();
    }

    // Verificar si el email ya existe
    $checkEmail = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $checkEmail->bind_param("s", $_POST["email"]);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $response['error'] = "Este correo electrónico ya está registrado";
        echo json_encode($response);
        exit();
    }
    $checkEmail->close();

    // Procesar registro
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $password);

    if ($stmt->execute()) {
        $_SESSION["usuario"] = $nombre;
        $_SESSION["email"] = $email;
        
        $response['success'] = true;
        $response['redirect'] = "../public/juegos.html"; // Cambiado de juegos.html a index.html
        echo json_encode($response);
    } else {
        $response['error'] = "Error al registrar: " . $stmt->error;
        echo json_encode($response);
    }
    
    $stmt->close();
} else {
    $response['error'] = "Método no permitido";
    echo json_encode($response);
}

$conn->close();
?>