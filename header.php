<header class="header">

    <div class="logo">
        <img src="img/logo.png" alt="logo">
    </div>

    <!-- 🔎 BUSCADOR CON VIVO -->
    <div class="buscador-container">
        <form method="GET" action="index.php" class="buscador-nav" onsubmit="return true;">
            <input 
                type="text" 
                id="buscador"
                name="busqueda" 
                placeholder="Buscar recetas..." 
                autocomplete="off"
                value="<?php echo $_GET['busqueda'] ?? ""; ?>"
            >
            <button type="submit">🔍</button>
        </form>

        <!-- RESULTADOS EN VIVO -->
        <div id="resultados"></div>
    </div>

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
    height: auto;
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

/* CONTENEDOR BUSCADOR */
.buscador-container {
    position: relative;
}

#buscador {
    padding: 8px;
    width: 200px;
}

/* RESULTADOS */
#resultados {
    position: absolute;
    background: white;
    width: 100%;
    border: 1px solid #ccc;
    z-index: 999;
    border-radius: 8px;
    overflow: hidden;
}

.resultado-item {
    padding: 8px;
    cursor: pointer;
}

.resultado-item:hover {
    background: #f0f0f0;
}

/* DROPDOWN */
.dropdown {
    position: relative;
}

.dropbtn {
    background: none;
    border: none;
    font-weight: bold;
    cursor: pointer;
}

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

.dropdown-content a {
    display: block;
    padding: 10px;
    color: #333;
}

.dropdown-content a:hover {
    background: #f5f5f5;
}

.dropdown:hover .dropdown-content {
    display: block;
}
</style>

<!-- 🔥 JS BUSCADOR EN VIVO -->
<script>
const input = document.getElementById("buscador");
const resultados = document.getElementById("resultados");

input.addEventListener("keyup", function() {
    let query = input.value;

    if (query.length < 2) {
        resultados.innerHTML = "";
        return;
    }

    fetch("buscar_ajax.php?q=" + query)
        .then(res => res.text())
        .then(data => {
            resultados.innerHTML = data;
        });
});

// click en resultado
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("resultado-item")) {
        let texto = e.target.innerText;
        window.location.href = "index.php?busqueda=" + texto;
    }
});
</script>