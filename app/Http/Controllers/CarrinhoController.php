<?php

namespace App\Http\Controllers;


use App\Http\Requests\AdicionarNoCarrinhoRequest;
use App\Http\Services\AdicionarAoCarrinhoService;

class CarrinhoController extends Controller
{
    public function adicionarAoCarrinho(AdicionarNoCarrinhoRequest $request)
    {
        $reposta = (new AdicionarAoCarrinhoService())->adicionarAoCarrinho($request);
        return response()
            ->json(['mensagem' => $reposta->getMensagem(), 'erro' => $reposta->getErro()], $reposta->getStatusCode());
    }
}
