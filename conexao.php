<?php
    $hostname = "localhost";
    $bancodedados = "cecert";
    $usuario = "root";
    $senha = "";

    $mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);
    if ($mysqli->connect_errno) {
        echo "Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
//     else
//         echo "Conectado ao Banco de Dados";
?>