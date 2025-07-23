<?php

namespace App\Http\Contracts;

use App\Http\Requests\ProdutoRequest;

interface ProdutoRepository
{
    /**
     * Adiciona um novo produto ao banco de dados.
     * @param ProdutoRequest $request
     * @return bool
     */
    public function criarProduto(ProdutoRequest $request): bool;

    /**
     * Obtém os IDs das últimas variações criadas.
     * @param int $limit
     * @return array
     */
    public function getIdsUltimasVariacoesCriadas(int $limit): array;
}