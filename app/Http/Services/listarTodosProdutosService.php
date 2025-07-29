<?php

namespace App\Http\Services;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Resources\ProdutoCollectionResource;

class listarTodosProdutosService
{
    public function listarTodosProdutos(ProdutoRepository $repository)
    {
        return new OrganizaRespostaRequisicao(
            200,
            '',
            new ProdutoCollectionResource($repository->listarTodosProdutos())
        );
    }
}