<?php
session_start();
require_once 'conexao.php';

$erro = '';
$email = '';
$senha = '';

if (isset($_SESSION['usuario_id'])) {
    header("Location: insert.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    if ($email === "admin@gmail.com" && $senha === "cefet123") {
        $_SESSION['usuario_id'] = 0;
        $_SESSION['usuario_nome'] = "Administrador";
        $_SESSION['usuario_email'] = $email;

        header("Location: admin.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];

        header("Location: insert.php");
        exit;
    } else {
        $erro = "Email ou senha incorretos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<style>
    /* ===== RESET ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ===== BODY ===== */
body {
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg, #4A90E2, #6A5ACD);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333;
}

/* ===== CARD ===== */
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

/* ===== TITULO ===== */
h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #444;
}

/* ===== FORM ===== */
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

/* ===== BOTÃO ===== */
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

/* ===== LINKS ===== */
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

/* ===== ERRO ===== */
.error {
    background: #ffdddd;
    border-left: 5px solid #d9534f;
    color: #a94442;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    text-align: center;
}

</style>
<div class="login-container">

    <h1>Login</h1>

    <?php if (!empty($erro)): ?>
        <p class="error"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>"><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br>

        <button type="submit">Entrar</button>
    </form>

    <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>

</div>

</body>
</html>

