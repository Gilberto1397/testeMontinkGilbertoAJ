<?php

namespace App\Http\Services;

use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Requests\AdicionarNoCarrinhoRequest;

class AdicionarAoCarrinhoService
{
    /**
     * @param AdicionarNoCarrinhoRequest $request
     * @return OrganizaRespostaRequisicao
     */
    public function adicionarAoCarrinho(AdicionarNoCarrinhoRequest $request): OrganizaRespostaRequisicao
    {
        $produtoAdicionado = [
            'carrinho' => [
                "produto{$request->produtoId}" => [
                    'produtoId' => $request->produtoId,
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