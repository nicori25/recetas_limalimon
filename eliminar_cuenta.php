<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

session_destroy();

header("Location: index.php");
exit();
?>