<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php'; // Usa $mysqli

// Proteção e fallback
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

// Mensagem de confirmação de PDF gerado
$mensagem_pdf = '';
if (isset($_GET['pdf']) && $_GET['pdf'] === 'ok') {
    $mensagem_pdf = "📄 PDF gerado com sucesso!";
}

// Consulta pagamentos
$stmt = $mysqli->prepare("
    SELECT e.nome, p.valor, p.mes, p.ano, p.data_pagamento
    FROM pagamentos p
    JOIN empregados e ON p.empregado_id = e.id
    WHERE p.mes = ? AND p.ano = ?
    ORDER BY e.nome
");
$stmt->bind_param("ii", $mes, $ano);
$stmt->execute();
$resultado = $stmt->get_result();

$total = 0;
$pagamentos = [];
while ($row = $resultado->fetch_assoc()) {
    $pagamentos[] = $row;
    $total += $row['valor'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Pagamentos</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
        }
        .mensagem-pdf {
            background: #d1ecf1;
            color: #0c5460;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #bee5eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .filtros {
            margin-bottom: 16px;
        }
        .btn {
            padding: 8px 16px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Relatório de Pagamentos</h2>

        <?php if ($mensagem_pdf): ?>
            <div class="mensagem-pdf">
                <?php echo $mensagem_pdf; ?>
            </div>
        <?php endif; ?>

        <form class="filtros" method="get">
            <label>Mês:
                <input type="number" name="mes" min="1" max="12" value="<?php echo $mes; ?>" required>
            </label>
            <label>Ano:
                <input type="number" name="ano" min="2000" max="2100" value="<?php echo $ano; ?>" required>
            </label>
            <button type="submit" class="btn">Filtrar</button>
            <a href="gerar_pdf.php?mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>" class="btn" target="_blank">Gerar PDF</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Empregado</th>
                    <th>Valor</th>
                    <th>Data de Pagamento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagamentos as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['nome']); ?></td>
                        <td>R$ <?php echo number_format($p['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($p['data_pagamento'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total Pago:</strong> R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
    </div>
</body>
</html>
