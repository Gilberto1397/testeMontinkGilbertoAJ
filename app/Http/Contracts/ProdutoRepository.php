<?php

namespace App\Http\Contracts;

use App\Http\Requests\AtualizarProdutoRequest;
use App\Http\Requests\CriarProdutoRequest;
use App\Models\Produto;
use DomainException;

interface ProdutoRepository
{
    /**
     * Adiciona um novo produto e seu estoque ao banco de dados.
     * @param CriarProdutoRequest $request
     * @return bool
     */
    public function criarProduto(CriarProdutoRequest $request): bool;

    /**
     * Obtém os IDs das últimas variações criadas.
     * @param int $limit
     * @return array
     */
    public function getIdsUltimasVariacoesCriadas(int $limit): array;

    /**
     * Atualiza um produto e seu estoque no banco de dados.
     * @param AtualizarProdutoRequest $request
     * @return bool
     */
    public function atualizarProduto(AtualizarProdutoRequest $request): bool;

    /**
     * Recupera produtos por IDs.
     * @param array $ids
     * @return array
     */
    public function getProdutosByIds(array $ids): array;

    /**
     * @param array $dadosProduto
     * @param int $produtoOrigem
     * @return void
     * @throws DomainException
     */
    public function salvarVariacoesProduto(array $dadosProduto, int $produtoOrigem): void;
}