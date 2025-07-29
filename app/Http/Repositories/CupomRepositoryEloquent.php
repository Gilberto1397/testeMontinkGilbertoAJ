<?php

namespace App\Http\Repositories;

use App\Http\Contracts\CupomRepository;
use App\Http\Requests\CriarCupomRequest;
use App\Models\Cupom;

class CupomRepositoryEloquent implements CupomRepository
{
    /**
     * Adiciona um novo cupom ao banco de dados.
     * @param CriarCupomRequest $request
     * @return bool
     */
    public function criarCupom(CriarCupomRequest $request): bool
    {
        try {
            $cupom = new Cupom();
            $cupom->cupons_codigo = $request->cupomCodigo;
            $cupom->cupons_valorminimo = $request->valorMinimo;
            $cupom->cupons_valordesconto = $request->valorDesconto;
            $cupom->cupons_expiracao = \DateTime::createFromFormat('d/m/Y', $request->dataExpiracao)
                ->format('Y-m-d');
            return $cupom->save();
        } catch (\PDOException $exception) {
            return false;
        }
    }
}