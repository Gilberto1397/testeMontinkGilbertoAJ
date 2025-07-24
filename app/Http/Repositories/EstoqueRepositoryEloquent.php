<?php

namespace App\Http\Repositories;

use App\Http\Contracts\EstoqueRepository;
use App\Models\Estoque;

class EstoqueRepositoryEloquent implements EstoqueRepository
{
    /**
     * Obtém o estoque de um produto pelo ID do produto.
     * @param int $produtoId
     * @return Estoque|null
     */
    public function getEstoqueByProdutoId(int $produtoId): ?Estoque
    {
        return Estoque::where('estoques_produto', $produtoId)->first();
    }
}