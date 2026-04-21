<?php
session_start();
include("config.php");

$usuario_id = $_SESSION['user_id'];
$receta_id = $_POST['receta_id'];

$sql = "INSERT INTO favoritos (usuario_id, receta_id)
        VALUES ('$usuario_id', '$receta_id')";

if ($conn->query($sql)) {
    echo "Guardado en favoritos";
} else {
    echo "Error";
}
?>