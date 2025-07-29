<?php

namespace App\Http\Services;

use App\Http\Contracts\CupomRepository;
use App\Http\Helpers\OrganizaRespostaRequisicao;
use App\Http\Requests\CriarCupomRequest;

class CriarCupomService
{
    /**
     * @param CriarCupomRequest $request
     * @param CupomRepository $repository
     * @return OrganizaRespostaRequisicao
     */
    public function criarCupom(CriarCupomRequest $request, CupomRepository $repository): OrganizaRespostaRequisicao
    {
        if (!$repository->criarCupom($request)) {
            return new OrganizaRespostaRequisicao(500, 'Erro ao criar cupom');
        }
        return new OrganizaRespostaRequisicao(201, 'Cupom criado com sucesso');
    }
}