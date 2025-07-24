<?php

namespace App\Http\Services;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Requests\CriarProdutoRequest;

class CriarProdutoService
{
    /**
     * Adiciona um novo produto ao banco de dados.
     * @param ProdutoRepository $repository
     * @param CriarProdutoRequest $request
     * @return OrganizaRespostaRequisicao
     * @throws \DomainException
     */
    public function criarProduto(ProdutoRepository $repository, CriarProdutoRequest $request): OrganizaRespostaRequisicao
    {
        $repository->criarProduto($request);
        return new OrganizaRespostaRequisicao(201, 'Produto criado com sucesso!');
    }
}