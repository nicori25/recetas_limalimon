<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];
$receta_id = $_POST['receta_id'];

// Verifica si ya existe
$stmt = $conn->prepare("SELECT * FROM favoritos WHERE usuario_id=? AND receta_id=?");
$stmt->bind_param("ii", $user_id, $receta_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Agrega favorito
    $stmt = $conn->prepare("INSERT INTO favoritos (usuario_id, receta_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $receta_id);
    $stmt->execute();
} else {
    // Quita favorito
    $stmt = $conn->prepare("DELETE FROM favoritos WHERE usuario_id=? AND receta_id=?");
    $stmt->bind_param("ii", $user_id, $receta_id);
    $stmt->execute();
}

header("Location: mis_favoritos.php");
exit();
?>