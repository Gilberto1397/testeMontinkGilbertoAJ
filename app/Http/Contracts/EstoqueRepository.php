<?php

namespace App\Http\Contracts;

use App\Models\Estoque;

interface EstoqueRepository
{
    /**
     * Recupera o estoque de um produto pelo ID do produto.
     * @param int $produtoId
     * @return Estoque|null
     */
    public function getEstoqueByProdutoId(int $produtoId): ?Estoque;
}