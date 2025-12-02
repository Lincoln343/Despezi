<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Inserir Categorias e Despesas</title>

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
    max-width: 500px;
    margin: auto;
    background: white;
    padding: 35px 40px;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    text-align: center;
    animation: fadeIn 0.7s ease;
    transition: transform 0.25s, box-shadow 0.25s;
}

.container:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 26px rgba(0,0,0,0.18);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

h1 {
    color: #444;
    margin-bottom: 25px;
    font-size: 26px;
    letter-spacing: 0.5px;
}

button {
    padding: 12px 22px;
    background: #4A90E2;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 7px;
    cursor: pointer;
    transition: 0.25s;
    min-width: 140px;
    font-weight: bold;
    box-shadow: 0px 3px 10px rgba(0,0,0,0.15);
}

button:hover {
    background: #357ABD;
    box-shadow: 0px 5px 14px rgba(0,0,0,0.25);
    transform: translateY(-2px);
}

.buttons {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 25px;
}

@media (max-width: 600px) {
    .container {
        width: 90%;
        padding: 25px;
    }

    .buttons {
        flex-direction: column;
    }

    button {
        width: 100%;
    }
}

</style>
</head>
<body>

<div class="container">
    <h1>Bem-vindo ao despezi!</h1>

    <div class="buttons">
        <form action="cadastro.php" method="get">
            <button type="submit">Cadastro</button>
        </form>

        <form action="login.php" method="post">
            <button type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>
