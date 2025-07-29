<?php

namespace App\Http\Services;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;

class GetProdutosByIdsService
{
    /**
     * @param ProdutoRepository $repository
     * @param array $ids
     * @return OrganizaRespostaRequisicao
     * TODO resource?
     */
    public function getProdutosByIds(ProdutoRepository $repository, array $ids): OrganizaRespostaRequisicao
    {
        if (empty($ids)) {
            throw new \DomainException('Nenhum produto solicitado!');
        }

        $produtos = $repository->getProdutosByIds($ids);
        return new OrganizaRespostaRequisicao(200, '', $produtos);
    }
}