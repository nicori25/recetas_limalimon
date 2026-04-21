<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<link rel="stylesheet" href="css/style.css">

<?php include("header.php"); ?>

<div class="container">
    <h2>Hola <?php echo $_SESSION['user_nombre']; ?></h2>

    <div class="form-box">
        <h3>Cargar receta</h3>
        <form action="guardar_recetas.php" method="POST">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descripcion" placeholder="Descripción"></textarea>
            <textarea name="ingredientes" placeholder="Ingredientes"></textarea>
            <textarea name="pasos" placeholder="Paso a paso de la receta"></textarea>

            <select name="tipo">
                <option value="sin gluten">Sin gluten</option>
                <option value="vegano">Vegano</option>
                <option value="sin lactosa">Sin lactosa</option>
                <option value="vegetariana">Sin lactosa</option>
                <option value="sin azúcar">Sin azúcar</option>
            </select>

            <button type="submit">Guardar receta</button>
        </form>
    </div>
</div>
<?php
include("config.php");

$sql = "SELECT recetas.*, usuarios.nombre 
        FROM recetas 
        JOIN usuarios ON recetas.usuario_id = usuarios.id 
        ORDER BY recetas.id DESC";
$result = $conn->query($sql);
?>

