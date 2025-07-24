<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProdutoRepositoryEloquent;
use App\Http\Requests\AtualizarProdutoRequest;
use App\Http\Requests\CriarProdutoRequest;
use App\Http\Services\AtualizarProdutoService;
use App\Http\Services\CriarProdutoService;
use DomainException;
use Illuminate\Http\JsonResponse;
use PDOException;

class ProdutoController extends Controller
{
    public function criarProduto(CriarProdutoRequest $request): JsonResponse
    {
        try {
            $resposta = (new CriarProdutoService())->criarProduto(new ProdutoRepositoryEloquent(), $request);
            return response()->json([
                'mensagem' => $resposta->getMensagem(),
                'erro' => $resposta->getErro()
            ], $resposta->getStatusCode());
        } catch (PDOException | DomainException $exception) {
            return response()->json(['mensagem' => $exception->getMessage(), 'erro' => true], 500);
        }
    }

    public function atualizarProduto(AtualizarProdutoRequest $request): JsonResponse
    {
        try {
            $resposta = (new AtualizarProdutoService())->atualizarProduto(new ProdutoRepositoryEloquent(), $request);
            return response()->json([
                'mensagem' => $resposta->getMensagem(),
                'erro' => $resposta->getErro()
            ], $resposta->getStatusCode());
        } catch (PDOException | DomainException $exception) {
            return response()->json(['mensagem' => $exception->getMessage(), 'erro' => true], 500);
        }
    }
}
