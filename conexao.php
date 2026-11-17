<?php

/*-dados para conectar com o banco*/
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "doce_nuvem";
$porta = 3306;

/*-fazendo a conexão com o banco*/
$conn = new mysqli($servidor, $usuario, $senha, $banco, $porta);

/*-verifica se aconteceu algum erro*/
if ($conn->connect_error) {
    die("erro na conexão: " . $conn->connect_error);
}

/*-permite utilizar acentos e caracteres especiais*/
$conn->set_charset("utf8mb4");

?>
