<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<link rel="stylesheet" href="css/style.css">

<?php include("header.php"); ?>

<div class="container perfil-container">
    <h2>Mi Perfil</h2>

    <div class="perfil-box">
        <h3>Datos personales</h3>
        <form action="actualizar_perfil.php" method="POST">
            <input type="text" name="nombre" value="<?php echo $user['nombre']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <input type="password" name="password" placeholder="Nueva contraseña (opcional)">
            <button type="submit">Actualizar</button>
        </form>
    </div>

    <div class="perfil-box">
        <h3>Eliminar cuenta</h3>
        <form action="eliminar_cuenta.php" method="POST">
            <button type="submit" style="background:red;">Eliminar cuenta</button>
        </form>
    </div>
</div>