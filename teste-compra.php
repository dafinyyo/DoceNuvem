<?php

include "conexao.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // inicia uma transação
    $conn->begin_transaction();

    // tenta registrar uma compra de teste
    $total = 10.00;

    $sql = "INSERT INTO compras (usuario_id, total, status)
            VALUES (NULL, ?, 'Teste')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $total);
    $stmt->execute();

    // pega o id criado
    $id = $conn->insert_id;

    // confirma o registro
    $conn->commit();

    echo "Compra de teste registrada!<br>";
    echo "Número da compra: " . $id . "<br>";

    $stmt->close();

} catch (Throwable $erro) {

    $conn->rollback();

    echo "ERRO: " . $erro->getMessage();

}

$conn->close();

?>
