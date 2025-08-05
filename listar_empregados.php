<?php
session_start();
if (!isset($_SESSION["admin_logado"])) {
    header("Location: index.php");
    exit;
}

include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empregados</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #004aad;
        }

        .filtro-container {
            margin-bottom: 20px;
        }

        .filtro-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            max-width: 400px;
        }

        .filtro-box input {
            flex: 1;
            font-size: 15px;
            padding: 8px;
            border: none;
            outline: none;
        }

        .tabela-container {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            background-color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #004aad;
            color: white;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .voltar {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #004aad;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .voltar:hover {
            background-color: #003080;
        }

        .tag {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .ativo {
            background-color: #d4edda;
            color: #155724;
        }

        .inativo {
            background-color: #e2e3e5;
            color: #6c757d;
        }

        .toggle-icon {
            margin-left: 10px;
            text-decoration: none;
            font-size: 16px;
            vertical-align: middle;
        }

        .toggle-icon:hover {
            color: #004aad;
        }

        #btnGerarPDF {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #004aad;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        #btnGerarPDF:hover {
            background-color: #003080;
        }

        .btn-wrapper {
            text-align: center;
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- jsPDF e autoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body>

<h2>Lista de Empregados Cadastrados</h2>

<!-- Campo de busca com ícone -->
<!-- Filtro com campo expansível -->
<style>
  .busca-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
  }

  .busca-container {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .campo-expandido {
    width: 0;
    opacity: 0;
    transition: all 0.3s ease;
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
  }

  .campo-expandido.visivel {
    width: 220px;
    opacity: 1;
  }

  .botao-icone {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
  }

  .botao-icone svg:hover {
    stroke: #003080;
  }
</style>

<div class="busca-wrapper">
  <div class="busca-container">
    <!-- Campo escondido inicialmente -->
    <input type="text" id="filtroTabela" class="campo-expandido" placeholder="Buscar...">

    <!-- Ícone Heroicons -->
    <button class="botao-icone" id="botaoFiltro" title="Buscar na tabela">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none"
           viewBox="0 0 24 24" stroke-width="1.5" stroke="#004aad" width="26" height="26">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
      </svg>
    </button>
  </div>
</div>

    <!-- Ícone Heroicons -->
    

<!-- Tabela com scroll -->
<div class="tabela-container">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Data de Nascimento</th>
                <th>Status</th>
                <th>Cadastrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($mysqli) && !$mysqli->connect_errno) {
                $sql = "SELECT e.id, e.nome AS nome_empregado, e.cpf, e.data_nasc, e.status, a.nome AS nome_admin
                        FROM empregados e
                        LEFT JOIN administradores a ON e.id_admin = a.id
                        ORDER BY e.nome ASC";

                $resultado = $mysqli->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($linha = $resultado->fetch_assoc()) {
                        $statusTag = $linha["status"] === "ativo" 
                            ? "<span class='tag ativo'>Credenciado</span>" 
                            : "<span class='tag inativo'>Descredenciado</span>";

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($linha["nome_empregado"]) . "</td>";
                        echo "<td>" . htmlspecialchars($linha["cpf"]) . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($linha["data_nasc"])) . "</td>";
                        echo "<td>$statusTag 
                                <a class='toggle-icon' href='editar_status.php?id=" . $linha["id"] . "' title='Alterar status'>&#x21bb;</a>
                              </td>";
                        echo "<td>" . htmlspecialchars($linha["nome_admin"] ?? 'Não identificado') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum empregado cadastrado.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Erro de conexão com o banco de dados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Botão PDF centralizado -->
<div class="btn-wrapper">
    <button id="btnGerarPDF">
        <i id="iconPdf" class="bi bi-file-earmark-arrow-down"></i> Exportar para PDF
    </button>
</div>

<!-- Botão voltar -->
<a href="painel.php" class="voltar">← Voltar ao Painel</a>

<!-- Filtro por texto -->
<script>
  const campo = document.getElementById("filtroTabela");
  const botao = document.getElementById("botaoFiltro");

  botao.addEventListener("click", () => {
    campo.classList.toggle("visivel");
    campo.focus();
  });

  campo.addEventListener("input", function () {
    const filtro = this.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    const linhas = document.querySelectorAll("table tbody tr");

    linhas.forEach(linha => {
      const textoLinha = linha.innerText.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
      linha.style.display = textoLinha.includes(filtro) ? "" : "none";
    });
  });
</script>


<!-- Gerar PDF -->
<script>
async function urlToBase64(url) {
  const res = await fetch(url);
  const blob = await res.blob();
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(blob);
  });
}

const btn = document.getElementById('btnGerarPDF');
const icon = document.getElementById('iconPdf');

btn.addEventListener('click', async () => {
  icon.classList.add('spin');

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  const logoUrl = "https://lirp.cdn-website.com/339e2492/dms3rep/multi/opt/LOGO-PNG--281-29-640w.png";

  let imgData;
  try {
    imgData = await urlToBase64(logoUrl);
  } catch (e) {
    alert('Erro ao carregar o logo para o PDF.');
    imgData = null;
  }

  if (imgData) {
    doc.addImage(imgData, 'PNG', 10, 10, 40, 20);
  }

  doc.setFontSize(20);
  doc.setTextColor(0, 74, 173);
  const pageWidth = doc.internal.pageSize.getWidth();
  const text = "EMPREGADOS";
  const textWidth = doc.getTextWidth(text);
  const startX = 60 + ((pageWidth - 60) / 2) - (textWidth / 2);
  const startY = 20;
  doc.text(text, startX, startY);

  const headers = [];
  document.querySelectorAll('table thead tr th').forEach(th => {
    headers.push(th.innerText.trim());
  });

  const data = [];
  document.querySelectorAll('table tbody tr').forEach(tr => {
    const rowData = [];
    tr.querySelectorAll('td').forEach(td => {
      rowData.push(td.innerText.trim());
    });
    data.push(rowData);
  });

  doc.autoTable({
    head: [headers],
    body: data,
    startY: 35,
    styles: { fontSize: 10 },
    headStyles: { fillColor: [0, 74, 173] }
  });

  doc.save("lista_empregados.pdf");
  icon.classList.remove('spin');
});
</script>

</body>
</html>
