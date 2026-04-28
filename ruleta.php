<?php
session_start();
include("config.php");

// 📅 Detectar estación (Argentina)
$mes = date("n");

if ($mes == 12 || $mes == 1 || $mes == 2) {
    $estacion = "verano";
} elseif ($mes >= 3 && $mes <= 5) {
    $estacion = "otoño";
} elseif ($mes >= 6 && $mes <= 8) {
    $estacion = "invierno";
} else {
    $estacion = "primavera";
}

// 📥 Traer recetas de esa estación
$stmt = $conn->prepare("SELECT * FROM recetas WHERE estacion = ?");
$stmt->bind_param("s", $estacion);
$stmt->execute();
$result = $stmt->get_result();

$recetas = [];

while ($row = $result->fetch_assoc()) {
    $recetas[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ruleta de recetas</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .ruleta-container {
            text-align: center;
            margin-top: 40px;
        }

        #ruleta {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 6px solid #8bc34a;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            transition: transform 3s ease-out;
            background: #f5f5f5;
        }

        .item {
            font-size: 12px;
            width: 80px;
            text-align: center;
        }

        /* 🔻 PUNTERO */
        .puntero {
            font-size: 30px;
            margin-bottom: -10px;
        }

        button {
            padding: 10px 20px;
            background: #8bc34a;
            border: none;
            color: white;
            border-radius: 10px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        #resultado {
            margin-top: 20px;
            font-size: 20px;
            color: #333;
        }

        .ver-receta {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #ff9800;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<?php include("header.php"); ?>

<div class="ruleta-container">
    <h2>🎡 Recetas de <?php echo $estacion; ?></h2>

    <?php if (count($recetas) == 0) { ?>
        <p>No hay recetas para esta estación 😢</p>
    <?php } else { ?>

        <div class="puntero">⬇️</div>

        <div id="ruleta">
            <?php foreach ($recetas as $r) { ?>
                <div class="item"><?php echo $r['titulo']; ?></div>
            <?php } ?>
        </div>

        <button onclick="girar()">Girar</button>

        <h3 id="resultado"></h3>

    <?php } ?>
</div>

<script>
let recetas = <?php echo json_encode($recetas); ?>;

function girar() {
    if (recetas.length === 0) return;

    let ruleta = document.getElementById("ruleta");
    let resultado = document.getElementById("resultado");

    // reset transición para evitar bugs
    ruleta.style.transition = "none";
    ruleta.style.transform = "rotate(0deg)";

    setTimeout(() => {
        ruleta.style.transition = "transform 3s ease-out";

        let random = Math.floor(Math.random() * recetas.length);
        let receta = recetas[random];

        // giro más realista
        let giro = 360 * 5 + (random * (360 / recetas.length));
        ruleta.style.transform = "rotate(" + giro + "deg)";

        setTimeout(() => {
            resultado.innerHTML = `
                🍽️ Te tocó: <strong>${receta.titulo}</strong><br>
                <a class="ver-receta" href="ver_receta.php?id=${receta.id}">
                    Ver receta
                </a>
            `;
        }, 3000);

    }, 50);
}
</script>

</body>
</html>