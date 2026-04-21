<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<link rel="stylesheet" href="css/style.css">

<header>
    <h1>🍋 LimaLimón</h1>
    <nav>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
</header>

<div class="container">
    <h2>Hola <?php echo $_SESSION['user_nombre']; ?></h2>

    <div class="form-box">
        <h3>Cargar receta</h3>
        <form action="guardar_receta.php" method="POST">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descripcion" placeholder="Descripción"></textarea>
            <textarea name="ingredientes" placeholder="Ingredientes"></textarea>

            <select name="tipo">
                <option value="sin gluten">Sin gluten</option>
                <option value="vegano">Vegano</option>
                <option value="sin lactosa">Sin lactosa</option>
            </select>

            <button type="submit">Guardar receta</button>
        </form>
    </div>
</div>
<?php
include("config.php");

$sql = "SELECT * FROM recetas ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="recetas-grid">
<?php while ($row = $result->fetch_assoc()) { ?>
    
    <div class="card">
        <h3><?php echo $row['titulo']; ?></h3>
        <p><?php echo $row['descripcion']; ?></p>
        <span class="tag"><?php echo $row['tipo']; ?></span>
    </div>

<?php } ?>
</div>