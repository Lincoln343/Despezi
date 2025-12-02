<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
if (!$pdo) {
    die(" Erro na conexão com o banco de dados.");
}

try {
    $stmt = $pdo->query("SELECT id_usuario, nome, email FROM Usuarios ORDER BY id_usuario ASC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}

try {
    $stmt = $pdo->query("SELECT id_categoria, nome_categoria FROM Categorias ORDER BY id_categoria ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}

try {

    if (isset($_GET['usuario_despesa']) && $_GET['usuario_despesa'] !== "") {

        $idFiltrar = $_GET['usuario_despesa'];

        $stmt = $pdo->prepare("
            SELECT d.id_despesa, d.descricao, d.valor, d.data, 
                   c.nome_categoria, u.nome AS nome_usuario
            FROM Despesas d
            LEFT JOIN Categorias c ON d.id_categoria = c.id_categoria
            LEFT JOIN Usuarios u ON d.id_usuario = u.id_usuario
            WHERE d.id_usuario = ?
            ORDER BY d.id_despesa ASC
        ");
        $stmt->execute([$idFiltrar]);

    } else {

        $stmt = $pdo->query("
            SELECT d.id_despesa, d.descricao, d.valor, d.data, 
                   c.nome_categoria, u.nome AS nome_usuario
            FROM Despesas d
            LEFT JOIN Categorias c ON d.id_categoria = c.id_categoria
            LEFT JOIN Usuarios u ON d.id_usuario = u.id_usuario
            ORDER BY d.id_despesa ASC
        ");
    }

    $despesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar despesas: " . $e->getMessage());
}


try {
    $stmt = $pdo->query("
        SELECT r.id_renda, r.valor_renda, r.saldo, r.data_renda, u.nome AS nome_usuario
        FROM Rendas r
        LEFT JOIN Usuarios u ON r.id_usuario = u.id_usuario
        ORDER BY r.id_renda ASC
    ");
    $rendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar rendas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tabelas do Sistema</title>
    <style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg, #4A90E2, #6A5ACD);
    min-height: 100vh;
    padding: 40px 15px;
    color: #333;
}

.container {
    background: #ffffff;
    max-width: 1000px;
    margin: auto;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0px 0px 20px rgba(0,0,0,0.18);
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity:0; transform: translateY(10px); }
    to   { opacity:1; transform: translateY(0); }
}

h1, h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #444;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    margin-bottom: 30px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
    font-size: 15px;
}

th {
    background: #4A90E2;
    color: white;
}

tr:last-child td {
    border-bottom: none;
}

tr:nth-child(even) {
    background:#f7f9ff;
}

button, a {
    display: inline-block;
    background: #4A90E2;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: 0.25s;
    font-size: 15px;
}

button:hover, a:hover {
    background: #357ABD;
}

.btn-filtrar {
    background: #28a745;
}

.btn-filtrar:hover {
    background: #1f7a33;
}

.menu {
    text-align: center;
    margin-bottom: 20px;
}

.filtro-container {
    text-align: center;
    margin: 25px auto;
}

.combo {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 220px;
    font-size: 15px;
    transition: .25s;
}

.combo:focus {
    border-color: #4A90E2;
    box-shadow: 0 0 8px rgba(74,144,226,0.4);
    outline: none;
}

@media (max-width: 700px) {

    table {
        font-size: 13px;
    }

    th, td {
        padding: 8px;
    }

    .combo {
        width: 100%;
        margin-bottom: 10px;
    }

    button, a {
        width: 100%;
        margin-top: 10px;
    }

}

    </style>
</head>
<body>
    <div class="container">
    <h1>Tabelas do Sistema</h1>

    <div class="menu">
        <a href="admin.php">Voltar</a>
    </div>

    <h2>Usuários</h2>
    <?php if (count($usuarios) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['id_usuario']); ?></td>
            <td><?= htmlspecialchars($u['nome']); ?></td>
            <td><?= htmlspecialchars($u['email']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php
$stmt1 = $pdo->prepare("SELECT id_usuario, nome FROM Usuarios ORDER BY nome");
$stmt1->execute();
$usuariosFiltro = $stmt1->fetchAll(PDO::FETCH_ASSOC);
?>




    <?php else: ?>
        <p style="text-align:center;">Nenhum usuário encontrado.</p>
    <?php endif; ?>
    
    <h2>Categorias</h2>
    <?php if (count($categorias) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome da Categoria</th>
            </tr>
            <?php foreach ($categorias as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['id_categoria']); ?></td>
                    <td><?= htmlspecialchars($c['nome_categoria']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p style="text-align:center;">Nenhuma categoria encontrada.</p>
                <?php endif; ?>
                
                <div class="filtro-container">
                    <form method="GET" action="">
                        <select name="usuario_despesa" class="combo" required>
                            <option value="">Selecione um usuário</option>
                            <?php foreach ($usuariosFiltro as $u): ?>
                                <option value="<?= $u['id_usuario']; ?>"
                                    <?= (isset($_GET['usuario_despesa']) && $_GET['usuario_despesa'] == $u['id_usuario']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($u['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                
                        <button type="submit" class="btn-filtrar">Atualizar Tabela</button>
                    </form>
                </div>
                
                <h2>Despesas</h2>
    <?php if (count($despesas) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Usuário</th>
        </tr>
        <?php foreach ($despesas as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['id_despesa']); ?></td>
            <td><?= htmlspecialchars($d['descricao']); ?></td>
            <td>R$ <?= number_format($d['valor'], 2, ',', '.'); ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($d['data']))); ?></td>
            <td><?= htmlspecialchars($d['nome_categoria']); ?></td>
            <td><?= htmlspecialchars($d['nome_usuario']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="text-align:center;">Nenhuma despesa encontrada.</p>
    <?php endif; ?>
    
    <h2>Rendas</h2>
    <?php if (count($rendas) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Valor da Renda</th>
            <th>Saldo</th>
            <th>Data</th>
            <th>Usuário</th>
        </tr>
        <?php foreach ($rendas as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['id_renda']); ?></td>
            <td>R$ <?= number_format($r['valor_renda'], 2, ',', '.'); ?></td>
            <td>R$ <?= number_format($r['saldo'], 2, ',', '.'); ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['data_renda']))); ?></td>
            <td><?= htmlspecialchars($r['nome_usuario']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="text-align:center;">Nenhuma renda encontrada.</p>
    <?php endif; ?>
    </div>
</body>
</html>
