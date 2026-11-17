<?php

// conecta com o banco de dados
include "conexao.php";

// verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // pega os dados preenchidos no formulário
    $nome = trim($_POST["nome"] ?? "");
    $cpf = trim($_POST["cpf"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");

    // prepara o comando para salvar os dados
    $sql = "INSERT INTO usuarios (nome, cpf, telefone, email, endereco)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // verifica se o comando foi preparado
    if (!$stmt) {
        die("Erro ao preparar cadastro: " . $conn->error);
    }

    // coloca os dados nos espaços do comando
    $stmt->bind_param("sssss", $nome, $cpf, $telefone, $email, $endereco);

    // tenta salvar no banco
    if ($stmt->execute()) {

        // volta para a página mostrando a confirmação
        header("Location: usuario.php?cadastro=sucesso");
        exit();

    } else {

        // mostra o erro caso não consiga salvar
        die("Erro ao salvar cadastro: " . $stmt->error);
    }

    $stmt->close();

} else {

    echo "O formulário não foi enviado corretamente.";
}

// fecha a conexão
$conn->close();

?>
