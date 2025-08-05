<?php
// Mostrar erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php'; // assume $mysqli

if (!isset($mysqli)) {
    die("Erro: conexão com o banco não foi estabelecida corretamente.");
}

$admin = $_SESSION['admin_nome'] ?? 'Administrador';
$dataHoraAtual = date('d/m/Y H:i:s');

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

$logoPathOriginal = __DIR__ . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'logo_ceara_certificacao.png';
if (!file_exists($logoPathOriginal)) {
    die("Erro: Imagem da logo não encontrada em: $logoPathOriginal");
}
$logoPath = 'file://' . realpath($logoPathOriginal);

$stmt = $mysqli->prepare("
    SELECT e.nome, e.cpf, e.status, p.valor, p.data_pagamento
    FROM pagamentos p
    JOIN empregados e ON p.empregado_id = e.id
    WHERE p.mes = ? AND p.ano = ?
    ORDER BY e.nome
");
if (!$stmt) {
    die("Erro ao preparar consulta: " . $mysqli->error);
}
$stmt->bind_param("ii", $mes, $ano);
$stmt->execute();
$resultado = $stmt->get_result();

$total = 0;

$html = '
<style>
    @page { margin: 80px 50px 80px 50px; }
    body { font-family: Arial, sans-serif; font-size: 12px; }
    header { position: fixed; top: -60px; left: 0; right: 0; text-align: center; }
    footer {
        position: fixed;
        bottom: -40px;
        left: 0;
        right: 0;
        font-size: 10px;
        text-align: center;
        border-top: 1px solid #ccc;
        padding-top: 5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 6px 8px;
        text-align: left;
        font-size: 11px;
    }
    th {
        background-color: #f2f2f2;
    }
    .total {
        font-weight: bold;
        background-color: #e0e0e0;
    }
</style>

<header>
    <img src="' . $logoPath . '" width="100"><br>
    <strong>CECERT – Ceará Certificação</strong><br>
    <span>Relatório de Pagamentos – ' . sprintf('%02d', $mes) . '/' . $ano . '</span>
</header>

<footer>
    Gerado em ' . $dataHoraAtual . ' por ' . htmlspecialchars($admin) . ' | Página {PAGE_NUM} de {PAGE_COUNT}
</footer>

<main>
    <table>
        <thead>
            <tr>
                <th>Empregado</th>
                <th>CPF</th>
                <th>Status</th>
                <th>Valor</th>
                <th>Data de Pagamento</th>
            </tr>
        </thead>
        <tbody>
';

while ($row = $resultado->fetch_assoc()) {
    $nome = htmlspecialchars($row['nome']);
    $cpf = htmlspecialchars($row['cpf']);
    $status = htmlspecialchars(ucfirst($row['status']));
    $valor = number_format($row['valor'], 2, ',', '.');
    $data = date('d/m/Y', strtotime($row['data_pagamento']));
    $total += $row['valor'];

    $html .= "
        <tr>
            <td>$nome</td>
            <td>$cpf</td>
            <td>$status</td>
            <td>R$ $valor</td>
            <td>$data</td>
        </tr>
    ";
}

$stmt->close();

$html .= '
        <tr class="total">
            <td>Total</td>
            <td></td>
            <td></td>
            <td>R$ ' . number_format($total, 2, ',', '.') . '</td>
            <td></td>
        </tr>
    </tbody>
</table>
</main>
';

// Gerar PDF com DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Numeração da página
$canvas = $dompdf->getCanvas();
$font = $dompdf->getFontMetrics()->getFont('Arial', 'normal');
$canvas->page_text(520, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 8, [0, 0, 0]);

$dompdf->stream("relatorio_pagamentos_{$mes}_{$ano}.pdf", ["Attachment" => false]);
exit;
