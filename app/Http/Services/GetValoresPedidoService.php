<?php

namespace App\Http\Services;

use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Repositories\ProdutoRepositoryEloquent;

class GetValoresPedidoService
{
    public const FRETE_TIPO_UM = 15.00;
    public const FRETE_TIPO_DOIS = 20.00;
    public const FRETE_GRATIS = 0.00;

    /**
     * @return OrganizaRespostaRequisicao
     */
    public function getValoresPedido(): OrganizaRespostaRequisicao
    {
        $totais = $this->calculaTotaisPedido();
        session(['totalPedido' => $totais['valorTotalPedido']]);

        return new OrganizaRespostaRequisicao(200, '', $totais);
    }

    /**
     * @return array
     */
    private function getProdutosIdsDoCarrinho(): array
    {
        $carrinho = $this->getProdutosCarrinho();
        $idsProdutos = [];

        foreach ($carrinho->carrinho as $produto) {
            $idsProdutos[] = $produto['produtoId'];
        }
        return $idsProdutos;
    }

    /**
     * @return array
     */
    private function getProdutos(): array
    {
        return (new GetProdutosByIdsService())
            ->getProdutosByIds(new ProdutoRepositoryEloquent(), $this->getProdutosIdsDoCarrinho())->getData();
    }

    /**
     * @return array
     */
    private function organizaValoresEQuantidades(): array
    {
        $valoresQuantidades = [];
        $carrinho = $this->getProdutosCarrinho();

        foreach ($this->getProdutos() as $produto) {
            foreach ($carrinho->carrinho as $carrinhoProduto) {
                if ($produto->produtos_id === $carrinhoProduto['produtoId']) {
                    $valoresQuantidades[] = [
                        'quantidade' => $carrinhoProduto['quantidade'],
                        'valorUnitario' => $produto->produtos_preco,
                    ];
                }
            }
        }
        return $valoresQuantidades;
    }

    /**
     * @return array
     */
    private function calculaTotaisPedido(): array
    {
        $totalProdutos = 0;

        foreach ($this->organizaValoresEQuantidades() as $valorQuantidade) {
            $totalProdutos += $valorQuantidade['quantidade'] * $valorQuantidade['valorUnitario'];
        }
        return [
            'valorTotalProdutos' => $totalProdutos,
            'frete' => $this->calculaFrete($totalProdutos),
            'valorTotalPedido' => $totalProdutos + $this->calculaFrete($totalProdutos),
        ];
    }

    /**
     * @param float $totalPedido
     * @return float
     */
    private function calculaFrete(float $totalPedido): float
    {
        if ($totalPedido >= 52 && $totalPedido <= 166.59) {
            return self::FRETE_TIPO_UM;
        } elseif ($totalPedido > 200) {
            return self::FRETE_GRATIS;
        }
        return self::FRETE_TIPO_DOIS;
    }

    /**
     * @return object
     * @throws \DomainException
     */
    private function getProdutosCarrinho()
    {
        $carrinho = session('carrinho');

        if (empty($carrinho)) {
            throw new \DomainException('Carrinho vazio!');
        }
        return $carrinho;
    }
}