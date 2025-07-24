<?php

namespace App\Http\Services;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Requests\AtualizarProdutoRequest;

class AtualizarProdutoService
{
    /**
     * Adiciona um novo produto ao banco de dados.
     * @param ProdutoRepository $repository
     * @param AtualizarProdutoRequest $request
     * @return OrganizaRespostaRequisicao
     * @throws \DomainException
     */
    public function atualizarProduto(ProdutoRepository $repository, AtualizarProdutoRequest $request): OrganizaRespostaRequisicao
    {
        $repository->atualizarProduto($request);
        return new OrganizaRespostaRequisicao(200, 'Produto atualizado com sucesso!');
    }
}