<header class="header">

    <div class="logo">
        <img src="img/logo.png" alt="logo">
    </div>

    <!-- 🔎 BUSCADOR -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <input type="text" name="busqueda" placeholder="Buscar recetas o ingredientes..." value="<?php echo $_GET['busqueda'] ?? ""; ?>">
    <button type="submit">🔍 Buscar</button>
</form>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="recetas.php">Recetas</a>
        <a href="ruleta.php">Ruleta</a>

        <?php if (isset($_SESSION['user_id'])) { ?>
            
            <div class="dropdown">
                <button class="dropbtn">Mi cuenta ⬇️</button>

                <div class="dropdown-content">
                    <a href="mis_recetas.php">Mis recetas</a>
                    <a href="mis_favoritos.php">Mis favoritos</a>
                    <a href="perfil.php">Perfil</a>
                    <a href="logout.php">Cerrar sesión</a>
                </div>
            </div>

        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Registro</a>
        <?php } ?>
    </nav>

</header>
<style>
    .header img{
        width: 200px;
        heigt: auto;
    }
   nav {
    display: flex;
    align-items: center;
    gap: 20px;
}

nav a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

/* CONTENEDOR */
.dropdown {
    position: relative;
}

/* BOTÓN */
.dropbtn {
    background: none;
    border: none;
    font-weight: bold;
    cursor: pointer;
}

/* CONTENIDO OCULTO */
.dropdown-content {
    display: none;
    position: absolute;
    background: white;
    min-width: 180px;
    top: 30px;
    right: 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    border-radius: 10px;
    overflow: hidden;
}

/* LINKS */
.dropdown-content a {
    display: block;
    padding: 10px;
    color: #333;
}

.dropdown-content a:hover {
    background: #f5f5f5;
}

/* MOSTRAR AL PASAR EL MOUSE */
.dropdown:hover .dropdown-content {
    display: block;
}
</style>
