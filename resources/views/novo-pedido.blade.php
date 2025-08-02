@extends('layouts.app')

@section('title', 'Novo Pedido')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Endereço de Entrega</h4>
                </div>
                <div class="card-body">
                    <form id="enderecoForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="cep" class="form-label">CEP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9" required>
                                    <button type="button" class="btn btn-outline-secondary" id="btnBuscarCep">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="cepError"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="logradouro" class="form-label">Logradouro <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="logradouro" name="logradouro" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="numero" class="form-label">Número <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="numero" name="numero" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="bairro" name="bairro" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Apartamento, bloco, etc.">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cidade" name="cidade" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="uf" class="form-label">UF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="uf" name="uf" maxlength="2" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card de Valores do Pedido -->
            <div class="card mt-4" id="cardValores" style="display: none;">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Resumo do Pedido</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="frete" class="form-label">Valor do Frete</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="frete" name="frete" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="valorTotalPedido" class="form-label">Valor Total do Pedido</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="valorTotalPedido" name="valorTotalPedido" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="loadingValores" class="text-center" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Carregando valores...</span>
                                </div>
                                <small class="text-muted">Calculando valores do pedido...</small>
                            </div>
                            <div id="erroValores" class="alert alert-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span id="mensagemErroValores"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Botão de Confirmar Pedido -->
                    <div class="row mt-3">
                        <div class="col-12 d-grid">
                            <button type="button" class="btn btn-success btn-lg" id="btnConfirmarPedido">
                                <i class="fas fa-check-circle me-2"></i>
                                Confirmar Pedido
                            </button>
                        </div>
                    </div>

                    <!-- Feedback do pedido -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div id="loadingPedido" class="text-center" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-success me-2" role="status">
                                    <span class="visually-hidden">Criando pedido...</span>
                                </div>
                                <small class="text-muted">Criando seu pedido...</small>
                            </div>
                            <div id="sucessoPedido" class="alert alert-success" style="display: none;">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="mensagemSucessoPedido"></span>
                            </div>
                            <div id="erroPedido" class="alert alert-danger" style="display: none;">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <span id="mensagemErroPedido"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mb-0">Buscando CEP...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cepInput = document.getElementById('cep');
        const btnBuscarCep = document.getElementById('btnBuscarCep');
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

        // Máscara para CEP
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            }
        });

        // Buscar CEP automaticamente quando o campo perde o foco
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                buscarCep(cep);
            }
        });

        // Buscar CEP ao clicar no botão
        btnBuscarCep.addEventListener('click', function() {
            const cep = cepInput.value.replace(/\D/g, '');
            if (cep.length === 8) {
                buscarCep(cep);
            } else {
                mostrarErro('CEP deve conter 8 dígitos');
            }
        });

        function buscarCep(cep) {
            loadingModal.show();
            limparErros();

            fetch(`/api/v1/cep/${cep}`)
                .then(response => response.json())
                .then(data => {
                    loadingModal.hide();

                    if (data.erro) {
                        mostrarErro(data.mensagem || 'CEP não encontrado');
                        return;
                    }

                    if (data.data && !data.data.erro) {
                        preencherEndereco(data.data);
                        // Buscar valores do pedido após preencher o endereço
                        buscarValoresPedido();
                    } else {
                        mostrarErro('CEP não encontrado');
                    }
                })
                .catch(error => {
                    loadingModal.hide();
                    mostrarErro('Erro ao buscar CEP. Tente novamente.');
                });
        }

        function preencherEndereco(endereco) {
            document.getElementById('logradouro').value = endereco.logradouro || '';
            document.getElementById('bairro').value = endereco.bairro || '';
            document.getElementById('cidade').value = endereco.localidade || '';
            document.getElementById('uf').value = endereco.uf || '';

            // Foca no campo número após preencher
            document.getElementById('numero').focus();
        }

        function buscarValoresPedido() {
            const cardValores = document.getElementById('cardValores');
            const loadingValores = document.getElementById('loadingValores');
            const erroValores = document.getElementById('erroValores');

            // Mostrar o card de valores
            cardValores.style.display = 'block';
            loadingValores.style.display = 'block';
            erroValores.style.display = 'none';

            fetch('/api/v1/pedidos/valores-pedido')
                .then(response => response.json())
                .then(data => {
                    loadingValores.style.display = 'none';

                    if (data.erro) {
                        mostrarErroValores(data.mensagem || 'Erro ao carregar valores do pedido');
                        return;
                    }

                    if (data.data) {
                        preencherValoresPedido(data.data);
                    } else {
                        mostrarErroValores('Dados de valores não encontrados');
                    }
                })
                .catch(error => {
                    loadingValores.style.display = 'none';
                    mostrarErroValores('Erro ao carregar valores do pedido. Tente novamente.');
                });
        }

        function preencherValoresPedido(valores) {
            const freteInput = document.getElementById('frete');
            const valorTotalInput = document.getElementById('valorTotalPedido');

            // Formatar valores para exibição
            freteInput.value = formatarMoeda(valores.frete || 0);
            valorTotalInput.value = formatarMoeda(valores.valorTotalPedido || 0);
        }

        function mostrarErroValores(mensagem) {
            const erroValores = document.getElementById('erroValores');
            const mensagemErroValores = document.getElementById('mensagemErroValores');

            mensagemErroValores.textContent = mensagem;
            erroValores.style.display = 'block';
        }

        function formatarMoeda(valor) {
            return Number(valor).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function mostrarErro(mensagem) {
            const cepError = document.getElementById('cepError');
            const cepInput = document.getElementById('cep');

            cepError.textContent = mensagem;
            cepInput.classList.add('is-invalid');
            cepError.style.display = 'block';
        }

        function limparErros() {
            const cepError = document.getElementById('cepError');
            const cepInput = document.getElementById('cep');

            cepError.textContent = '';
            cepInput.classList.remove('is-invalid');
            cepError.style.display = 'none';
        }

        // Confirmar pedido
        document.getElementById('btnConfirmarPedido').addEventListener('click', function() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            const logradouro = document.getElementById('logradouro').value;
            const numero = document.getElementById('numero').value;
            const bairro = document.getElementById('bairro').value;
            const complemento = document.getElementById('complemento').value;
            const cidade = document.getElementById('cidade').value;
            const uf = document.getElementById('uf').value;

            // Validações simples
            if (!cep || !logradouro || !numero || !bairro || !cidade || !uf) {
                mostrarErro('Por favor, preencha todos os campos obrigatórios.');
                return;
            }

            confirmarPedido();
        });

        function confirmarPedido() {
            // Exibir loading e ocultar mensagens de erro ou sucesso
            document.getElementById('loadingPedido').style.display = 'block';
            document.getElementById('erroPedido').style.display = 'none';
            document.getElementById('sucessoPedido').style.display = 'none';
            document.getElementById('btnConfirmarPedido').disabled = true;

            // Coleta os dados do endereço para enviar na requisição
            const dadosPedido = {
                cep: document.getElementById('cep').value.replace(/\D/g, ''),
                logradouro: document.getElementById('logradouro').value,
                numero: document.getElementById('numero').value,
                bairro: document.getElementById('bairro').value,
                complemento: document.getElementById('complemento').value,
                cidade: document.getElementById('cidade').value,
                uf: document.getElementById('uf').value
            };

            fetch('/api/v1/pedidos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dadosPedido)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingPedido').style.display = 'none';
                document.getElementById('btnConfirmarPedido').disabled = false;

                if (data.erro) {
                    // Exibe mensagem de erro
                    const mensagemErro = data.mensagem || 'Erro ao criar pedido';
                    document.getElementById('mensagemErroPedido').textContent = mensagemErro;
                    document.getElementById('erroPedido').style.display = 'block';
                } else {
                    // Exibe mensagem de sucesso
                    const mensagemSucesso = data.mensagem || 'Pedido criado com sucesso!';
                    document.getElementById('mensagemSucessoPedido').textContent = mensagemSucesso;
                    document.getElementById('sucessoPedido').style.display = 'block';

                    // Redireciona para a página de produtos após 3 segundos
                    setTimeout(() => {
                        window.location.href = '{{ route("novo-produto") }}';
                    }, 3000);
                }
            })
            .catch(error => {
                document.getElementById('loadingPedido').style.display = 'none';
                document.getElementById('btnConfirmarPedido').disabled = false;
                document.getElementById('mensagemErroPedido').textContent = 'Erro de conexão. Tente novamente.';
                document.getElementById('erroPedido').style.display = 'block';
            });
        }

        // Formulário submit
        document.getElementById('enderecoForm').addEventListener('submit', function(e) {
            e.preventDefault();
        });
    });
</script>
@endsection
