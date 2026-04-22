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
            transition: transform 2s ease-out;
            background: #f5f5f5;
        }

        .item {
            font-size: 12px;
            width: 80px;
            text-align: center;
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
    </style>
</head>

<body>

<?php include("header.php"); ?>

<div class="ruleta-container">
    <h2>🎡 Recetas de <?php echo $estacion; ?></h2>

    <?php if (count($recetas) == 0) { ?>
        <p>No hay recetas para esta estación 😢</p>
    <?php } else { ?>

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

    let random = Math.floor(Math.random() * recetas.length);
    let receta = recetas[random];

    let ruleta = document.getElementById("ruleta");

    // 🎡 giro visual
    let giro = 360 * 5 + (random * 30);
    ruleta.style.transform = "rotate(" + giro + "deg)";

    // 🕐 mostrar resultado después del giro
    setTimeout(() => {
        document.getElementById("resultado").innerHTML =
            "🍽️ Te tocó: <strong>" + receta.titulo + "</strong>";
    }, 2000);
}
</script>

</body>
</html>