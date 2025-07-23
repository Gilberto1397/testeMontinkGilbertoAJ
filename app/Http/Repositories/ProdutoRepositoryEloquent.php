<?php

namespace App\Http\Repositories;

use App\Http\Contracts\ProdutoRepository;
use App\Http\Requests\ProdutoRequest;
use App\Models\Estoque;
use App\Models\Produto;
use DomainException;
use Illuminate\Support\Facades\DB;

class ProdutoRepositoryEloquent implements ProdutoRepository
{
    /**
     * Adiciona um novo produto ao banco de dados.
     * @param ProdutoRequest $request
     * @return true
     * @throws DomainException
     */
    public function criarProduto(ProdutoRequest $request): bool
    {
        DB::beginTransaction();

        $produtoCriado = Produto::create([
            'produtos_nome' => $request->nome,
            'produtos_preco' => $request->preco
        ]);

        if (!$produtoCriado instanceof Produto) {
            DB::rollBack();
            throw new DomainException('Erro ao criar produto');
        }
        $estoqueSalvo = new Estoque();
        $estoqueSalvo->estoque_produto = $produtoCriado->produtos_id;
        $estoqueSalvo->estoque_quantidade = $request->estoque;

        if (!$estoqueSalvo->save()) {
            DB::rollBack();
            throw new DomainException('Erro ao salvar estoque de produto!');
        }

        if (!empty($request->produtoVariacao)) {
            $variacoes = [];

            foreach ($request->produtoVariacao as $variacao) {
                $variacoes[] = [
                    'produtos_nome' => $variacao['nome'],
                    'produtos_preco' => $request->preco,
                    'produtos_variacaode' => $produtoCriado->produtos_id
                ];
            }

            if (!Produto::insert($variacoes)) {
                DB::rollBack();
                throw new DomainException('Erro ao salvar variações do produto!');
            }
            $idsUltimasVariacoes = $this->getIdsUltimasVariacoesCriadas(count($request->produtoVariacao));

            usort($idsUltimasVariacoes, function ($primeiraVariacao, $segundaVariacao) {
                return $primeiraVariacao->produtos_id - $segundaVariacao->produtos_id;
            });
            $estoque = [];

            foreach ($idsUltimasVariacoes as $key => $idUltimaVariacao) {
                $estoque[] = [
                    'estoque_produto' => $idUltimaVariacao->produtos_id,
                    'estoque_quantidade' => $request->produtoVariacao[$key]['estoque']
                ];
            }

            if (!Estoque::insert($estoque)) {
                DB::rollBack();
                throw new DomainException('Erro ao salvar estoque das variações do produto!');
            }
        }
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
}