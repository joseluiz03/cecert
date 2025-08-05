<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="style.css"> <!-- Ajuste conforme seu arquivo -->
    <style>
        .container {
            max-width: 800px;
            margin: 40px auto;
            text-align: center;
        }

        .admin-opcoes {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .admin-opcoes a {
            display: block;
            padding: 16px 24px;
            background-color: #0052cc;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .admin-opcoes a:hover {
            background-color: #003d99;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Painel do Administrador</h2>
        <p>Selecione uma ação relacionada aos pagamentos:</p>

        <div class="admin-opcoes">
            <a href="pagamentos.php">Registrar Pagamento</a>
            <a href="relatorio_pagamentos.php">Relatório de Pagamentos</a>
        </div>
    </div>
</body>
</html>
