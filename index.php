<?php
session_start();
include("config.php");

// 🔍 BUSCADOR
$busqueda = $_GET['busqueda'] ?? "";

// 🧠 Guardar filtro manual
if (isset($_POST['tipo_filtro'])) {
    $_SESSION['tipo_filtro'] = $_POST['tipo_filtro'];
}

// 🔍 LÓGICA FINAL (manual > preferencia > todo)
$tipo = "";

if (!empty($_SESSION['tipo_filtro'])) {
    $tipo = $_SESSION['tipo_filtro'];
} elseif (!empty($_SESSION['preferencia_tipo'])) {
    $tipo = $_SESSION['preferencia_tipo'];
}

// 🧩 CONSULTA DINÁMICA
$sql = "SELECT recetas.*, usuarios.nombre 
        FROM recetas 
        JOIN usuarios ON recetas.usuario_id = usuarios.id 
        WHERE 1=1";

$params = [];
$types = "";

// 🔎 BUSCAR por título o ingredientes
if (!empty($busqueda)) {
    $sql .= " AND (titulo LIKE ? OR ingredientes LIKE ?)";
    $like = "%$busqueda%";
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}

// 🧠 FILTRO / PREFERENCIA
if (!empty($tipo)) {
    $sql .= " AND tipo = ?";
    $params[] = $tipo;
    $types .= "s";
}

// ORDEN
$sql .= " ORDER BY recetas.id DESC LIMIT 6";

// PREPARE
$stmt = $conn->prepare($sql);

// BIND DINÁMICO
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recetas LimaLimón</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="container">
    <h2>Bienvenido</h2>
    <p>Descubrí recetas seguras y deliciosas</p>
</div>

<div class="container">
    <!-- 📌 MOSTRAR BÚSQUEDA -->
    <?php if (!empty($busqueda)) { ?>
        <p>Resultados para: <strong><?php echo $busqueda; ?></strong></p>
    <?php } ?>

    <!-- 🔍 FILTRO -->
    <form method="POST" action="" style="margin-bottom: 20px;">
        <select name="tipo_filtro">
            <option value="">Todos</option>
            <option value="vegano">Vegano</option>
            <option value="sin gluten">Sin gluten</option>
            <option value="sin lactosa">Sin lactosa</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <!-- 📌 MOSTRAR FILTRO -->
    <?php if (!empty($_SESSION['tipo_filtro'])) { ?>
        <p>Filtro manual: <strong><?php echo $_SESSION['tipo_filtro']; ?></strong></p>

        <form method="POST">
            <button name="tipo_filtro" value="">Mostrar todas</button>
        </form>

    <?php } elseif (!empty($_SESSION['preferencia_tipo'])) { ?>
        <p>Preferencia automática: <strong><?php echo $_SESSION['preferencia_tipo']; ?></strong></p>
    <?php } ?>

    <h2>Recetas recientes</h2>

    <div class="recetas-grid">
    <?php while ($row = $result->fetch_assoc()) { ?>
    
    <div class="card">

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
        $stmt2 = $conn->prepare("SELECT AVG(puntuacion) as promedio FROM valoraciones WHERE receta_id=?");
        $stmt2->bind_param("i", $row['id']);
        $stmt2->execute();
        $res = $stmt2->get_result();
        $data = $res->fetch_assoc();
        $promedio = round($data['promedio'], 1);
        ?>

        <p>⭐ <?php echo $promedio ? $promedio : "Sin votos"; ?></p>

        <?php if (isset($_SESSION['user_id'])) { ?>

            <!-- ⭐ FAVORITOS -->
            <?php
            $fav = $conn->prepare("SELECT 1 FROM favoritos WHERE usuario_id=? AND receta_id=?");
            $fav->bind_param("ii", $_SESSION['user_id'], $row['id']);
            $fav->execute();
            $resFav = $fav->get_result();
            $esFavorito = $resFav->num_rows > 0;
            ?>

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

        <!-- 🗨️ COMENTARIOS -->
        <?php
        $stmt3 = $conn->prepare("SELECT comentarios.*, usuarios.nombre 
                                FROM comentarios 
                                JOIN usuarios ON comentarios.usuario_id = usuarios.id 
                                WHERE receta_id=? 
                                ORDER BY fecha DESC");

        $stmt3->bind_param("i", $row['id']);
        $stmt3->execute();
        $comentarios = $stmt3->get_result();

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