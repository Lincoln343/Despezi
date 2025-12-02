<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("
    SELECT descricao, valor, data, nome_categoria 
    FROM vw_informacoes_usuario 
    WHERE id_usuario = ? 
    ORDER BY data DESC
");
$stmt->execute([$usuario_id]);

$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$registros) {
    $registros = [];
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Informações do Usuário</title>
    <link rel="stylesheet" href="style-informacoes.css">
</head>
<body>
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

h2 {
    margin-top: 30px;
    margin-bottom: 15px;
    color: #4a4a4a;
    border-left: 4px solid #4A90E2;
    padding-left: 10px;
}

.user-info p {
    font-size: 16px;
    margin-bottom: 8px;
    background: #e9f5ff;
    border-left: 4px solid #4A90E2;
    padding: 8px;
    border-radius: 4px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    border-radius: 8px;
    overflow: hidden;
}

th {
    background: #4A90E2;
    color: white;
    padding: 12px;
    font-size: 15px;
}

td {
    text-align: center;
    padding: 12px;
    background: #fafafa;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

tr:nth-child(even) td {
    background: #f0f4fa;
}

tr:hover td {
    background: #e7f0ff;
}

p.no-data {
    background: #ffeaea;
    border-left: 4px solid #d9534f;
    padding: 10px;
    color: #a94442;
    border-radius: 4px;
    margin: 20px 0;
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


@media (max-width: 700px) {
    .container {
        padding: 25px;
    }

    table, tr, td, th {
        font-size: 13px;
        padding: 9px;
    }

    .buttons {
        flex-direction: column;
    }

    a.button {
        width: 100%;
    }
}

@media (max-width: 450px) {
    h1 {
        font-size: 22px;
    }
}

</style>
<div class="container">

    <h1>Informações do Usuário</h1>

    <div class="user-info">
        <p><strong>ID:</strong> <?php echo htmlspecialchars($_SESSION['usuario_id']); ?></p>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></p>
        <p><strong>Data e hora atual:</strong> <?php echo date('d/m/Y H:i'); ?></p>
    </div>
    <br>

    <?php if (!empty($registros) && $registros[0]['descricao'] !== null): ?>
    
    <h2>Despesas e Categorias</h2>

    <table>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor (R$)</th>
                <th>Data</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $linha): ?>
                <tr>
                    <td><?php echo htmlspecialchars($linha['descricao']); ?></td>
                    <td><?php echo number_format($linha['valor'], 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($linha['data'])); ?></td>
                    <td><?php echo htmlspecialchars($linha['nome_categoria']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
    
        <p class="no-data">Este usuário ainda não possui despesas cadastradas.</p>
    
    <?php endif; ?>

        <br><hr><br>
        
        <?php
$usuario_id1 = $_SESSION['usuario_id'];

$stmt1 = $pdo->prepare("
SELECT id_renda, valor_renda, data_renda, saldo
    FROM vw_informacoes_usuario_renda
    WHERE id_usuario = ?
    ORDER BY data_renda DESC
");
$stmt1->execute([$usuario_id1]);

$registros1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
if (!$registros1) {
    $registros1 = [];
}
?>
<?php if (!empty($registros1) && $registros1[0]['id_renda'] !== null): ?>
    <h2>Rendas</h2>

    <table>
        <thead>
            <tr>
                <th>ID da renda</th>
                <th>Valor (R$)</th>
                <th>Data</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros1 as $linha): ?>
                <tr>
                    <td><?php echo number_format($linha['id_renda']); ?></td>
                    <td><?php echo number_format($linha['valor_renda'], 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($linha['data_renda'])); ?></td>
                    <td><?php echo number_format($linha['saldo']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    
        <p class="no-data">Este usuário ainda não possui renda cadastrada.</p>
    
    <?php endif; ?>

    <div class="buttons">
        <a href="Desempenho.php" class="button">Desempenho</a>
        <a href="Insert.php" class="button">Voltar</a>
        <a href="logout.php" class="button logout">Sair</a>
    </div>

</div>

</body>
</html>

