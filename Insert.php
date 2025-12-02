<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Fundo responsivo */
body {
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg, #4A90E2, #6A5ACD);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* Container adaptável */
.container {
    background: #ffffff;
    padding: 30px 40px;
    width: 100%;
    max-width: 420px;
    border-radius: 12px;
    box-shadow: 0px 0px 20px rgba(0,0,0,0.15);
    animation: fadeIn 0.6s ease;
}

/* Animação */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Títulos */
h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Botões principais */
button {
    padding: 12px;
    background: #4A90E2;
    border: none;
    color: white;
    width: 100%;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #357ABD;
}

button.success { background: #4CAF50; }
button.success:hover { background: #3c8c40; }

button.danger { background: #d9534f; }
button.danger:hover { background: #c64542; }

/* Formulários */
form {
    margin-bottom: 15px;
}

/* Inputs e selects */
input[type="text"],
input[type="email"],
input[type="password"],
input[type="number"],
input[type="date"],
select {
    width: 100%;
    padding: 12px;
    margin-bottom: 14px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
    transition: 0.2s;
}

/* Foco nos inputs */
input:focus, select:focus {
    border-color: #4A90E2;
    box-shadow: 0 0 5px rgba(74,144,226,0.4);
    outline: none;
}

/* Mensagens */
p {
    padding: 12px;
    background: #e9f5ff;
    border-left: 4px solid #4A90E2;
    border-radius: 5px;
    margin-bottom: 12px;
}

p.error {
    background:#ffeaea;
    border-left: 4px solid #d9534f;
    color: #a94442;
}

p.success {
    background:#e6ffea;
    border-left: 4px solid #4CAF50;
    color: #3c8c40;
}

/* Área dos botões superiores */
.top-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    margin-bottom: 25px;
    margin-top: 10px;
}

/* Responsividade geral */
@media (max-width: 600px) {
    .container {
        padding: 20px;
    }

    button {
        font-size: 15px;
        padding: 10px;
    }

    h1 {
        font-size: 22px;
    }
}

@media (max-width: 380px) {
    .top-buttons {
        grid-template-columns: 1fr;
    }
}

</style>

<div class="container">

    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></h1>

    <div class="top-buttons">
        <form action="adicionardespesas.php" method="get">
            <button type="submit" class="success">Adicionar Despesa</button>
        </form>

        <form action="adicionarrendas.php" method="get">
            <button type="submit" class="success">Adicionar Renda</button>
        </form>

        <form action="Informacoes.php" method="get">
            <button type="submit" class="success">Informações</button>
        </form>

        <form action="logout.php" method="post">
            <button type="submit" class="danger">Sair</button>
        </form>
    </div>

</div>

</body>
</html>
