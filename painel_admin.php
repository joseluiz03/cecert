<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $mysqli->real_escape_string($_POST["nome"]);
    $cpf = $mysqli->real_escape_string($_POST["cpf"]);
    $data_nasc = $mysqli->real_escape_string($_POST["data_nasc"]);
    $usuario = $mysqli->real_escape_string($_POST["usuario"]);
    $senha = $mysqli->real_escape_string($_POST["senha"]);

    $sql = "INSERT INTO administradores (nome, cpf, data_nasc, usuario, senha) 
            VALUES ('$nome', '$cpf', '$data_nasc', '$usuario', '$senha')";

    if ($mysqli->query($sql)) {
        $sucesso = "Administrador cadastrado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar administrador: " . $mysqli->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <img src="./imagens/logo ceara certificação.png" alt="Logo Ceará Certificação" class="logo-img">
        <h2>Cadastrar Novo Administrador</h2>
        <?php 
        if (isset($sucesso)) echo "<div class='msg-sucesso'>$sucesso</div>";
        if (isset($erro)) echo "<div class='msg-erro'>$erro</div>";
        ?>
        <form method="post">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="text" name="cpf" placeholder="CPF" required maxlength="14" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
            <input type="date" name="data_nasc" required>
            <input type="text" name="usuario" placeholder="Usuário de login" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar Administrador</button>
        </form>

        <a href="logout.php" style="
            display:inline-block;
            margin-top:20px;
            padding:8px 16px;
            background-color:#dc3545;
            color:white;
            border-radius:4px;
            text-decoration:none;
        ">Sair</a>
    </div>
</body>
</html>
