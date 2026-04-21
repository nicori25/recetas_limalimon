<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM recetas WHERE usuario_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="css/style.css">
<?php include("header.php"); ?>

<div class="container">
    <h2>Mis recetas</h2>

    <div class="recetas-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            
            <div class="card">
                <h3><?php echo $row['titulo']; ?></h3>
                <p><?php echo $row['descripcion']; ?></p>
                <span class="tag"><?php echo $row['tipo']; ?></span>
                <a href="eliminar_receta.php?id=<?php echo $row['id']; ?>" 
                onclick="return confirm('¿Eliminar receta?')" 
                class="btn-delete">
                Eliminar
                </a>
                <a href="editar_receta.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                Editar
                </a>
            </div>

        <?php } ?>
    </div>
</div>