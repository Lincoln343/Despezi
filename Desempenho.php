<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
require "conexao.php";

$sqlCat = $pdo->query("SELECT id_categoria, nome_categoria FROM categorias");
$categorias = $sqlCat->fetchAll(PDO::FETCH_ASSOC);

$sqlTotal = $pdo->prepare("SELECT SUM(valor) AS total FROM despesas WHERE id_usuario = ?");
$sqlTotal->execute([$id_usuario]);
$totalGasto = $sqlTotal->fetchColumn();
if ($totalGasto == 0) $totalGasto = 1;

$sqlCatGastos = $pdo->prepare("
    SELECT id_categoria, SUM(valor) AS total 
    FROM despesas 
    WHERE id_usuario = ?
    GROUP BY id_categoria
");
$sqlCatGastos->execute([$id_usuario]);
$gastosPorCategoria = $sqlCatGastos->fetchAll(PDO::FETCH_KEY_PAIR);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Desempenho do Usuário</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg, #4A90E2, #6A5ACD);
    padding: 40px;
    color: #333;
    min-height: 100vh;
}

.container {
    max-width: 950px;
    margin: auto;
    background: #ffffff;
    padding: 35px 40px;
    border-radius: 12px;
    box-shadow: 0px 0px 20px rgba(0,0,0,0.15);
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

h1 {
    text-align: center;
    color: #444;
    margin-bottom: 25px;
}


.category-block {
    margin-bottom: 22px;
}

.category-name {
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 6px;
    color: #333;
}

.progress-bar {
    width: 100%;
    height: 25px;
    background: #e6e6e6;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: inset 0px 0px 5px rgba(0,0,0,0.2);
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4A90E2, #6A5ACD);
    width: 0;
    color: white;
    font-size: 14px;
    text-align: center;
    line-height: 25px;
    border-radius: 12px;
    transition: width 0.8s ease;
}

.buttons {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

a.button {
    padding: 12px 20px;
    background: #4A90E2;
    color: white;
    text-decoration: none;
    border-radius: 7px;
    font-weight: bold;
    text-align: center;
    transition: 0.25s;
    font-size: 15px;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.15);
}

a.button:hover {
    background: #357ABD;
}

a.button.logout {
    background: #d9534f;
}

a.button.logout:hover {
    background: #c64542;
}

</style>
</head>
<body>

<div class="container">
    <h1>Percentual de gastos</h1>

<?php foreach ($categorias as $cat): 
    $catId = $cat['id_categoria'];
    $catNome = $cat['nome_categoria'];

    $valorCat = $gastosPorCategoria[$catId] ?? 0;
    $percentual = round(($valorCat / $totalGasto) * 100, 2);
?>

    <div class="category-block">
        <div class="category-name"><?= htmlspecialchars($catNome) ?> — <?= $percentual ?>%</div>

        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $percentual ?>%;">
                <?= $percentual ?>%
            </div>
        </div>
    </div>

<?php endforeach; ?>
<?php
$labels = [];
$values = [];
$colors = [];

foreach ($categorias as $cat) {
    $labels[] = $cat['nome_categoria'];
    $values[] = $gastosPorCategoria[$cat['id_categoria']] ?? 0;

    $colors[] = '#' . substr(md5($cat['nome_categoria']), 0, 6);
}
?>
    <h2 style="margin-top:30px; color:#444;">Distribuição dos Gastos</h2>

    <canvas id="graficoPizza" style="margin-top:20px; max-width:450px; margin:auto; display:block;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('graficoPizza');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($values); ?>,
            backgroundColor: <?php echo json_encode($colors); ?>,
            borderColor: "#fff",
            borderWidth: 2,
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 14 } }
            }
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});
</script>


    <div class="buttons">
        <a href="Insert.php" class="button">Voltar</a>
        <a href="logout.php" class="button logout">Sair</a>
    </div>

</div>

</body>
</html>
