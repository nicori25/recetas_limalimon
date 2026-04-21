<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// SOLO elimina si la receta es del usuario
$stmt = $conn->prepare("DELETE FROM recetas WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

header("Location: mis_recetas.php");
exit();
?>