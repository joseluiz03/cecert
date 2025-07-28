<?php
session_start();
include 'conexao.php';

// Se já estiver logado, redireciona direto pro painel (evita redirecionamento infinito)
if (isset($_SESSION["admin_logado"])) {
    header("Location: painel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $mysqli->real_escape_string($_POST["usuario"]);
    $senha = $mysqli->real_escape_string($_POST["senha"]);

    $sql = "SELECT * FROM administradores WHERE usuario = '$usuario' AND senha = '$senha'";
    $resultado = $mysqli->query($sql);

    if ($resultado && $resultado->num_rows === 1) {
        $_SESSION["admin_logado"] = true;
        header("Location: painel.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login do Administrador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <img src="./imagens/logo ceara certificação.png" alt="Logo Ceará Certificação" class="logo-img">
        <h2>Login do Administrador</h2>
        <?php if (isset($erro)) echo "<div class='msg-erro'>$erro</div>"; ?>
        <form method="post">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
