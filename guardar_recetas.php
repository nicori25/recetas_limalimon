<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    die("No autorizado");
}

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$ingredientes = $_POST['ingredientes'];
$tipo = $_POST['tipo'];
$usuario_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO recetas (titulo, descripcion, ingredientes, tipo, usuario_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $titulo, $descripcion, $ingredientes, $tipo, $usuario_id);

$stmt->execute();

header("Location: recetas.php");
?>