<?php

// conecta com o banco de dados
include "conexao.php";

// procura as compras e os dados dos clientes
$sql = "SELECT
            compras.id AS compra_id, // pega o número da compra
            compras.total, // pega o valor total da compra
            compras.status, // pega o status do pedido
            compras.data_compra, // pega a data em que foi realizada
            usuarios.nome AS cliente // pega o nome do cliente
        FROM compras
        LEFT JOIN usuarios
        ON compras.usuario_id = usuarios.id
        ORDER BY compras.id DESC"; // organiza as compras da mais recente para a mais antiga

// executa a pesquisa no banco de dados
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <!-- configurações iniciais da página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- título que aparece na aba do navegador -->
    <title>Compras - Doce Nuvem</title>

    <!-- conecta o arquivo css com o html -->
    <link rel="stylesheet" href="styleia.css">

</head>

<body>

    <!-- cabeçalho da página -->
    <header>
        <h1>☁️ Compras da Doce Nuvem</h1>
        <p>Confira os pedidos realizados na loja!</p>
    </header>

    <!-- parte principal da página -->
    <main>

        <!-- verifica se existem compras cadastradas -->
        <?php if ($resultado && $resultado->num_rows > 0): ?>

            <!-- passa por todas as compras encontradas -->
            <?php while ($compra = $resultado->fetch_assoc()): ?>

                <!-- espaço onde ficam as informações de cada compra -->
                <section class="compra">

                    <!-- mostra o número do pedido -->
                    <h2>Pedido Nº <?php echo $compra["compra_id"]; ?></h2>

                    <p>
                        <strong>Cliente:</strong>

                        <?php
                        // mostra o nome do cliente ou informa que a compra não possui identificação
                        echo htmlspecialchars(
                            $compra["cliente"] ?? "Compra sem identificação"
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Data:</strong>

                        <?php
                        // transforma a data do banco para o formato brasileiro
                        echo date(
                            "d/m/Y H:i",
                            strtotime($compra["data_compra"])
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Status:</strong>

                        <!-- mostra a situação atual da compra -->
                        <?php echo htmlspecialchars($compra["status"]); ?>
                    </p>

                    <h3>Produtos comprados:</h3>

                    <?php

                    // procura os produtos que pertencem a essa compra
                    $sql_itens = "SELECT
                                    produtos.nome,
                                    itens_compra.quantidade,
                                    itens_compra.preco_unitario
                                  FROM itens_compra
                                  INNER JOIN produtos
                                  ON itens_compra.produto_id = produtos.id
                                  WHERE itens_compra.compra_id = ?";

                    // prepara a pesquisa para buscar os itens do pedido
                    $stmt = $conn->prepare($sql_itens);

                    // informa qual compra será pesquisada
                    $stmt->bind_param("i", $compra["compra_id"]);

                    // executa a pesquisa
                    $stmt->execute();

                    // guarda os produtos encontrados
                    $itens = $stmt->get_result();

                    ?>

                    <!-- passa por todos os produtos dessa compra -->
                    <?php while ($item = $itens->fetch_assoc()): ?>

                        <p>
                            <!-- mostra o nome do produto -->
                            <?php echo htmlspecialchars($item["nome"]); ?>

                            - Quantidade:
                            <!-- mostra quantas unidades foram compradas -->
                            <?php echo $item["quantidade"]; ?>

                            - Preço:
                            R$

                            <!-- mostra o preço de cada unidade no formato brasileiro -->
                            <?php echo number_format(
                                $item["preco_unitario"],
                                2,
                                ",",
                                "."
                            ); ?>
                        </p>

                    <!-- termina a repetição dos produtos -->
                    <?php endwhile; ?>

                    <!-- fecha a pesquisa dos produtos -->
                    <?php $stmt->close(); ?>

                    <h3>
                        Total:

                        R$

                        <!-- mostra o valor total da compra -->
                        <?php echo number_format(
                            $compra["total"],
                            2,
                            ",",
                            "."
                        ); ?>
                    </h3>

                </section>

                <!-- separa uma compra da outra -->
                <hr>

            <!-- termina a repetição das compras -->
            <?php endwhile; ?>

        <?php else: ?>

            <!-- mensagem mostrada quando ainda não existem compras -->
            <p>Nenhuma compra foi realizada ainda.</p>

        <?php endif; ?>

    </main>

</body>
</html>

<?php

// fecha a conexão com o banco
$conn->close();

?>
