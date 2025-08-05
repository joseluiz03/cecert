<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

$html = '<h1>Teste PDF - CECERT</h1><p>Se você está vendo este PDF, o DOMPDF está funcionando!</p>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("teste_cecert.pdf", ["Attachment" => false]);
?>

