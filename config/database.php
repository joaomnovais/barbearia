<?php
    $host = "localhost";
    $utilizador = "root";
    $senha = "";
    $banco = "barbearia";

    $conexao = new mysqli(
        $host,
        $utilizador,
        $senha,
        $banco
    );

    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    }

    $conexao->set_charset("utf8");

?>