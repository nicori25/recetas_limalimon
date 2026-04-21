<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $nombre, $email, $password_hash, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $nombre, $email, $user_id);
}

$stmt->execute();

$_SESSION['user_nombre'] = $nombre;

header("Location: perfil.php");
exit();
?>