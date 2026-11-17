<?php

// conecta com o banco de dados
include "conexao.php";

// busca os produtos que estão ativos no banco
$sql = "SELECT id, codigo, nome, descricao, preco, imagem
        FROM produtos
        WHERE ativo = 1
        ORDER BY id ASC";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- configurações iniciais da página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doce Nuvem</title>

    <!-- conecta o arquivo css com o html -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- cabeçalho do site -->
    <header>

        <!-- imagem da logo -->
        <div class="logo">
            <img src="logo1.png" alt="Doce Nuvem">
        </div>

        <!-- enfeite que fica no cabeçalho -->
        <div>
            <h1>︷︷︷︷︷︷︷︷︷︷︷︷︷︷︷︷</h1>
        </div>

        <!-- menu de navegação do site -->
        <nav>
            <ul>
                <!-- links para acessar as outras páginas -->
                <li><a href="index.html">Início</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="contato.html">Contato</a></li>
                <li><a href="usuario.php">Usuário</a></li>

                <!-- link que leva até o carrinho -->
                <li><a href="#carrinho">🛒 Carrinho</a></li>

                <!-- botão para trocar o tema do site -->
                <li>
                    <button id="theme-toggle" class="theme-btn">
                        🌙 Modo escuro
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <!-- parte principal da página de produtos -->
    <main id="produtos">

        <!-- título da página -->
        <h2>Produtos</h2>

        <section>

            <!-- título da parte dos cupcakes -->
            <h2>- Os cupcakes mais pedidos -</h2>

            <!-- campo de pesquisa dos produtos -->
            <div class="search-container">
                <input type="text" id="feature-search" placeholder="Pesquise">

                <!-- mensagem que aparece quando não encontra nenhum produto -->
                <p id="no-results" class="no-results-msg">
                    Nada encontrado
                </p>
            </div>

            <!-- grade onde ficam organizados os cupcakes -->
            <div class="produtos-grid">

                <?php if ($resultado && $resultado->num_rows > 0): ?>

                    <!-- mostra os produtos cadastrados no banco -->
                    <?php while ($produto = $resultado->fetch_assoc()): ?>

                        <article>

                            <div>
                                <img
                                    src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                >
                            </div>

                            <h3>
                                <?php echo htmlspecialchars($produto['nome']); ?>
                            </h3>

                            <p>
                                R$<?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </p>

                            <!-- botão para adicionar o produto no carrinho -->
                            <button
                                class="itens-carrinho"
                                data-id="<?php echo $produto['id']; ?>"
                                data-codigo="<?php echo htmlspecialchars($produto['codigo']); ?>"
                                data-nome="<?php echo htmlspecialchars($produto['nome']); ?>"
                                data-preco="<?php echo $produto['preco']; ?>"
                            >
                                Adicionar ao carrinho
                            </button>

                        </article>

                    <?php endwhile; ?>

                <?php else: ?>

                    <!-- mensagem caso não existam produtos cadastrados -->
                    <p>Nenhum produto encontrado.</p>

                <?php endif; ?>

            </div>
        </section>

    </main>

    <!-- parte do carrinho de compras -->
    <section class="carrinho" id="carrinho">

        <h2>🛒 Carrinho</h2>

        <!-- espaço onde aparecem os produtos adicionados -->
        <div id="itens-carrinho">
            <p>Seu carrinho está vazio.</p>
        </div>

        <!-- mostra o valor total da compra -->
        <div class="total">
            <strong>
                Total: R$ <span id="total-carrinho">0,00</span>
            </strong>
        </div>

        <!-- botão para finalizar a compra -->
        <button id="finalizar-compra">
            Finalizar compra
        </button>

    </section>

    <!-- rodapé do site -->
    <footer>

        <h3>Doce Nuvem - Todos os direitos reservados</h3>

        <!-- link para a página de contato -->
        <a href="contato.html">Entre em contato</a>

    </footer>

    <!-- conecta o arquivo javascript com o html -->
    <script src="script.js"></script>

</body>
</html>

<?php

// fecha a conexão com o banco
$conn->close();

?>
