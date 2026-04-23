<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = $_POST['password'];
$preferencia = $_POST['preferencia_tipo']; // 👈 NUEVO

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios 
        SET nombre=?, email=?, password=?, preferencia_tipo=? 
        WHERE id=?");

    $stmt->bind_param("ssssi", 
        $nombre, 
        $email, 
        $password_hash, 
        $preferencia, 
        $user_id
    );

} else {

    $stmt = $conn->prepare("UPDATE usuarios 
        SET nombre=?, email=?, preferencia_tipo=? 
        WHERE id=?");

    $stmt->bind_param("sssi", 
        $nombre, 
        $email, 
        $preferencia, 
        $user_id
    );
}

$stmt->execute();

// 🧠 Actualizar sesión
$_SESSION['user_nombre'] = $nombre;
$_SESSION['preferencia_tipo'] = $preferencia; // 👈 IMPORTANTE

header("Location: perfil.php");
exit();
?>