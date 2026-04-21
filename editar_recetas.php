<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Traer receta
$stmt = $conn->prepare("SELECT * FROM recetas WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$receta = $result->fetch_assoc();
?>

<link rel="stylesheet" href="css/styles.css">
<?php include("header.php"); ?>

<div class="container">
    <h2>Editar receta</h2>

    <div class="form-box">
        <form action="actualizar_receta.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $receta['id']; ?>">

            <input type="text" name="titulo" value="<?php echo $receta['titulo']; ?>" required>

            <textarea name="descripcion"><?php echo $receta['descripcion']; ?></textarea>

            <textarea name="ingredientes"><?php echo $receta['ingredientes']; ?></textarea>

            <input type="text" name="tipo" value="<?php echo $receta['tipo']; ?>">

            <button type="submit">Actualizar receta</button>
        </form>
    </div>
</div>