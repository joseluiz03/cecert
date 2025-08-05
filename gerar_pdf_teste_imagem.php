<?php
// Mostrar erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$logoPath = 'file://' . realpath(__DIR__ . '/imagens/logo_ceara_certificacao.png');

$html = '
    <h1>Teste de Imagem no PDF</h1>
    <p>Se você estiver vendo a imagem abaixo, está tudo funcionando.</p>
    <img src="' . $logoPath . '" width="300" />
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("teste_imagem.pdf", ["Attachment" => false]);
exit;
