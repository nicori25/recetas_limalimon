<?php
include("config.php");

$sql = "SELECT recetas.*, usuarios.nombre 
        FROM recetas 
        JOIN usuarios ON recetas.usuario_id = usuarios.id 
        ORDER BY recetas.id DESC LIMIT 6";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recetas LimaLimón</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php session_start(); ?>
<?php include("header.php"); ?>

<div class="container">
    <h2>Bienvenido</h2>
    <p>Descubrí recetas seguras y deliciosas</p>
</div>
<div class="container">
    <h2>Recetas recientes</h2>

    <div class="recetas-grid">
    <?php while ($row = $result->fetch_assoc()) { ?>
        
        <div class="card">
            <h3><?php echo $row['titulo']; ?></h3>
            <p><?php echo $row['descripcion']; ?></p>
            <p><strong><?php echo $row['nombre']; ?></strong></p>
            <span class="tag"><?php echo $row['tipo']; ?></span>
        </div>

    <?php } ?>
    </div>
</div>
</body>
</html>