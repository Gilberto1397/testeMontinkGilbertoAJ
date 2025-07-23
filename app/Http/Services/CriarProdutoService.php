<?php

namespace App\Http\Services;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Requests\ProdutoRequest;

class CriarProdutoService
{
    /**
     * Adiciona um novo produto ao banco de dados.
     * @param ProdutoRepository $repository
     * @param ProdutoRequest $request
     * @return OrganizaRespostaRequisicao
     * @throws \DomainException
     */
    public function criarProduto(ProdutoRepository $repository, ProdutoRequest $request): OrganizaRespostaRequisicao
    {
        $repository->criarProduto($request);
        return new OrganizaRespostaRequisicao(201, 'Produto criado com sucesso');
    }
}