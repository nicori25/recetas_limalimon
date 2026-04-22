<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];
$receta_id = $_POST['receta_id'];
$puntuacion = $_POST['puntuacion'];

// Si ya valoró → actualiza
$stmt = $conn->prepare("SELECT * FROM valoraciones WHERE usuario_id=? AND receta_id=?");
$stmt->bind_param("ii", $user_id, $receta_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE valoraciones SET puntuacion=? WHERE usuario_id=? AND receta_id=?");
    $stmt->bind_param("iii", $puntuacion, $user_id, $receta_id);
} else {
    $stmt = $conn->prepare("INSERT INTO valoraciones (usuario_id, receta_id, puntuacion) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $receta_id, $puntuacion);
}

$stmt->execute();

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>