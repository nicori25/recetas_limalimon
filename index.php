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

        <!-- 📌 DATOS -->
        <h3><?php echo $row['titulo']; ?></h3>
        <br>
        <p><?php echo $row['descripcion']; ?></p>
        <br>
        <p><?php echo $row['ingredientes']; ?></p>
        <br>
        <p><strong><?php echo $row['pasos']; ?></strong></p>
        <br>
        <p><strong>Por: <?php echo $row['nombre']; ?></strong></p>
        <br>
        <span class="tag"><?php echo $row['tipo']; ?></span>
        <br>

        <!-- ⭐ PROMEDIO -->
        <?php
        $stmt = $conn->prepare("SELECT AVG(puntuacion) as promedio FROM valoraciones WHERE receta_id=?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $promedio = round($data['promedio'], 1);
        ?>

        <p>⭐ <?php echo $promedio ? $promedio : "Sin votos"; ?></p>

        <?php if (isset($_SESSION['user_id'])) { ?>

            <!-- ⭐ DETECTAR FAVORITO -->
            <?php
            $fav = $conn->prepare("SELECT 1 FROM favoritos WHERE usuario_id=? AND receta_id=?");
            $fav->bind_param("ii", $_SESSION['user_id'], $row['id']);
            $fav->execute();
            $resFav = $fav->get_result();
            $esFavorito = $resFav->num_rows > 0;
            ?>

            <!-- ⭐ BOTÓN FAVORITO -->
            <form action="favorito.php" method="POST">
                <input type="hidden" name="receta_id" value="<?php echo $row['id']; ?>">
                
                <?php if ($esFavorito) { ?>
                    <button type="submit">❌ Quitar favorito</button>
                <?php } else { ?>
                    <button type="submit">⭐ Agregar a favoritos</button>
                <?php } ?>
            </form>

            <!-- ⭐ VALORAR -->
            <form action="valorar.php" method="POST">
                <input type="hidden" name="receta_id" value="<?php echo $row['id']; ?>">

                <select name="puntuacion">
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>

                <button type="submit">Valorar</button>
            </form>

            <!-- 💬 COMENTAR -->
            <form action="comentar.php" method="POST">
                <input type="hidden" name="receta_id" value="<?php echo $row['id']; ?>">
                <textarea name="comentario" placeholder="Escribí tu opinión..." required></textarea>
                <button type="submit">Comentar</button>
            </form>

        <?php } else { ?>
            <p>Iniciá sesión para interactuar</p>
        <?php } ?>

        <!-- 🗨️ MOSTRAR COMENTARIOS -->
        <?php
        $stmt = $conn->prepare("SELECT comentarios.*, usuarios.nombre 
                                FROM comentarios 
                                JOIN usuarios ON comentarios.usuario_id = usuarios.id 
                                WHERE receta_id=? 
                                ORDER BY fecha DESC");

        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $comentarios = $stmt->get_result();

        while ($c = $comentarios->fetch_assoc()) {
        ?>
            <p><strong><?php echo $c['nombre']; ?>:</strong> <?php echo $c['comentario']; ?></p>
        <?php } ?>

    </div>

<?php } ?>
    </div>
</div>
</body>
</html>