<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PedidoRepositoryEloquent;
use App\Http\Services\CriarPedidoService;
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

    public function criarPedido()
    {
        $resposta = (new CriarPedidoService())->criarPedido(new PedidoRepositoryEloquent());
        return response()
            ->json(['erro' => $resposta->getErro(), 'mensagem' => $resposta->getMensagem()], $resposta->getStatusCode());
    }
}
