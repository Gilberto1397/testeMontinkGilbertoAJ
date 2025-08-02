<?php

namespace App\Http\Services;

use App\Http\Contracts\PedidoRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;

class CriarPedidoService
{
    /**
     * @param PedidoRepository $pedidoRepository
     * @return OrganizaRespostaRequisicao
     */
    public function criarPedido(PedidoRepository $pedidoRepository): OrganizaRespostaRequisicao
    {
        $pedidoCriado = $pedidoRepository->criarPedido(session('totalPedido'));

        if (!$pedidoCriado) {
            return new OrganizaRespostaRequisicao(500, 'Erro ao criar pedido');
        }
        session(['totalPedido' => 0, 'carrinho' => null]);
        return new OrganizaRespostaRequisicao(201, 'Pedido criado com sucesso');
    }
}