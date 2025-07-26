<?php

namespace App\Http\Controllers;

use App\Http\Services\GetValoresPedidoService;

class PedidoController extends Controller
{
    public function getValoresPedido()
    {
        try {
            $resposta = (new GetValoresPedidoService())->getValoresPedido();
            return response()
                ->json(['erro' => $resposta->getErro(), 'data' => $resposta->getData()], $resposta->getStatusCode());
        } catch (\DomainException $exception) {
            return response()->json(['mensagem' => $exception->getMessage(), 'erro' => true], 500);
        }
    }
}
