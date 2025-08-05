<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php'; // define $mysqli

// Buscar empregados
$sql = "SELECT id, nome FROM empregados ORDER BY nome ASC";
$resultado = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pagamento</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        input[type="number"], input[type="text"] {
            width: 100px;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Pagamento</h2>

        <form action="processar_pagamento.php" method="post">
            <label>Mês: <input type="number" name="mes" min="1" max="12" required></label>
            <label>Ano: <input type="number" name="ano" min="2000" max="2100" required></label>

            <table>
                <thead>
                    <tr>
                        <th>Empregado</th>
                        <th>Valor a Pagar (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($empregado = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($empregado['nome']); ?></td>
                            <td>
                                <input type="number" step="0.01" name="valor[<?php echo $empregado['id']; ?>]">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button type="submit" class="btn">Salvar Pagamentos</button>
        </form>
    </div>
</body>
</html>
