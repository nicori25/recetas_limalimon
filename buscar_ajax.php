<?php
include("config.php");

$busqueda = $_GET['q'] ?? "";

$sql = "SELECT id, titulo FROM recetas WHERE titulo LIKE ? OR ingredientes LIKE ? LIMIT 5";

$stmt = $conn->prepare($sql);
$like = "%$busqueda%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div class='resultado-item' data-id='{$row['id']}'>{$row['titulo']}</div>";
}
?>