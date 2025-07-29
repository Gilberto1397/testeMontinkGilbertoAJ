<?php

namespace App\Http\Contracts;

interface PedidoRepository
{
    /**
     * Adiciona um novo pedido ao banco de dados.
     * @param float $valorPedido
     * @return bool
     */
    public function criarPedido(float $valorPedido): bool;
}