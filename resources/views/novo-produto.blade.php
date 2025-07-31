<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Produto</title>
    <!-- Bootstrap 5 CSS -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="{{asset('js/app.js')}}"></script>
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body class="bg-light">
<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <article class="card border-0 rounded-3">
                    <header class="card-header bg-primary text-white">
                        <h1 class="h3 mb-0"><i class="fas fa-box me-2"></i>Criar Novo Produto</h1>
                    </header>
                    <div class="card-body p-4">
                        <form id="produtoForm">
                            <fieldset>
                                <legend class="visually-hidden">Informações do Produto</legend>

                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome do Produto</label>
                                    <input type="text" class="form-control" id="nome"
                                           placeholder="Ex: Camiseta Algodão">
                                    <span id="nomeErro" class="text-danger fw-bold"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="preco" class="form-label">Preço (R$)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control" id="preco" placeholder="0.00">
                                    </div>
                                    <span id="precoErro" class="text-danger fw-bold"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="estoque" class="form-label">Quantidade em Estoque</label>
                                    <input type="text" class="form-control" id="estoque" placeholder="0">
                                    <span id="estoqueErro" class="text-danger fw-bold"></span>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="h5">Variações do Produto</h3>
                                        <button type="button" id="btnAdicionarVariacao" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-plus me-1"></i>Adicionar Variação
                                        </button>
                                    </div>
                                    <hr>
                                    <div id="variacoesContainer">
                                        <!-- As variações serão adicionadas aqui dinamicamente -->
                                    </div>
                                </div>
                            </fieldset>

                            <div class="d-grid gap-2">
                                <button onclick="criarProduto()" type="button" id="btnCriarProduto" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Criar Produto
                                </button>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    </div>
</main>

<script>
    "use strict";

    // Contador para IDs únicos de variações
    let contadorVariacoes = 0;

    // Validação para campos numéricos
    document.querySelectorAll('#preco, #estoque').forEach(campo => {
        campo.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            // Para o campo preço, permite números e pontos
            if (this.id === 'preco') {
                if (!/[0-9\.]/.test(char) || (char === '.' && this.value.includes('.'))) {
                    e.preventDefault();
                }
            }
            // Para o campo estoque, permite apenas números inteiros
            else if (this.id === 'estoque') {
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            }
        });
    });

    // Adicionar variação
    document.querySelector('#btnAdicionarVariacao').addEventListener('click', function() {
        adicionarVariacao();
    });

    // Função para adicionar variação
    function adicionarVariacao() {
        const id = contadorVariacoes++;
        const variacaoHTML = `
            <div class="card mb-3 variacao-item" id="variacao-${id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="h6 mb-0">Variação #${id + 1}</h4>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remover-variacao" data-id="${id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="variacao-nome-${id}" class="form-label">Nome da Variação</label>
                        <input type="text" class="form-control" id="variacao-nome-${id}" placeholder="Ex: Tamanho P">
                        <span id="produtoVariacao.${id}.nome" class="text-danger fw-bold"></span>
                    </div>
                    <div class="mb-2">
                        <label for="variacao-estoque-${id}" class="form-label">Quantidade em Estoque</label>
                        <input type="text" class="form-control variacao-estoque" id="variacao-estoque-${id}" placeholder="0">
                        <span id="produtoVariacao.${id}.estoque" class="text-danger fw-bold"></span>
                    </div>
                </div>
            </div>
        `;

        document.querySelector('#variacoesContainer').insertAdjacentHTML('beforeend', variacaoHTML);

        // Adicionar validação para o novo campo de estoque
        document.querySelector(`#variacao-estoque-${id}`).addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/[0-9]/.test(char)) {
                e.preventDefault();
            }
        });

        // Adicionar listener para o botão de remover
        document.querySelector(`#variacao-${id} .btn-remover-variacao`).addEventListener('click', function() {
            const idVariacao = this.getAttribute('data-id');
            document.querySelector(`#variacao-${idVariacao}`).remove();
        });
    }

    // Função para criar produto
    async function criarProduto() {
        try {
            limparMensagensErro();

            const nome = document.querySelector('#nome').value;
            const preco = document.querySelector('#preco').value;
            const estoque = document.querySelector('#estoque').value;

            // Coletar variações
            const produtoVariacao = [];
            document.querySelectorAll('.variacao-item').forEach(item => {
                const id = item.id.split('-')[1];
                const nomeVariacao = document.querySelector(`#variacao-nome-${id}`).value;
                const estoqueVariacao = document.querySelector(`#variacao-estoque-${id}`).value;

                produtoVariacao.push({
                    nome: nomeVariacao,
                    estoque: estoqueVariacao
                });
            });

            const dados = {
                nome,
                preco,
                estoque,
                produtoVariacao
            };

            const response = await fetch('http://127.0.0.1:8000/api/v1/produtos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dados)
            });
            const resultado = await response.json();

            if (resultado.erro) {
                if (resultado.mensagens) {
                    exibirErroInput(resultado.mensagens);
                    return;
                }
            }

            alert('Produto criado com sucesso!');
            limpaFormulario();
        } catch (erro) {
            console.log(erro)
            alert('Erro ao criar produto!');
        }
    }

    function exibirErroInput(mensagensDeErro) {
        for (const campoMensagem in mensagensDeErro) {
            const mensagemDeErro = mensagensDeErro[campoMensagem][0];

            if (campoMensagem === 'nome') {
                document.getElementById('nomeErro').textContent = mensagemDeErro;
            } else if (campoMensagem === 'preco') {
                document.getElementById('precoErro').textContent = mensagemDeErro;
            } else if (campoMensagem === 'estoque') {
                document.getElementById('estoqueErro').textContent = mensagemDeErro;
            } else if (campoMensagem.startsWith('produtoVariacao')) {
                document.getElementById(campoMensagem).textContent = mensagemDeErro;
            }
        }
    }

    function limparMensagensErro() {
        document.querySelectorAll('.text-danger.fw-bold').forEach(span => {
            span.textContent = '';
        });
    }

    function limpaFormulario() {
        document.getElementById('produtoForm').reset();
        document.querySelector('#variacoesContainer').innerHTML = '';
        contadorVariacoes = 0;
    }
</script>
</body>
</html>
