<?php
session_start();

if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

$admin_nome = $_SESSION["admin_nome"] ?? "Administrador";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Inicial</title>
    <link rel="stylesheet" href="style.css">

    <!-- Fonte Inter e FontAwesome já importados no CSS, mas FontAwesome aqui só se precisar -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo">Ceará Certificação</div>
    <div class="perfil-dropdown" onclick="toggleDropdown()">
        <i class="fas fa-user-circle"></i>
        <span><?php echo htmlspecialchars($admin_nome); ?></span>
        <div class="dropdown-menu">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>
</div>

<header class="painel-header">
    <h1>Bem-vindo, <?php echo htmlspecialchars($admin_nome); ?></h1>
    <p>Sistema Ceará Certificação</p>
</header>

<div class="painel-container">
    <h2>Painel Inicial</h2>

    <div class="painel-opcoes">
        <div class="painel-opcao">
            <a href="manual_adm.php"><i class="fas fa-tachometer-alt"></i> Painel do Administrador</a>
        </div>
        <div class="painel-opcao">
            <a href="cadastro.php"><i class="fas fa-user-plus"></i> Cadastrar Empregado</a>
        </div>
        <div class="painel-opcao">
            <a href="listar_empregados.php"><i class="fas fa-list"></i> Listar Empregados</a>
        </div>
        <div class="painel-opcao">
            <a href="painel_admin.php"><i class="fas fa-user-cog"></i> Cadastrar Novo Administrador</a>
        </div>
    </div>
</div>

<footer class="painel-footer">
    &copy; <?php echo date("Y"); ?> Ceará Certificação
</footer>

<script>
function toggleDropdown() {
    const menu = document.querySelector(".dropdown-menu");
    menu.classList.toggle("show");
}

document.addEventListener('click', function(e) {
    const dropdown = document.querySelector(".dropdown-menu");
    const profile = document.querySelector(".perfil-dropdown");
    if (!profile.contains(e.target)) {
        dropdown.classList.remove("show");
    }
});
</script>

</body>
</html>
