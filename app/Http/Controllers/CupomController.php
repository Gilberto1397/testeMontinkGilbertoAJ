<?php

namespace App\Http\Controllers;


use App\Http\Repositories\CupomRepositoryEloquent;
use App\Http\Requests\CriarCupomRequest;
use App\Http\Services\CriarCupomService;

class CupomController extends Controller
{
    public function criarCupom(CriarCupomRequest $request)
    {
        $resposta = (new CriarCupomService())->criarCupom($request, new CupomRepositoryEloquent());
        return response()->json([
            'message' => $resposta->getMensagem(),
            'error' => $resposta->getErro()
        ], $resposta->getStatusCode());
    }
}
