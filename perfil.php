<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nombre, email, preferencia_tipo FROM usuarios WHERE id = ?");
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
            <select name="preferencia_tipo">
                <option value="">Sin preferencia</option>

                <option value="vegano" <?php if(($user['preferencia_tipo'] ?? "")=="vegano") echo "selected"; ?>>
                    Vegano
                </option>

                <option value="sin gluten" <?php if(($user['preferencia_tipo'] ?? "")=="sin gluten") echo "selected"; ?>>
                    Sin gluten
                </option>

                <option value="sin lactosa" <?php if(($user['preferencia_tipo'] ?? "")=="sin lactosa") echo "selected"; ?>>
                    Sin lactosa
                </option>
            </select>
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