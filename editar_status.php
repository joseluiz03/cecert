<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php';

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    $busca = $mysqli->query("SELECT status FROM empregados WHERE id = $id");
    if ($busca && $busca->num_rows > 0) {
        $empregado = $busca->fetch_assoc();
        $novoStatus = ($empregado["status"] === "ativo") ? "inativo" : "ativo";

        $atualiza = $mysqli->query("UPDATE empregados SET status = '$novoStatus' WHERE id = $id");
    }
}

header("Location: listar_empregados.php");
exit;
