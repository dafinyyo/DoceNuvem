<?php
// verifica se o cadastro foi realizado
$cadastro_realizado = isset($_GET["cadastro"]) && $_GET["cadastro"] == "sucesso";
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
            <img src="logo1.png" alt="docenuvem">
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
                <li><a href="produtos.html#carrinho">🛒 Carrinho</a></li>

                <!-- botão para trocar o tema do site -->
                <li>
                    <button id="theme-toggle" class="theme-btn">
                        🌙 Modo escuro
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <!-- parte principal da página de usuário -->
    <main id="usuario">

        <!-- título da área de cadastro -->
        <h2>Preencha com os seus dados</h2>

        <!-- formulário para preencher os dados do usuário -->
<form id="form-usuario" method="POST" action="salvar_usuario.php">
                <div>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome"
                    placeholder="Seu nome" required>
            </div>

            <!-- campo para colocar o cpf -->
            <div>
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf"
                    placeholder="XXX.XXX.XXX-XX" required>
            </div>

            <!-- campo para colocar o número de whatsapp -->
            <div>
                <label for="telefone">Whatsapp</label>
                <input type="tel" id="telefone" name="telefone"
                    placeholder="(19) 00000-0000" required>
            </div>

            <!-- campo para colocar o email -->
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    placeholder="seuemail@gmail.com" required>
            </div>

            <!-- campo para colocar o endereço -->
            <div>
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco"
                    placeholder="Seu endereço" required>
            </div>

            <!-- botão para enviar os dados preenchidos -->
            <button type="submit">
                Prosseguir
            </button>

        </form>

        <!-- janela que aparece depois do cadastro -->
        <div class="modal-overlay" id="cadastro-modal">

            <div class="modal-content">

                <!-- botão para fechar a janela -->
                <button type="button" class="modal-close" id="modal-close">
                    x
                </button>

                <!-- mensagem de confirmação do cadastro -->
                <h2>☁️ Cadastro realizado!</h2>

                <p id="cadastro-feedback">
                    Seja bem-vindo à Doce Nuvem!
                </p>

            </div>

        </div>

    </main>

    <!-- rodapé do site -->
    <footer>
        <h3>Doce Nuvem</h3>

        <!-- link para a página de contato -->
        <a href="contato.html">Entre em contato</a>
    </footer>

   
    <!-- conecta o javascript com a página -->
    <script src="script.js"></script>

    <!-- abre a mensagem depois que o php confirmar o cadastro -->
    <?php if ($cadastro_realizado): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("cadastro-modal");
            const mensagem = document.getElementById("cadastro-feedback");

            if (modal && mensagem) {
                mensagem.textContent = "Seu cadastro foi realizado com sucesso! Seja bem-vindo(a) à Doce Nuvem!";
                modal.classList.add("active");
            }
        });
    </script>
    <?php endif; ?>

</body>
</html>