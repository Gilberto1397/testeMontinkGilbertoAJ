<?php

namespace App\Http\Repositories;

use App\Http\Contracts\PedidoRepository;
use App\Models\Pedido;

class PedidoRepositoryEloquent implements PedidoRepository
{
    /**
     * Adiciona um novo pedido ao banco de dados.
     * @param float $valorPedido
     * @return bool
     */
    public function criarPedido(float $valorPedido): bool
    {
        try {
            $pedido = new Pedido();
            $pedido->pedidos_valor = $valorPedido;
            $pedido->pedidos_statuspedido = 1;
            $pedido->pedidos_data = date('Y-m-d');

            return $pedido->save();
        } catch (\PDOException $exception) {
            return false;
        }
    }
}