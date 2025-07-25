<?php

namespace App\Http\Controllers;

use App\Http\Services\BuscarCepService;
use Illuminate\Http\JsonResponse;

class CepController extends Controller
{
    public function buscarCep(int $cep): JsonResponse
    {
        $resposta = (new BuscarCepService())->buscarCep($cep);
        return response()->json([
            'mensagem' => $resposta->getMensagem(),
            'erro' => $resposta->getErro(),
            'data' => $resposta->getData()
        ], $resposta->getStatusCode());
    }
}
