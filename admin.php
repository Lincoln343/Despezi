<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$msg_categoria = '';
$msg_despesa = '';

if (isset($_POST['acao']) && $_POST['acao'] === 'add_categoria') {
    $nome_categoria = trim($_POST['nome_categoria']);
    if (!empty($nome_categoria)) {
        $stmt = $pdo->prepare("SELECT id_categoria FROM Categorias WHERE nome_categoria = ?");
        $stmt->execute([$nome_categoria]);
        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO Categorias (nome_categoria) VALUES (?)");
            $stmt->execute([$nome_categoria]);
            $msg_categoria = "Categoria '$nome_categoria' adicionada com sucesso.";
        } else {
            $msg_categoria = "Categoria já existe.";
        }
    } else {
        $msg_categoria = "O nome da categoria não pode estar vazio.";
    }
}


if (isset($_POST['acao']) && $_POST['acao'] === 'add_despesa') {
    $descricao = trim($_POST['descricao']);
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $categoria_id = $_POST['categoria_id'];
    
    if ($descricao && is_numeric($valor) && $valor > 0 && $data && $categoria_id) {
$usuario_destino = trim($_POST['usuario_destino']);

if ($usuario_destino === "" || !is_numeric($usuario_destino)) {
    $usuario_destino = $usuario_id; 
}

$stmt = $pdo->prepare("INSERT INTO Despesas (descricao, valor, data, id_categoria, id_usuario) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$descricao, $valor, $data, $categoria_id, $usuario_destino]);

        $msg_despesa = "Despesa adicionada com sucesso.";
    } else {
        $msg_despesa = "Preencha todos os campos corretamente para adicionar a despesa.";
    }
}

if (isset($_POST['acao']) && $_POST['acao'] === 'del_categoria') {
    $id_categoria = trim($_POST['id_categoria']);

    if (!empty($id_categoria) && is_numeric($id_categoria)) {

        $stmt = $pdo->prepare("SELECT nome_categoria FROM Categorias WHERE id_categoria = ?");
        $stmt->execute([$id_categoria]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($categoria) {

            $stmt = $pdo->prepare("DELETE FROM Categorias WHERE id_categoria = ?");
            $stmt->execute([$id_categoria]);

            $msg_categoria = "Categoria '" . htmlspecialchars($categoria['nome_categoria']) . "' removida com sucesso.";

        } else {
            $msg_categoria = "Categoria não encontrada.";
        }

    } else {
        $msg_categoria = "Digite um ID válido.";
    }
}


if (isset($_POST['acao']) && $_POST['acao'] === 'del_despesa') {
    $id_despesa = trim($_POST['id_despesa']);

    if (!empty($id_despesa) && is_numeric($id_despesa)) {

        $stmt = $pdo->prepare("SELECT descricao FROM Despesas WHERE id_despesa = ?");
$stmt->execute([$id_despesa]);
$despesa = $stmt->fetch(PDO::FETCH_ASSOC);

if ($despesa) {
    $stmt = $pdo->prepare("DELETE FROM Despesas WHERE id_despesa = ?");
    $stmt->execute([$id_despesa]);
    $msg_despesa = "Despesa '" . htmlspecialchars($despesa['descricao']) . "' removida.";
}
    } else {
        $msg_despesa = "Digite um ID válido.";
    }
}


$stmt = $pdo->prepare("SELECT * FROM Categorias ORDER BY nome_categoria");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Inserir Categorias e Despesas</title>
    <link rel="stylesheet" href="style.css">
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
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 15px;
    color: #333;
}

.container {
    background: #ffffff;
    width: 100%;
    max-width: 850px;
    padding: 35px 40px;
    border-radius: 12px;
    box-shadow: 0px 0px 20px rgba(0,0,0,0.15);
    animation: fadeIn 0.6s ease;
    margin-top: 20px;
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
    margin-top: 35px;
    margin-bottom: 15px;
    color: #444;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #555;
}

input[type="text"],
input[type="number"],
input[type="date"],
select {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
    transition: 0.2s;
}

input:focus,
select:focus {
    border-color: #4A90E2;
    box-shadow: 0px 0px 5px rgba(74,144,226,0.5);
    outline: none;
}

button {
    background: #4A90E2;
    padding: 12px 18px;
    border: none;
    color: white;
    border-radius: 7px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.25s;
}

button:hover {
    background: #357ABD;
}

button.danger {
    background: #e53935;
}

button.danger:hover {
    background: #c62828;
}

.success {
    color: green;
    font-weight: bold;
    margin-bottom: 10px;
}

.error {
    color: #c62828;
    font-weight: bold;
    margin-bottom: 10px;
}

hr {
    margin: 25px 0;
    border: none;
    border-top: 1px solid #ddd;
}

.nav-buttons {
    margin-top: 30px;
    text-align: center;
}

.nav-buttons form {
    display: inline-block;
    margin: 5px 10px;
}

@media (max-width: 600px) {
    .container {
        padding: 25px;
    }

    button {
        width: 100%;
        margin-bottom: 10px;
    }

    .nav-buttons form {
        display: block;
        width: 100%;
    }
}
.categoria-flex {
    display: flex;
    gap: 20px;
    align-items: flex-end;
    margin-bottom: 20px;
}

.categoria-flex form {
    flex: 1;
}

.categoria-flex input {
    width: 100%;
}

.categoria-flex button {
    margin-top: 5px;
    width: 100%;
}
input[name="usuario_destino"] {
    border: 1px solid #bbb;
}


</style>
<div class="container">

    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></h1>

    <h2>Adicionar Categoria</h2>
    <?php if ($msg_categoria): ?>
        <p class="success"><?php echo htmlspecialchars($msg_categoria); ?></p>
    <?php endif; ?>

<div class="categoria-flex">
    
    <form method="post">
        <input type="hidden" name="acao" value="add_categoria">
        <label>Nome da Categoria:</label>
        <input type="text" name="nome_categoria" required>
        <button type="submit">Adicionar Categoria</button>
    </form>

    <form method="post">
        <input type="hidden" name="acao" value="del_categoria">
        <label>ID da Categoria:</label>
        <input type="number" name="id_categoria" required>
        <button class="danger" type="submit"onclick="return confirm('Tem certeza que deseja remover esta categoria?');">
            Remover Categoria
        </button>
    </form>

</div>


    <hr>

    <h2>Adicionar Despesa</h2>
    <?php if ($msg_despesa): ?>
        <p class="success"><?php echo htmlspecialchars($msg_despesa); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>ID do Usuário (opcional):</label>
        <input type="number" name="usuario_destino" placeholder="Se vazio, será do admin">

        <input type="hidden" name="acao" value="add_despesa">

        <label>Descrição:</label>
        <input type="text" name="descricao" required>

        <label>Valor:</label>
        <input type="number" step="0.01" name="valor" required>

        <label>Data:</label>
        <input type="date" name="data" required>

        <label>Categoria:</label>
        <select name="categoria_id" required>
            <option value="">Selecione</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id_categoria']; ?>">
                    <?php echo htmlspecialchars($categoria['nome_categoria']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit">Adicionar Despesa</button>
    </form>

    <hr>

    <h2>Remover Despesa</h2><br>
    
    <form method="post">
        <input type="hidden" name="acao" value="del_despesa">
    
        <label>ID da Despesa:</label>
        <input type="number" name="id_despesa" required>
    
        <br><br>
        <button class="danger" type="submit"
            onclick="return confirm('Tem certeza que deseja remover esta despesa?');">
            Remover Despesa
        </button>
    </form>

    <div class="nav-buttons">

        <form action="Tabelas.php" method="get">
            <button type="submit">Informações</button>
        </form>

        <form action="logout.php" method="post">
            <button class="danger" type="submit">Sair</button>
        </form>

    </div>

</div>
</body>
</html>

