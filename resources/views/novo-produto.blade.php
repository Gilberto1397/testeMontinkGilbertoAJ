@extends('layouts.app')

@section('title', 'Novo Produto')

@section('styles')
<style>
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 500;
    }

    /* Estilos para o carrinho lateral */
    .carrinho-lateral {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1050;
        overflow-y: auto;
    }

    .carrinho-lateral.aberto {
        right: 0;
    }

    .carrinho-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1040;
    }

    .carrinho-overlay.ativo {
        opacity: 1;
        visibility: visible;
    }

    .btn-carrinho-flutuante {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1030;
        width: 60px;
        height: 60px;
        border-radius: 50%;
    }

    .badge-carrinho {
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 12px;
        line-height: 20px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Se��o de listagem de produtos -->
            <article class="card border-0 rounded-3 mb-4">
                <header class="card-header bg-success text-white">
                    <h2 class="h4 mb-0"><i class="fas fa-list me-2"></i>Produtos Cadastrados</h2>
                </header>
                <div class="card-body p-4">
                    <div id="produtosContainer" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col">Nome do Produto</th>
                                    <th scope="col">Pre�o</th>
                                    <th scope="col">A��es</th>
                                </tr>
                                </thead>
                                <tbody id="produtosTableBody">
                                <!-- Os produtos ser�o inseridos aqui -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="semProdutos" class="text-center py-4 d-none">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum produto cadastrado ainda.</p>
                    </div>
                </div>
            </article>

            <!-- Formul�rio de cria��o de produto -->
            <article class="card border-0 rounded-3">
                <header class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0"><i class="fas fa-box me-2"></i>Criar Novo Produto</h1>
                </header>
                <div class="card-body p-4">
                    <form id="produtoForm">
                        <input id="produtoId" type="hidden">

                        <fieldset>
                            <legend class="visually-hidden">Informa��es do Produto</legend>

                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" id="nome"
                                       placeholder="Ex: Camiseta Algod�o">
                                <span id="nomeErro" class="text-danger fw-bold"></span>
                            </div>

                            <div class="mb-3">
                                <label for="preco" class="form-label">Pre�o (R$)</label>
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
                                    <h3 class="h5">Varia��es do Produto</h3>
                                    <button type="button" id="btnAdicionarVariacao"
                                            class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus me-1"></i>Adicionar Varia��o
                                    </button>
                                </div>
                                <hr>
                                <div id="variacoesContainer">
                                    <!-- As varia��es ser�o adicionadas aqui dinamicamente -->
                                </div>
                            </div>
                        </fieldset>

                        <div class="d-grid gap-2">
                            <button onclick="criarAtualizarProduto()" type="button" id="btnCriarProduto"
                                    class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Criar Produto
                            </button>
                            <button onclick="cancelarEdicao()" type="button" id="btnCancelarEdicao"
                                    class="btn btn-secondary btn-lg d-none">
                                <i class="fas fa-times me-2"></i>Cancelar Edi��o
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        </div>
    </div>
</div>

<!-- Modal de confirma��o para adicionar ao carrinho -->
<div class="modal fade" id="modalCarrinho" tabindex="-1" aria-labelledby="modalCarrinhoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCarrinhoLabel">
                    <i class="fas fa-cart-plus me-2"></i>Adicionar ao Carrinho
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <h6 id="nomeProdutoModal" class="mb-1"></h6>
                    <span id="precoProdutoModal" class="text-success fw-bold"></span>
                </div>
                <div class="mb-3">
                    <label for="quantidadeCarrinho" class="form-label">Quantidade</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" type="button" onclick="alterarQuantidade(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control text-center" id="quantidadeCarrinho" value="1" min="1">
                        <button class="btn btn-outline-secondary" type="button" onclick="alterarQuantidade(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div id="erroCarrinho" class="text-danger fw-bold d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAdicaoCarrinho()">
                    <i class="fas fa-cart-plus me-2"></i>Adicionar ao Carrinho
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Overlay do carrinho -->
<div class="carrinho-overlay" id="carrinhoOverlay" onclick="fecharCarrinho()"></div>

<!-- Carrinho lateral -->
<div class="carrinho-lateral" id="carrinhoLateral">
    <div class="p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Meu Carrinho</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fecharCarrinho()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="p-3" id="conteudoCarrinho">
        <div class="text-center py-4" id="carrinhoVazio">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <p class="text-muted">Seu carrinho est� vazio</p>
        </div>
        <div id="itensCarrinho" class="d-none">
            <!-- Os itens do carrinho ser�o inseridos aqui -->
        </div>
    </div>
    <div class="p-3 border-top" id="rodapeCarrinho" >
        <div class="d-flex justify-content-between mb-3">
            <strong>Total: <span id="totalCarrinho">R$ 0,00</span></strong>
        </div>
        <a href="{{ route('novo-pedido') }}" class="btn btn-success w-100">
            <i class="fas fa-credit-card me-2"></i>Finalizar Compra
        </a>
    </div>
</div>

<!-- Bot�o flutuante do carrinho -->
<button class="btn btn-success btn-carrinho-flutuante" onclick="abrirCarrinho()" style="display: none;"
        id="btnCarrinhoFlutuante">
    <i class="fas fa-shopping-cart"></i>
    <span class="badge bg-danger badge-carrinho" id="badgeCarrinho">0</span>
</button>
@endsection

@section('scripts')
<script>
    "use strict";

    // Contador para IDs �nicos de varia��es
    let contadorVariacoes = 0;

    // Carregar produtos ao carregar a p�gina
    document.addEventListener('DOMContentLoaded', function () {
        carregarProdutos();
    });

    // Fun��o para carregar produtos
    async function carregarProdutos() {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/v1/produtos', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            const resultado = await response.json();

            if (resultado.erro || !resultado.data || resultado.data.length === 0) {
                document.getElementById('semProdutos').classList.remove('d-none');
                return;
            }

            const produtos = resultado.data;
            const tbody = document.getElementById('produtosTableBody');
            tbody.innerHTML = '';

            produtos.forEach(produto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${produto.nome}</td>
                    <td>R$ ${parseFloat(produto.preco).toFixed(2).replace('.', ',')}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning me-2" onclick="editarProduto(${produto.id}, '${produto.nome}', '${produto.preco}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="abrirModalCarrinho(${produto.id}, '${produto.nome}', '${produto.preco}')">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById('produtosContainer').classList.remove('d-none');
            document.getElementById('semProdutos').classList.add('d-none');
        } catch (erro) {
            alert('Erro ao carregar produtos!');
            document.getElementById('semProdutos').classList.remove('d-none');
        }
    }

    // Valida��o para campos num�ricos
    document.querySelectorAll('#preco, #estoque').forEach(campo => {
        campo.addEventListener('keypress', function (e) {
            const char = String.fromCharCode(e.which);
            // Para o campo pre�o, permite n�meros e pontos
            if (this.id === 'preco') {
                if (!/[0-9\.]/.test(char) || (char === '.' && this.value.includes('.'))) {
                    e.preventDefault();
                }
            }
            // Para o campo estoque, permite apenas n�meros inteiros
            else if (this.id === 'estoque') {
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            }
        });
    });

    // Adicionar varia��o
    document.querySelector('#btnAdicionarVariacao').addEventListener('click', function () {
        adicionarVariacao();
    });

    // Fun��o para adicionar varia��o
    function adicionarVariacao() {
        const id = contadorVariacoes++;
        const variacaoHTML = `
            <div class="card mb-3 variacao-item" id="variacao-${id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="h6 mb-0">Varia��o #${id + 1}</h4>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remover-variacao" data-id="${id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="variacao-nome-${id}" class="form-label">Nome da Varia��o</label>
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

        // Adicionar valida��o para o novo campo de estoque
        document.querySelector(`#variacao-estoque-${id}`).addEventListener('keypress', function (e) {
            const char = String.fromCharCode(e.which);
            if (!/[0-9]/.test(char)) {
                e.preventDefault();
            }
        });

        // Adicionar listener para o bot�o de remover
        document.querySelector(`#variacao-${id} .btn-remover-variacao`).addEventListener('click', function () {
            const idVariacao = this.getAttribute('data-id');
            document.querySelector(`#variacao-${idVariacao}`).remove();
        });
    }

    // Fun��o para criar produto
    async function criarProduto() {
        try {
            limparMensagensErro();

            const nome = document.querySelector('#nome').value;
            const preco = document.querySelector('#preco').value;
            const estoque = document.querySelector('#estoque').value;

            // Coletar varia��es
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
            // Recarregar a lista de produtos
            await carregarProdutos();
        } catch (erro) {
            alert('Erro ao criar produto!');
        }
    }

    async function atualizarProduto() {
        try {
            limparMensagensErro();

            const nome = document.querySelector('#nome').value;
            const preco = document.querySelector('#preco').value;
            const estoque = document.querySelector('#estoque').value;
            const produtoId = document.querySelector('#produtoId').value;

            // Coletar varia��es
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
                produtoVariacao,
                produtoId
            };

            const response = await fetch('http://127.0.0.1:8000/api/v1/produtos', {
                method: 'PUT',
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

            alert('Produto atualizado com sucesso!');
            limpaFormulario();
            // Recarregar a lista de produtos
            await carregarProdutos();
        } catch (erro) {
            alert('Erro ao atualizar produto!');
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

        // Ocultar bot�o de cancelar edi��o
        document.getElementById('btnCancelarEdicao').classList.add('d-none');

        // Restaurar t�tulo do formul�rio
        document.querySelector('.card-header.bg-primary h1').innerHTML = '<i class="fas fa-box me-2"></i>Criar Novo Produto';

        // Restaurar texto do bot�o para "Criar Produto"
        document.querySelector('#btnCriarProduto').innerHTML = '<i class="fas fa-save me-2"></i>Criar Produto';

        // Remover ID do produto sendo editado
        document.querySelector('#produtoForm').removeAttribute('data-id');
    }

    // Fun��o para editar produto
    async function editarProduto(id, nome, preco) {
        // Preencher o formul�rio com os dados do produto
        document.querySelector('#nome').value = nome;
        document.querySelector('#preco').value = preco;

        // Atualizar t�tulo do formul�rio
        document.querySelector('.card-header.bg-primary h1').innerHTML = '<i class="fas fa-box me-2"></i>Editar Produto';

        // Atualizar texto do bot�o para "Salvar Altera��es"
        document.querySelector('#btnCriarProduto').innerHTML = '<i class="fas fa-save me-2"></i>Salvar Altera��es';

        // Exibir bot�o de cancelar edi��o
        document.getElementById('btnCancelarEdicao').classList.remove('d-none');

        // Armazenar ID do produto sendo editado
        document.querySelector('#produtoId').value = id;
    }

    // Fun��o para cancelar edi��o
    function cancelarEdicao() {
        if (confirm('Tem certeza que deseja cancelar a edi��o?')) {
            limpaFormulario();
        }
    }

    function criarAtualizarProduto() {
        const produtoId = document.querySelector('#produtoId').value;

        if (produtoId) {
            atualizarProduto();
        } else {
            criarProduto();
        }
    }

    // Vari�veis globais para o carrinho
    let carrinho = [];
    let quantidadeCarrinho = 1;

    // Fun��o para abrir o carrinho
    function abrirCarrinho() {
        document.getElementById('carrinhoLateral').classList.add('aberto');
        document.getElementById('carrinhoOverlay').classList.add('ativo');

        // Atualizar visualiza��o do carrinho
        atualizarCarrinho();
    }

    // Fun��o para fechar o carrinho
    function fecharCarrinho() {
        document.getElementById('carrinhoLateral').classList.remove('aberto');
        document.getElementById('carrinhoOverlay').classList.remove('ativo');
    }

    // Fun��o para abrir o modal do carrinho
    function abrirModalCarrinho(id, nome, preco) {
        document.getElementById('nomeProdutoModal').textContent = nome;
        document.getElementById('precoProdutoModal').textContent = `R$ ${parseFloat(preco).toFixed(2).replace('.', ',')}`;
        document.getElementById('quantidadeCarrinho').value = 1;
        document.getElementById('erroCarrinho').classList.add('d-none');

        // Armazenar informa��es do produto no modal
        window.produtoCarrinho = {
            id,
            nome,
            preco: parseFloat(preco)
        };

        // Exibir modal
        const modal = new bootstrap.Modal(document.getElementById('modalCarrinho'));
        modal.show();
    }

    // Fun��o para alterar a quantidade no modal do carrinho
    function alterarQuantidade(delta) {
        const quantidadeInput = document.getElementById('quantidadeCarrinho');
        let novaQuantidade = parseInt(quantidadeInput.value) + delta;

        // Garantir que a quantidade m�nima seja 1
        if (novaQuantidade < 1) {
            novaQuantidade = 1;
        }

        quantidadeInput.value = novaQuantidade;
    }

    // Fun��o para confirmar adi��o ao carrinho
    async function confirmarAdicaoCarrinho() {
        try {
            const {id, nome, preco} = window.produtoCarrinho;
            const quantidade = parseInt(document.getElementById('quantidadeCarrinho').value);

            // Preparar dados para enviar � API conforme AdicionarNoCarrinhoRequest
            const dadosCarrinho = {
                produtoId: id,
                quantidade: quantidade,
                nomeProduto: nome
            };

            // Enviar requisi��o para a API
            const response = await fetch('http://127.0.0.1:8000/api/v1/carrinho', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dadosCarrinho)
            });

            const resultado = await response.json();

            if (resultado.erro) {
                // Exibir erro no modal
                const erroCarrinho = document.getElementById('erroCarrinho');
                erroCarrinho.textContent = resultado.mensagem || 'Erro ao adicionar produto ao carrinho';
                erroCarrinho.classList.remove('d-none');
                return;
            }

            // Verificar se o produto j� est� no carrinho
            const produtoExistente = carrinho.find(item => item.id === id);

            carrinho.push({
                id,
                nome,
                preco,
                quantidade
            });

            // Fechar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCarrinho'));
            modal.hide();

            // Atualizar visualiza��o do carrinho
            atualizarCarrinho();

            alert('Produto adicionado ao carrinho com sucesso!');
        } catch (erro) {
            console.error('Erro ao adicionar produto ao carrinho:', erro);
            const erroCarrinho = document.getElementById('erroCarrinho');
            erroCarrinho.textContent = 'Erro de conex�o ao adicionar produto ao carrinho';
            erroCarrinho.classList.remove('d-none');
        }
    }

    // Fun��o para atualizar a visualiza��o do carrinho
    function atualizarCarrinho() {
        const conteudoCarrinho = document.getElementById('conteudoCarrinho');
        const carrinhoVazio = document.getElementById('carrinhoVazio');
        const itensCarrinho = document.getElementById('itensCarrinho');
        const rodapeCarrinho = document.getElementById('rodapeCarrinho');
        const totalCarrinho = document.getElementById('totalCarrinho');
        const badgeCarrinho = document.getElementById('badgeCarrinho');
        const btnCarrinhoFlutuante = document.getElementById('btnCarrinhoFlutuante');

        // Limpar conte�do atual
        itensCarrinho.innerHTML = '';

        if (carrinho.length === 0) {
            // Exibir mensagem de carrinho vazio
            carrinhoVazio.classList.remove('d-none');
            itensCarrinho.classList.add('d-none');
            rodapeCarrinho.style.display = 'none';
            badgeCarrinho.textContent = '0';
            btnCarrinhoFlutuante.style.display = 'none';
        } else {
            // Ocultar mensagem de carrinho vazio
            carrinhoVazio.classList.add('d-none');
            itensCarrinho.classList.remove('d-none');
            btnCarrinhoFlutuante.style.display = 'block';

            let total = 0;
            let nmrItens = 0;

            // Adicionar itens ao carrinho
            carrinho.forEach(item => {
                total += item.preco * item.quantidade;
                nmrItens++;

                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between align-items-center mb-2';
                div.innerHTML = `
                    <div>
                        <strong>${item.nome}</strong> (R$ ${item.preco.toFixed(2).replace('.', ',')})
                    </div>
                    <div>
                        <span class="badge bg-primary">${item.quantidade}</span>
                    </div>
                `;
                itensCarrinho.appendChild(div);
            });
            totalCarrinho.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
            badgeCarrinho.textContent = nmrItens;
        }
    }
</script>
@endsection
