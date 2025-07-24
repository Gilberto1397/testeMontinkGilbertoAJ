<?php

namespace App\Http\Repositories;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Requests\AtualizarProdutoRequest;
use App\Http\Requests\CriarProdutoRequest;
use App\Http\Services\GetEstoqueByProdutoService;
use App\Models\Estoque;
use App\Models\Produto;
use DomainException;
use Illuminate\Support\Facades\DB;

class ProdutoRepositoryEloquent implements ProdutoRepository
{
    /**
     * Adiciona um novo produto ao banco de dados.
     * @param CriarProdutoRequest $request
     * @return true
     * @throws DomainException
     */
    public function criarProduto(CriarProdutoRequest $request): bool
    {
        DB::beginTransaction();

        $produtoCriado = Produto::create([
            'produtos_nome' => $request->nome,
            'produtos_preco' => $request->preco
        ]);

        if (!$produtoCriado instanceof Produto) {
            DB::rollBack();
            throw new DomainException('Erro ao criar produto!');
        }
        $estoqueSalvo = new Estoque();
        $estoqueSalvo->estoques_produto = $produtoCriado->produtos_id;
        $estoqueSalvo->estoques_quantidade = $request->estoque;

        if (!$estoqueSalvo->save()) {
            DB::rollBack();
            throw new DomainException('Erro ao salvar estoque de produto!');
        }
        $this->salvarVariacoesProduto($request->all(), $produtoCriado->produtos_id);

        DB::commit();
        return true;
    }

    /**
     * Obtém os IDs das últimas variações criadas.
     * @param int $limit
     * @return array
     */
    public function getIdsUltimasVariacoesCriadas($limit): array
    {
        return Produto::select('produtos_id')->orderBy('produtos_id', 'desc')->limit($limit)->get()->all();
    }

    /**
     * Atualiza um produto e seu estoque no banco de dados.
     * @param AtualizarProdutoRequest $request
     * @return true
     * @throws DomainException
     */
    public function atualizarProduto(AtualizarProdutoRequest $request): bool
    {
        $produto = $this->getProdutoById($request->produtoId);
        $produto->produtos_nome = $request->nome;
        $produto->produtos_preco = $request->preco;

        $estoque = (new GetEstoqueByProdutoService())
            ->getEstoqueByProdutoId(new EstoqueRepositoryEloquent(), $request->produtoId)->getData();

        if (empty($estoque)) {
            throw new DomainException('Estoque do produto não encontrado!');
        }
        $estoque->estoques_quantidade = $request->estoque;

        DB::beginTransaction();

        if (!$produto->save()) {
            DB::rollBack();
            throw new DomainException('Erro ao atualizar produto!');
        }

        if (!$estoque->save()) {
            DB::rollBack();
            throw new DomainException('Erro ao atualizar estoque do produto!');
        }
        $this->salvarVariacoesProduto($request->all(), $request->produtoId);

        DB::commit();
        return true;
    }

    /**
     * Recupera um produto pelo ID.
     * @param int $id
     * @return Produto|null
     */
    public function getProdutoById(int $id): ?Produto
    {
        return Produto::find($id);
    }

    /**
     * @param array $dadosProduto
     * @param int $produtoOrigem
     * @return void
     * @throws DomainException
     */
    public function salvarVariacoesProduto(array $dadosProduto, int $produtoOrigem): void
    {
        if (!empty($dadosProduto['produtoVariacao'])) {
            $variacoes = [];

            foreach ($dadosProduto['produtoVariacao'] as $variacao) {
                $variacoes[] = [
                    'produtos_nome' => $variacao['nome'],
                    'produtos_preco' => $dadosProduto['preco'],
                    'produtos_variacaode' => $produtoOrigem
                ];
            }

            if (!Produto::insert($variacoes)) {
                DB::rollBack();
                throw new DomainException('Erro ao salvar variações do produto!');
            }
            $idsUltimasVariacoes = $this->getIdsUltimasVariacoesCriadas(count($dadosProduto['produtoVariacao']));

            usort($idsUltimasVariacoes, function ($primeiraVariacao, $segundaVariacao) {
                return $primeiraVariacao->produtos_id - $segundaVariacao->produtos_id;
            });
            $estoque = [];

            foreach ($idsUltimasVariacoes as $key => $idUltimaVariacao) {
                $estoque[] = [
                    'estoques_produto' => $idUltimaVariacao->produtos_id,
                    'estoques_quantidade' => $dadosProduto['produtoVariacao'][$key]['estoque']
                ];
            }

            if (!Estoque::insert($estoque)) {
                DB::rollBack();
                throw new DomainException('Erro ao salvar estoque das variações do produto!');
            }
        }
    }
}