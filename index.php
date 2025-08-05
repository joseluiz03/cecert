<?php
session_start();
include 'conexao.php';

// Redireciona se já estiver logado
if (isset($_SESSION["admin_logado"])) {
    header("Location: painel.php");
    exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($usuario && $senha) {
        $stmt = $mysqli->prepare("SELECT id, nome, senha FROM administradores WHERE usuario = ?");
        if ($stmt) {
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $nome, $senha_hash);
                $stmt->fetch();

                if (password_verify($senha, $senha_hash)) {
                    $_SESSION["admin_logado"] = true;
                    $_SESSION["admin_id"] = $id;
                    $_SESSION["admin_nome"] = $nome;

                    header("Location: painel.php");
                    exit;
                } else {
                    $erro = "Senha incorreta.";
                }
            } else {
                $erro = "Usuário não encontrado.";
            }
            $stmt->close();
        } else {
            $erro = "Erro ao preparar consulta.";
        }
    } else {
        $erro = "Preencha usuário e senha.";
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

        <?php if ($erro): ?>
            <p style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
