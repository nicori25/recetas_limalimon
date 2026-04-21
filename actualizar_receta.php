<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];

$id = $_POST['id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$ingredientes = $_POST['ingredientes'];
$tipo = $_POST['tipo'];

$stmt = $conn->prepare("UPDATE recetas 
    SET titulo=?, descripcion=?, ingredientes=?, tipo=? 
    WHERE id=? AND usuario_id=?");

$stmt->bind_param("ssssii", $titulo, $descripcion, $ingredientes, $tipo, $id, $user_id);
$stmt->execute();

header("Location: mis_recetas.php");
exit();
?>