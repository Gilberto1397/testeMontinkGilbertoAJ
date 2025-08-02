@extends('layouts.app')

@section('title', 'Novo Cupom')

@section('styles')
<style>
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <article class="card border-0 rounded-3">
                <header class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0"><i class="fas fa-tag me-2"></i>Criar Novo Cupom</h1>
                </header>
                <div class="card-body p-4">
                    <form id="cupomForm">
                        <fieldset>
                            <legend class="visually-hidden">Informações do Cupom</legend>

                            <div class="mb-3">
                                <label for="cupomCodigo" class="form-label">Código do Cupom</label>
                                <input type="text" class="form-control" id="cupomCodigo"
                                       placeholder="Ex: VERAO2025">
                                <span id="cupomCodigoErro" class="text-danger fw-bold"></span>
                            </div>

                            <div class="mb-3">
                                <label for="valorMinimo" class="form-label">Valor Mínimo (R$)</label>

                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" id="valorMinimo" placeholder="0.00">
                                </div>

                                <span id="valorMinimoErro" class="text-danger fw-bold"></span>
                            </div>

                            <div class="mb-3">
                                <label for="valorDesconto" class="form-label">Valor do Desconto (R$)</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" id="valorDesconto" placeholder="0.00">
                                </div>

                                <span id="valorDescontoErro" class="text-danger fw-bold"></span>
                            </div>

                            <div class="mb-4">
                                <label for="dataExpiracao" class="form-label">Data de Expiração</label>
                                <input type="text" class="form-control" id="dataExpiracao" placeholder="DD/MM/AAAA">
                                <span id="dataExpiracaoErro" class="text-danger fw-bold"></span>
                            </div>
                        </fieldset>

                        <div class="d-grid gap-2">
                            <button onclick="criarCupom()" type="button" id="btnCriarCupom" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Criar Cupom
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    "use strict";

    // Seleciona os campos numéricos
    const valorMinimoInput = document.getElementById('valorMinimo');
    const valorDescontoInput = document.getElementById('valorDesconto');

    // Função para validar entrada numérica (aceita números e ponto decimal)
    function validarEntradaNumerica(event) {
        // Permite: backspace, delete, tab, escape, enter e ponto
        if ([46, 8, 9, 27, 13, 110, 190].indexOf(event.keyCode) !== -1 ||
            // Permite: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (event.keyCode === 65 && (event.ctrlKey === true || event.metaKey === true)) ||
            (event.keyCode === 67 && (event.ctrlKey === true || event.metaKey === true)) ||
            (event.keyCode === 86 && (event.ctrlKey === true || event.metaKey === true)) ||
            (event.keyCode === 88 && (event.ctrlKey === true || event.metaKey === true)) ||
            // Permite: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // Permite a ação padrão da tecla
            return;
        }

        // Cancela o evento se não for um número (0-9)
        if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) &&
            (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }

        // Verifica se já existe um ponto decimal e bloqueia outro
        if (event.key === '.' && this.value.includes('.')) {
            event.preventDefault();
        }
    }

    // Adiciona listeners para os eventos de tecla
    valorMinimoInput.addEventListener('keydown', validarEntradaNumerica);
    valorDescontoInput.addEventListener('keydown', validarEntradaNumerica);

    // Validação no input para garantir apenas números e formatação ao perder o foco
    function formatarValorMonetario(event) {
        const valor = event.target.value.replace(/[^\d.]/g, ''); // Remove caracteres não numéricos, exceto ponto

        if (valor) {
            // Converte para número e formata com duas casas decimais
            const valorNumerico = parseFloat(valor);
            if (!isNaN(valorNumerico)) {
                event.target.value = valorNumerico.toFixed(2);
            }
        }
    }

    // Adiciona eventos para formatar valor ao perder foco
    valorMinimoInput.addEventListener('blur', formatarValorMonetario);
    valorDescontoInput.addEventListener('blur', formatarValorMonetario);

    async function criarCupom() {
        try {
            limparMensagensErro();

            const cupomCodigo = document.getElementById('cupomCodigo').value;
            const valorMinimo = document.getElementById('valorMinimo').value;
            const valorDesconto = document.getElementById('valorDesconto').value;
            const dataExpiracao = document.getElementById('dataExpiracao').value

            const opcoes = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cupomCodigo: cupomCodigo,
                    valorMinimo: valorMinimo,
                    valorDesconto: valorDesconto,
                    dataExpiracao: dataExpiracao
                })
            };

            const response = await fetch('http://127.0.0.1:8000/api/v1/cupons', opcoes);
            const resultado = await response.json();

            if (resultado.erro) {
                if (resultado.mensagens) {
                    exibirErroInput(resultado.mensagens);
                    return;
                }
            }

            alert('Cupom criado com sucesso!');
            limpaFormulario();
        } catch (erro) {
            alert('Erro ao criar cupom!');
        }
    }

    function exibirErroInput(mensagensDeErro) {
        for (const campoMensagem in mensagensDeErro) {
            const mensagemDeErro = mensagensDeErro[campoMensagem][0];

            if (campoMensagem === 'cupomCodigo') {
                document.getElementById('cupomCodigoErro').textContent = mensagemDeErro;
            } else if (campoMensagem === 'valorMinimo') {
                document.getElementById('valorMinimoErro').textContent = mensagemDeErro;
            } else if (campoMensagem === 'valorDesconto') {
                document.getElementById('valorDescontoErro').textContent = mensagemDeErro;
            } else if (campoMensagem === 'dataExpiracao') {
                document.getElementById('dataExpiracaoErro').textContent = mensagemDeErro;
            }
        }
    }

    function limparMensagensErro() {
        document.getElementById('cupomCodigoErro').textContent = '';
        document.getElementById('valorMinimoErro').textContent = '';
        document.getElementById('valorDescontoErro').textContent = '';
        document.getElementById('dataExpiracaoErro').textContent = '';
    }

    function limpaFormulario() {
        document.getElementById('cupomForm').reset();
    }
</script>
@endsection
