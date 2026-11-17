//Parte 1: Modo escuro//

//pega o botão que troca o tema do site
const botaoTema = document.querySelector("#theme-toggle");

//verifica se já existe uma preferência de tema salva
const temaSalvo = localStorage.getItem("doceNuvem_tema");

//se o tema salvo for escuro, ativa o modo escuro
if (temaSalvo === "escuro") {
    document.body.classList.add("dark-mode");
}

//verifica se o botão existe
if (botaoTema) {

    //quando clicar no botão, troca o tema do site
    botaoTema.addEventListener("click", () => {

        //adiciona ou tira o modo escuro
        document.body.classList.toggle("dark-mode");

        //verifica qual tema está ativado
        const temaAtual = document.body.classList.contains("dark-mode")
            ? "escuro"
            : "claro";

        //salva o tema escolhido
        localStorage.setItem("doceNuvem_tema", temaAtual);

        //muda o texto do botão
        botaoTema.textContent = temaAtual === "escuro"
            ? "☀️ Modo claro"
            : "🌙 Modo escuro";
    });

    //mostra o texto correto quando a página abre
    botaoTema.textContent = document.body.classList.contains("dark-mode")
        ? "☀️ Modo claro"
        : "🌙 Modo escuro";
}


//Parte 2: Modal//

//pega os elementos do formulário e da janela de mensagem
const formularioUsuario = document.querySelector("#form-usuario");
const cadastroModal = document.querySelector("#cadastro-modal");
const fecharModalCadastro = cadastroModal?.querySelector("#modal-close");
const cadastroFeedback = document.querySelector("#cadastro-feedback");

//verifica se os elementos existem
if (formularioUsuario && cadastroModal && fecharModalCadastro && cadastroFeedback) {

    //quando o formulário for enviado, deixa o php receber os dados
    formularioUsuario.addEventListener("submit", (event) => {

        //verifica se os campos foram preenchidos corretamente
        if (!formularioUsuario.checkValidity()) {
            event.preventDefault();
            return;
        }

    });

    //fecha a janela quando clicar no botão
    fecharModalCadastro.addEventListener("click", () => {
        cadastroModal.classList.remove("active");
    });

    //fecha a janela quando clicar fora dela
    cadastroModal.addEventListener("click", (event) => {
        if (event.target === cadastroModal) {
            cadastroModal.classList.remove("active");
        }
    });
}


//Parte 3: Alerta dinâmico//

//pega a área onde o aviso vai aparecer
const areaAlerta = document.querySelector("#inicio > section:nth-child(2)");

if (areaAlerta) {

    //cria uma div para mostrar o aviso
    const alerta = document.createElement("div");

    //adiciona uma classe para estilizar no css
    alerta.classList.add("dynamic-alert-banner");

    //define a mensagem do aviso
    alerta.textContent = "☁️ Uma nuvem de sabor está passando por aqui! Confira nossas delícias.";

    //coloca o aviso dentro da seção
    areaAlerta.appendChild(alerta);

    //espera meio segundo antes de iniciar a animação
    setTimeout(() => {
        alerta.classList.add("active");
    }, 500);
}


//Parte 4: Lista de tarefas//

//pega o formulário, o campo e a lista de tarefas
const taskForm = document.querySelector("#task-form");
const taskInput = document.querySelector("#task-input");
const taskList = document.querySelector("#task-list");

//verifica se os elementos existem
if (taskForm && taskInput && taskList) {

    //recupera as tarefas salvas no navegador
    let tarefas = [];

    try {
        tarefas = JSON.parse(localStorage.getItem("doceNuvem_tarefas")) || [];
    } catch {
        tarefas = [];
    }

    //função que salva as tarefas
    function salvarTarefas() {
        localStorage.setItem("doceNuvem_tarefas", JSON.stringify(tarefas));
    }

    //função que mostra as tarefas na tela
    function mostrarTarefas() {

        //limpa a lista antes de mostrar novamente
        taskList.innerHTML = "";

        //passa por todas as tarefas
        tarefas.forEach((tarefa, indice) => {

            //cria um item para a tarefa
            const item = document.createElement("li");

            //cria o espaço do nome da tarefa
            const texto = document.createElement("span");
            texto.textContent = tarefa.nome;

            //coloca um risco se a tarefa estiver concluída
            if (tarefa.concluida) {
                texto.style.textDecoration = "line-through";
            }

            //cria o botão de concluir
            const concluir = document.createElement("button");
            concluir.type = "button";
            concluir.textContent = tarefa.concluida ? "Desfazer" : "Concluir";

            //altera o estado da tarefa
            concluir.addEventListener("click", () => {

                tarefas[indice].concluida = !tarefas[indice].concluida;

                salvarTarefas();
                mostrarTarefas();
            });

            //cria o botão de excluir
            const excluir = document.createElement("button");
            excluir.type = "button";
            excluir.textContent = "Excluir";

            //remove a tarefa da lista
            excluir.addEventListener("click", () => {

                tarefas.splice(indice, 1);

                salvarTarefas();
                mostrarTarefas();
            });

            //coloca os elementos dentro do item
            item.append(texto, concluir, excluir);

            //adiciona o item na lista
            taskList.appendChild(item);
        });
    }

    //quando o formulário for enviado, adiciona uma tarefa
    taskForm.addEventListener("submit", (event) => {

        //impede a atualização automática da página
        event.preventDefault();

        //pega o nome da tarefa
        const nomeTarefa = taskInput.value.trim();

        //verifica se o campo está vazio
        if (nomeTarefa === "") return;

        //adiciona a tarefa na lista
        tarefas.push({
            nome: nomeTarefa,
            concluida: false
        });

        //salva e atualiza a lista
        salvarTarefas();
        mostrarTarefas();

        //limpa o campo
        taskInput.value = "";
    });

    //mostra as tarefas salvas quando a página abre
    mostrarTarefas();
}


//Parte 5: Sugestão de cupcakes//

//pega os elementos da sugestão
const botaoIA = document.querySelector("#ai-generate-btn");
const caixaResultadoIA = document.querySelector("#ai-result-box");
const textoResultadoIA = document.querySelector("#ai-output-text");

//verifica se os elementos existem
if (botaoIA && caixaResultadoIA && textoResultadoIA) {

    //lista com os sabores de cupcakes
    const cupcakes = [
        "🧁 Cupcake de Morango: uma combinação delicada de massa fofinha e cobertura de morango!",
        "🍫 Cupcake de Brigadeiro: perfeito para quem ama chocolate e muito sabor!",
        "🍪 Cupcake de Oreo: uma mistura deliciosa de chocolate com biscoito!",
        "🍦 Cupcake de Baunilha: clássico, leve e cheio de charme!",
        "💛 Cupcake de Maracujá com Chocolate: uma combinação especial de doce e azedinho!",
        "🍫 Cupcake de Kinder: uma opção cremosa para os apaixonados por chocolate!",
        "🧁 Cupcake Trufado: uma explosão de sabor com recheio especial!",
        "🍓 Cupcake Sensação: a combinação de morango com chocolate!",
        "🤍 Cupcake de Ninho com Nutella: cremosidade em cada mordida!"
    ];

    //quando clicar no botão, escolhe uma sugestão aleatória
    botaoIA.addEventListener("click", () => {

        //escolhe um sabor da lista
        const sugestao = cupcakes[Math.floor(Math.random() * cupcakes.length)];

        //mostra a sugestão na tela
        textoResultadoIA.textContent = sugestao;

        //deixa a caixa de resultado visível
        caixaResultadoIA.style.display = "block";
    });
}


//Parte 6: Pesquisa de produtos//

//pega os elementos da pesquisa
const campoPesquisa = document.querySelector("#feature-search");
const mensagemSemResultados = document.querySelector("#no-results");
const produtos = document.querySelectorAll("#produtos .produtos-grid article");

//verifica se os elementos existem
if (campoPesquisa && mensagemSemResultados) {

    //quando a pessoa digitar, começa a pesquisa
    campoPesquisa.addEventListener("input", () => {

        //transforma o texto digitado em letras minúsculas
        const pesquisa = campoPesquisa.value.toLowerCase().trim();

        //contador de produtos encontrados
        let encontrados = 0;

        //passa por todos os produtos
        produtos.forEach((produto) => {

            //pega o nome do produto
            const nome = produto.querySelector("h3")?.textContent.toLowerCase() || "";

            //verifica se o nome combina com a pesquisa
            if (nome.includes(pesquisa)) {

                produto.style.display = "";
                encontrados++;

            } else {

                produto.style.display = "none";
            }
        });

        //mostra uma mensagem se nenhum produto for encontrado
        mensagemSemResultados.style.display = encontrados === 0 ? "block" : "none";
    });
}


//Parte 7: Carrinho de compras//

//pega os botões de adicionar produtos
const botoesAdicionar = document.querySelectorAll(".itens-carrinho");

//pega os espaços do carrinho e o botão de finalizar
const areaCarrinho = document.querySelector("#itens-carrinho");
const totalCarrinho = document.querySelector("#total-carrinho");
const botaoFinalizar = document.querySelector("#finalizar-compra");

//cria uma lista para guardar os produtos
let carrinho = [];

//recupera os produtos salvos no navegador
try {
    carrinho = JSON.parse(localStorage.getItem("doceNuvemCarrinho")) || [];

    //verifica se os dados recuperados são uma lista
    if (!Array.isArray(carrinho)) {
        carrinho = [];
    }

} catch {
    carrinho = [];
}

//verifica se os elementos do carrinho existem
if (areaCarrinho && totalCarrinho && botaoFinalizar) {

    //função que salva o carrinho
    function salvarCarrinho() {
        localStorage.setItem("doceNuvemCarrinho", JSON.stringify(carrinho));
    }

    //função que atualiza os produtos e o total
    function atualizarCarrinho() {

        areaCarrinho.innerHTML = "";

        let total = 0;

        //mostra uma mensagem se o carrinho estiver vazio
        if (carrinho.length === 0) {
            areaCarrinho.textContent = "Seu carrinho está vazio.";
        }

        //passa por todos os produtos
        carrinho.forEach((produto, indice) => {

            const item = document.createElement("div");
            item.classList.add("item-carrinho");

            //mostra o nome do produto
            const nome = document.createElement("span");
            nome.textContent = produto.nome;

            //mostra a quantidade
            const quantidade = document.createElement("span");
            quantidade.textContent = `Quantidade: ${produto.quantidade}`;

            //calcula e mostra o preço do produto
            const preco = document.createElement("span");

            preco.textContent = (produto.preco * produto.quantidade).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL"
            });

            //cria o botão para diminuir a quantidade
            const diminuir = document.createElement("button");
            diminuir.type = "button";
            diminuir.textContent = "−";

            diminuir.addEventListener("click", () => {

                produto.quantidade--;

                if (produto.quantidade <= 0) {
                    carrinho.splice(indice, 1);
                }

                salvarCarrinho();
                atualizarCarrinho();
            });

            //cria o botão para aumentar a quantidade
            const aumentar = document.createElement("button");
            aumentar.type = "button";
            aumentar.textContent = "+";

            aumentar.addEventListener("click", () => {

                produto.quantidade++;

                salvarCarrinho();
                atualizarCarrinho();
            });

            //cria o botão para remover o produto
            const remover = document.createElement("button");
            remover.type = "button";
            remover.textContent = "Remover";

            remover.addEventListener("click", () => {

                carrinho.splice(indice, 1);

                salvarCarrinho();
                atualizarCarrinho();
            });

            //coloca as informações dentro do item
            item.append(nome, quantidade, preco, diminuir, aumentar, remover);

            //adiciona o item ao carrinho
            areaCarrinho.appendChild(item);

            //soma o valor dos produtos
            total += Number(produto.preco) * Number(produto.quantidade);
        });

        //mostra o valor total da compra
        totalCarrinho.textContent = total.toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        });
    }

    //passa por todos os botões de adicionar
    botoesAdicionar.forEach((botao) => {

        botao.addEventListener("click", () => {

            //pega os dados do produto
            const id = Number(botao.dataset.id);
            const nome = botao.dataset.nome;
            const preco = Number(botao.dataset.preco);

            //verifica se os dados são válidos
            if (!id || !nome || !Number.isFinite(preco)) {
                alert("Não foi possível adicionar esse produto.");
                return;
            }

            //procura se o produto já está no carrinho
            const produtoExistente = carrinho.find((produto) => Number(produto.id) === id);

            if (produtoExistente) {

                //aumenta a quantidade do produto
                produtoExistente.quantidade++;

            } else {

                //adiciona o produto ao carrinho
                carrinho.push({
                    id: id,
                    nome: nome,
                    preco: preco,
                    quantidade: 1
                });
            }

            //salva e atualiza o carrinho
            salvarCarrinho();
            atualizarCarrinho();

            alert(`${nome} foi adicionado ao carrinho! 🧁`);
        });
    });

    //quando clicar em finalizar, registra a compra
    botaoFinalizar.addEventListener("click", async () => {

        //verifica se o carrinho está vazio
        if (carrinho.length === 0) {
            alert("Seu carrinho está vazio!");
            return;
        }

        //verifica se todos os produtos possuem id válido
        const produtosValidos = carrinho.every((produto) => {
            return Number.isInteger(Number(produto.id)) &&
                   Number(produto.id) > 0 &&
                   Number(produto.quantidade) > 0;
        });

        if (!produtosValidos) {
            alert("Existem produtos antigos no carrinho. Limpe o carrinho e adicione os cupcakes novamente.");
            return;
        }

        //evita que o botão seja clicado várias vezes
        botaoFinalizar.disabled = true;
        botaoFinalizar.textContent = "Registrando pedido...";

        try {

            //envia os produtos para o php
            const resposta = await fetch("finalizar_compra.php", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({

                    //envia o id e a quantidade de cada produto
                    itens: carrinho.map((produto) => ({
                        id: produto.id,
                        quantidade: produto.quantidade
                    }))
                })
            });

            //recebe a resposta do php
            const resultado = await resposta.json();
console.log("Resposta do PHP:", resultado);
            //verifica se a compra foi registrada
            if (!resposta.ok || !resultado.sucesso) {
                throw new Error(resultado.mensagem || "Não foi possível registrar a compra.");
            }

            //mostra a confirmação da compra
            alert(
                `Compra número ${resultado.compra_id} registrada com sucesso!\n` +
                `Total: ${Number(resultado.total).toLocaleString("pt-BR", {
                    style: "currency",
                    currency: "BRL"
                })}\n\nObrigado por escolher a Doce Nuvem! ☁️`
            );

            //limpa o carrinho depois da confirmação do banco
            carrinho = [];

            salvarCarrinho();
            atualizarCarrinho();

        } catch (erro) {

            //mostra a mensagem caso aconteça algum erro
            alert("Erro ao finalizar a compra: " + erro.message);

        } finally {

            //libera o botão novamente
            botaoFinalizar.disabled = false;
            botaoFinalizar.textContent = "Finalizar compra";
        }
    });

    //mostra os produtos salvos quando a página abre
    atualizarCarrinho();
}
