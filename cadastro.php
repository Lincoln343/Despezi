<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        echo "Por favor, preencha todos os campos corretamente.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT id_usuario FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Email já cadastrado. <a href='login.php'>Faça login</a>";
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO Usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senhaHash]);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>

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
    align-items: center;
    padding: 40px;
    color: #333;
}

.container {
    background: #ffffff;
    width: 400px;
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

label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
    transition: 0.2s;
}

input:focus {
    border-color: #4A90E2;
    box-shadow: 0px 0px 5px rgba(74,144,226,0.5);
    outline: none;
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
    margin-top: 5px;
}

button:hover {
    background: #357ABD;
}

.link-login {
    margin-top: 15px;
    text-align: center;
    color: #444;
}

.link-login a {
    color: #4A90E2;
    font-weight: bold;
    text-decoration: none;
}

.link-login a:hover {
    text-decoration: underline;
}


@media (max-width: 500px) {
    .container {
        width: 90%;
        padding: 25px;
    }
}

</style>

</head>
<body>

<div class="container">
    <h1>Cadastro</h1>

    <form method="post" action="">
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit">Cadastrar</button>
    </form>

    <p class="link-login">
        Já tem conta?
        <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>
