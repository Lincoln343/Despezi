<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$msg = '';

$stmt = $pdo->prepare("SELECT * FROM Rendas WHERE id_usuario = ?");
$stmt->execute([$usuario_id]);
$renda = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $valor_renda = $_POST['valor_renda'];
    $saldo = $_POST['saldo'];
    $data_renda = $_POST['data_renda'];

    if (empty($data_renda)) {
        $data_renda = date('Y-m-d');
    }

    if (is_numeric($valor_renda) && $valor_renda > 0 && is_numeric($saldo)) {

        if ($renda) {
            $stmt = $pdo->prepare("
                UPDATE Rendas 
                SET valor_renda = ?, saldo = ?, data_renda = ?
                WHERE id_usuario = ?
            ");
            $stmt->execute([$valor_renda, $saldo, $data_renda, $usuario_id]);
            $msg = "Renda atualizada com sucesso.";

        } else {
            $stmt = $pdo->prepare("
                INSERT INTO Rendas (id_usuario, valor_renda, saldo, data_renda)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$usuario_id, $valor_renda, $saldo, $data_renda]);
            $msg = "Renda adicionada com sucesso.";
        }

    } else {
        $msg = "Digite valores válidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Renda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg, #4A90E2, #6A5ACD);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333;
}

.login-container {
    background: #ffffff;
    padding: 30px 40px;
    width: 350px;
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
    margin-bottom: 20px;
    color: #444;
}

label {
    font-weight: bold;
    color: #555;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
    transition: 0.2s;
}

input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #4A90E2;
    outline: none;
    box-shadow: 0px 0px 5px rgba(74,144,226,0.5);
}

button {
    width: 100%;
    background: #4A90E2;
    padding: 12px;
    border: none;
    color: white;
    border-radius: 7px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.25s;
}

button:hover {
    background: #357ABD;
}

p {
    margin-top: 15px;
    text-align: center;
    color: #444;
}

a {
    color: #4A90E2;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.error {
    background: #ffdddd;
    border-left: 5px solid #d9534f;
    color: #a94442;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    text-align: center;
}

.container {
    max-width: 750px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #444;
    margin-bottom: 25px;
}

h2 {
    margin-top: 30px;
    color: #4a4a4a;
    border-left: 4px solid #4A90E2;
    padding-left: 8px;
}

button {
    padding: 10px 18px;
    background: #4A90E2;
    border: none;
    color: white;
    font-size: 15px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.25s;
}

button:hover {
    background: #357ABD;
}

button.success {
    background: #4CAF50;
}
button.success:hover {
    background: #3c8c40;
}

button.danger {
    background: #d9534f;
}
button.danger:hover {
    background: #c64542;
}

form {
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
}

input[type="text"],
input[type="number"],
input[type="date"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    transition: 0.2s;
}

input:focus,
select:focus {
    border-color: #4A90E2;
    box-shadow: 0 0 4px rgba(74,144,226,0.4);
    outline: none;
}

p {
    background: #e9f5ff;
    border-left: 4px solid #4A90E2;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
}

p.error {
    background: #ffeaea;
    border-left: 4px solid #d9534f;
    color: #a94442;
}

p.success {
    background: #e6ffea;
    border-left: 4px solid #4CAF50;
    color: #3c8c40;
}

.top-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
}

@media (max-width: 600px) {
    .container {
        padding: 20px;
    }

    button {
        width: 100%;
        margin-bottom: 10px;
    }

    .top-buttons {
        flex-direction: column;
    }
}
</style>
<div class="login-container">

<h1>Adicionar / Editar Renda</h1>

<?php if ($msg): ?>
<p class="success"><?php echo htmlspecialchars($msg); ?></p>
<?php endif; ?>

<form method="post">

    <label>Valor da renda:</label>
    <input type="number" step="0.01" name="valor_renda"
           value="<?= $renda ? $renda['valor_renda'] : '' ?>" required>

    <label>Saldo atual:</label>
    <input type="number" step="0.01" name="saldo"
           value="<?= $renda ? $renda['saldo'] : '' ?>" required>

    <label>Data para crédito automático:</label>
    <input type="date" name="data_renda"
           value="<?= $renda ? $renda['data_renda'] : '' ?>">

    <button type="submit" class="success">Salvar Renda</button>
</form>

<form action="Insert.php">
    <button type="submit">Voltar</button>
</form>

</div>

</body>
</html>
