<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <img src="./imagens/logo ceara certificação.png" alt="Logo Ceará Certificação" class="logo-img">

        <form action="cadastro.php" method="post">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="date" name="data_nasc" required>
            <input type="text" name="cpf" placeholder="CPF" required maxlength="14" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Digite o CPF no formato 000.000.000-00">
            <button type="submit">Cadastrar</button>
        </form>

        <!-- Botão de logout -->
        <a href="logout.php" style="
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        ">Sair</a>

        <?php
        // Mensagem de erro de conexão
        if (isset($mysqli) && $mysqli->connect_errno) {
            echo "<div class='msg-erro' id='msgErro'><span class='icone-erro'>&#10006;</span> Erro ao conectar ao banco de dados: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "</div>";
        }

        // Processa o cadastro quando o formulário é enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($mysqli) && !$mysqli->connect_errno) {
            if (!isset($_SESSION["admin_id"])) {
                echo "<div class='msg-erro'>Erro: ID do administrador não está definido na sessão.</div>";
            } else {
                $id_admin = $_SESSION["admin_id"];

                $nome = $mysqli->real_escape_string($_POST["nome"]);
                $data_nasc = $mysqli->real_escape_string($_POST["data_nasc"]);
                $cpf = $mysqli->real_escape_string($_POST["cpf"]);

                $sql = "INSERT INTO empregados (nome, data_nasc, cpf, id_admin) 
                        VALUES ('$nome', '$data_nasc', '$cpf', '$id_admin')";

                if ($mysqli->query($sql)) {
                    echo "<div class='msg-sucesso' id='msgSucesso'><span class='icone-sucesso'>&#10004;</span> Empregado cadastrado com sucesso!</div>";
                } else {
                    echo "<div class='msg-erro' id='msgErro'><span class='icone-erro'>&#10006;</span> Erro ao cadastrar: " . $mysqli->error . "</div>";
                }
            }
        }
        ?>
    </div>
    <script>
        setTimeout(function() {
            var msgSucesso = document.getElementById('msgSucesso');
            if(msgSucesso) msgSucesso.style.display = 'none';
            var msgErro = document.getElementById('msgErro');
            if(msgErro) msgErro.style.display = 'none';
        }, 6000);
    </script>
</body>
</html>
