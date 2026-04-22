<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];
$receta_id = $_POST['receta_id'];
$comentario = $_POST['comentario'];

$stmt = $conn->prepare("INSERT INTO comentarios (usuario_id, receta_id, comentario) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $receta_id, $comentario);
$stmt->execute();

header("Location: recetas.php");
exit();
?>