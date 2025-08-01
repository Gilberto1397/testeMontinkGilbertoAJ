<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <title>Novo Pedido</title>
</head>
<body>
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

            // Formulário submit
            document.getElementById('enderecoForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Aqui você pode adicionar a lógica para processar o formulário
                alert('Endereço confirmado! Aqui você pode adicionar a lógica para processar o pedido.');
            });
        });
    </script>

    <style>
        .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .form-label {
            font-weight: 500;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .btn {
            border-radius: 0.375rem;
        }
    </style>
</body>
</html>
