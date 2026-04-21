<?php
include("config.php");

$sql = "SELECT * FROM recetas";
$result = $conn->query($sql);

$recetas = [];

while ($row = $result->fetch_assoc()) {
    $recetas[] = $row;
}

echo json_encode($recetas);
?>