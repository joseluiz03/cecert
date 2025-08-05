<?php
include 'conexao.php';

$result = $mysqli->query("SELECT id, senha FROM administradores");

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $senha_texto = $row['senha'];

    if (strpos($senha_texto, '$2y$') === 0) continue;

    $senha_hash = password_hash($senha_texto, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("UPDATE administradores SET senha = ? WHERE id = ?");
    $stmt->bind_param('si', $senha_hash, $id);
    $stmt->execute();
    $stmt->close();
}

echo "Senhas atualizadas para hash!";
?>
