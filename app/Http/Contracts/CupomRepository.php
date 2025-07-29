<?php

namespace App\Http\Contracts;

use App\Http\Requests\CriarCupomRequest;

interface CupomRepository
{
    /**
     * Adiciona um novo cupom ao banco de dados.
     * @param CriarCupomRequest $request
     * @return bool
     */
    public function criarCupom(CriarCupomRequest $request): bool;
}