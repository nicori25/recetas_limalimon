<header class="header">
    <div class="logo">
        🍋 <span>LimaLimón</span>
    </div>

    <!-- BUSCADOR -->
    <form method="GET" action="recetas.php" class="search-box">
        <input type="text" name="buscar" placeholder="Buscar recetas...">
        <button type="submit">🔍</button>
    </form>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="recetas.php">Recetas</a>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="mis_recetas.php">Mis recetas</a>
            <a href="perfil.php">Perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Registro</a>
        <?php } ?>
    </nav>
</header>