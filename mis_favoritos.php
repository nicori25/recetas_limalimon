<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT recetas.*, usuarios.nombre 
        FROM favoritos
        JOIN recetas ON favoritos.receta_id = recetas.id
        JOIN usuarios ON recetas.usuario_id = usuarios.id
        WHERE favoritos.usuario_id = ?
        ORDER BY favoritos.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="css/style.css">
<?php include("header.php"); ?>

<div class="container">
    <h2>⭐ Mis Favoritos</h2>

    <div class="recetas-grid">
        <?php if ($result->num_rows == 0) { ?>
            <p>No tenés recetas favoritas todavía</p>
        <?php } ?>

        <?php while ($row = $result->fetch_assoc()) { ?>
            
            <div class="card">
                <h3><?php echo $row['titulo']; ?></h3>
                <p><?php echo $row['descripcion']; ?></p>
                <p><?php echo $row['ingredientes']; ?></p>
                <p><strong><?php echo $row['pasos']; ?></strong></p>
                <p><strong><?php echo $row['nombre']; ?></strong></p>
                <span class="tag"><?php echo $row['tipo']; ?></span>

                <!-- ❌ BOTÓN QUITAR FAVORITO -->
                <form action="favorito.php" method="POST">
                    <input type="hidden" name="receta_id" value="<?php echo $row['id']; ?>">
                    <button type="submit">❌ Quitar de favoritos</button>
                </form>
            </div>

        <?php } ?>
    </div>
</div>