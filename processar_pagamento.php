<?php
// Mostrar erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php'; // Usa $mysqli

// Verificar se os campos foram enviados corretamente
if (!isset($_POST["mes"], $_POST["ano"], $_POST["valor"])) {
    die("Erro: Dados incompletos.");
}

$mes = intval($_POST["mes"]);
$ano = intval($_POST["ano"]);
$valores = $_POST["valor"];

$pagamentos_feitos = 0;

foreach ($valores as $empregado_id => $valor) {
    $valor = floatval($valor);
    if ($valor > 0) {
        $stmt = $mysqli->prepare("INSERT INTO pagamentos (empregado_id, mes, ano, valor) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Erro ao preparar a query: " . $mysqli->error);
        }
        $stmt->bind_param("iiid", $empregado_id, $mes, $ano, $valor);
        if (!$stmt->execute()) {
            die("Erro ao registrar pagamento: " . $stmt->error);
        }
        $stmt->close();  // Fecha o statement para liberar recursos
        $pagamentos_feitos++;
    }
}

if ($pagamentos_feitos === 0) {
    echo "<script>alert('Nenhum pagamento foi registrado. Preencha pelo menos um valor.'); window.history.back();</script>";
    exit;
}

// ✅ Redireciona para o painel correto
header("Location: manual_adm.php?sucesso=1");
exit;
