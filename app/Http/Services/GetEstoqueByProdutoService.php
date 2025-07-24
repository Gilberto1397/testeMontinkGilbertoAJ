<?php

namespace App\Http\Services;

use App\Http\Contracts\EstoqueRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;

class GetEstoqueByProdutoService
{
    /**
     * Recupera o estoque de um produto pelo ID do produto.
     * @param EstoqueRepository $repository
     * @param int $produtoId
     * @return OrganizaRespostaRequisicao
     */
    public function getEstoqueByProdutoId(EstoqueRepository $repository, int $produtoId): OrganizaRespostaRequisicao
    {
        $estoque = $repository->getEstoqueByProdutoId($produtoId);
        return new OrganizaRespostaRequisicao(200, '', $estoque);
    }
}