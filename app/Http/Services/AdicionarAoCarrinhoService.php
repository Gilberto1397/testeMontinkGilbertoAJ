<?php

namespace App\Http\Services;

use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Requests\AdicionarNoCarrinhoRequest;

class AdicionarAoCarrinhoService
{
    public function adicionarAoCarrinho(AdicionarNoCarrinhoRequest $request)
    {
        $produtoAdicionado = [
            'carrinho' => [
                "produto{$request->produtoId}" => [
                    'quantidade' => $request->quantidade,
                    'nomeProduto' => $request->nomeProduto,
                ]
            ]
        ];
        $carrinhoSessao = (array)session('carrinho');

        if (!empty($carrinhoSessao)) {
            foreach ($carrinhoSessao['carrinho'] as $produtoId => $produto) {
                $produtoAdicionado['carrinho'][$produtoId] = $produto;
            }
        }

        session(['carrinho' => (object)$produtoAdicionado]);
        return new OrganizaRespostaRequisicao(200, 'Produto adicionado ao carrinho com sucesso!');
    }
}