<?php

// informa que a resposta será em formato json
header("Content-Type: application/json; charset=UTF-8");

// faz o mysqli apresentar os erros do banco
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// conecta com o banco de dados
include "conexao.php";

// verifica se a conexão foi realizada
$transacao_iniciada = false;

try {

    // recebe os dados enviados pelo javascript
    $dados = json_decode(file_get_contents("php://input"), true);

    // pega os produtos enviados
    $itens = $dados["itens"] ?? [];



    // verifica se existem produtos no pedido
    if (empty($itens) || !is_array($itens)) {
        throw new Exception("O carrinho está vazio.");
    }

    // inicia uma transação no banco
    $conn->begin_transaction();
    $transacao_iniciada = true;

    // calcula o valor usando os preços do banco
    $total = 0;
    $produtos_pedido = [];

    foreach ($itens as $item) {

        // pega o id e a quantidade do produto
        $produto_id = (int) ($item["id"] ?? 0);
        $quantidade = (int) ($item["quantidade"] ?? 0);

        // verifica se os dados são válidos
        if ($produto_id <= 0 || $quantidade <= 0) {
            throw new Exception("Produto ou quantidade inválida.");
        }

        // procura o produto no banco
        $sql = "SELECT id, preco FROM produtos
                WHERE id = ? AND ativo = 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $produto_id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $produto = $resultado->fetch_assoc();

        if (!$produto) {
            throw new Exception("Produto não encontrado: ID " . $produto_id);
        }

        // pega o preço verdadeiro do banco
        $preco = (float) $produto["preco"];

        // calcula o subtotal
        $total += $preco * $quantidade;

        // guarda os dados do produto
        $produtos_pedido[] = [
            "id" => $produto_id,
            "quantidade" => $quantidade,
            "preco" => $preco
        ];

        $stmt->close();
    }

    // registra a compra sem identificar o usuário
    $sql_compra = "INSERT INTO compras (usuario_id, total, status)
                   VALUES (NULL, ?, 'Pendente')";

    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->bind_param("d", $total);
    $stmt_compra->execute();

    // pega o número da compra criada
    $compra_id = $conn->insert_id;

    // verifica se a compra foi criada
    if ($compra_id <= 0) {
        throw new Exception("Não foi possível registrar a compra.");
    }

    $stmt_compra->close();

    // prepara o comando para registrar os produtos
    $sql_item = "INSERT INTO itens_compra
                 (compra_id, produto_id, quantidade, preco_unitario)
                 VALUES (?, ?, ?, ?)";

    $stmt_item = $conn->prepare($sql_item);

    // registra cada produto da compra
    foreach ($produtos_pedido as $produto) {

        $produto_id = $produto["id"];
        $quantidade = $produto["quantidade"];
        $preco = $produto["preco"];

        $stmt_item->bind_param(
            "iiid",
            $compra_id,
            $produto_id,
            $quantidade,
            $preco
        );

        $stmt_item->execute();
    }

    $stmt_item->close();

    // confirma o registro no banco
    $conn->commit();
    $transacao_iniciada = false;

    // envia a confirmação para o javascript
    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Compra realizada com sucesso!",
        "compra_id" => $compra_id,
        "total" => $total
    ]);

} catch (Throwable $erro) {

    // desfaz as alterações caso aconteça algum erro
    if ($transacao_iniciada) {
        $conn->rollback();
    }

    // envia o erro verdadeiro para o javascript
    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => $erro->getMessage()
    ]);

}

// fecha a conexão
$conn->close();

?>
